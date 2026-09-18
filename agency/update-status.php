<?php

session_start();

require_once "../config/database.php";


/* Check login */

if (!isset($_SESSION["user_id"])) {
    header("Location: ../auth/login.php");
    exit;
}


/* Check agency role */

if ($_SESSION["user_role"] !== "agency") {
    die("Access denied.");
}


/* Check request ID */

if (!isset($_GET["id"]) || !is_numeric($_GET["id"])) {
    die("Invalid request ID.");
}

$request_id = intval($_GET["id"]);


/* Get current request status */

$sql = "
    SELECT
        service_requests.id,
        service_requests.request_number,
        service_requests.status,
        service_requests.created_at,

        users.full_name AS citizen_name,

        government_services.service_name,

        government_agencies.agency_name

    FROM service_requests

    INNER JOIN users
        ON service_requests.citizen_id = users.id

    INNER JOIN government_services
        ON service_requests.service_id =
           government_services.id

    INNER JOIN government_agencies
        ON government_services.agency_id =
           government_agencies.id

    WHERE service_requests.id = ?
";


$stmt = $conn->prepare($sql);

$stmt->bind_param(
    "i",
    $request_id
);

$stmt->execute();

$result = $stmt->get_result();


if ($result->num_rows === 0) {
    die("Service request not found.");
}


$request = $result->fetch_assoc();


/* Handle status update */

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $new_status = $_POST["status"] ?? "";

    $allowed_statuses = [
        "Pending",
        "Processing",
        "Approved",
        "Rejected",
        "Completed"
    ];


    /* Validate status */

    if (!in_array($new_status, $allowed_statuses, true)) {
        die("Invalid status selected.");
    }


    $old_status = $request["status"];

    $changed_by = $_SESSION["user_id"];


    /* Prevent unnecessary duplicate update */

    if ($old_status === $new_status) {

        header(
            "Location: request-details.php?id=" .
                $request_id
        );

        exit;
    }


    /*
     * Start database transaction
     */

    $conn->begin_transaction();


    try {

        /*
         * Update request status
         */

        $update_sql = "
            UPDATE service_requests
            SET status = ?
            WHERE id = ?
        ";

        $update_stmt = $conn->prepare($update_sql);

        $update_stmt->bind_param(
            "si",
            $new_status,
            $request_id
        );

        if (!$update_stmt->execute()) {
            throw new Exception(
                "Failed to update request status."
            );
        }


        /*
         * Insert audit log
         */

        $log_sql = "
            INSERT INTO request_status_logs
            (
                request_id,
                changed_by,
                old_status,
                new_status
            )
            VALUES (?, ?, ?, ?)
        ";

        $log_stmt = $conn->prepare($log_sql);

        $log_stmt->bind_param(
            "iiss",
            $request_id,
            $changed_by,
            $old_status,
            $new_status
        );


        if (!$log_stmt->execute()) {
            throw new Exception(
                "Failed to create audit log."
            );
        }


        /*
         * Commit both operations
         */

        $conn->commit();


        /*
         * Return to request details
         */

        header(
            "Location: request-details.php?id=" .
                $request_id
        );

        exit;
    } catch (Exception $e) {

        /*
         * Roll back if anything fails
         */

        $conn->rollback();

        die("Transaction failed: " .
            htmlspecialchars($e->getMessage()));
    }
}

?>

<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>N-GATE - Update Request Status</title>

    <link
        rel="stylesheet"
        href="../assets/css/style.css">

</head>


<body>


    <nav class="navbar">

        <div class="logo">
            N-GATE Agency
        </div>

        <div class="nav-links">

            <a href="../index.php">
                Home
            </a>

            <a href="dashboard.php">
                Dashboard
            </a>

            <a href="requests.php">
                Requests
            </a>

            <a href="../auth/logout.php">
                Logout
            </a>

        </div>

    </nav>


    <section class="section">

        <h1>
            Update Request Status
        </h1>

        <p>
            Agency officers can update the processing
            status of this citizen service request.
        </p>


        <div class="card">

            <h2>
                Request Information
            </h2>


            <p>
                <strong>Request Number:</strong>

                <?php
                echo htmlspecialchars(
                    $request["request_number"]
                );
                ?>
            </p>


            <p>
                <strong>Citizen:</strong>

                <?php
                echo htmlspecialchars(
                    $request["citizen_name"]
                );
                ?>
            </p>


            <p>
                <strong>Service:</strong>

                <?php
                echo htmlspecialchars(
                    $request["service_name"]
                );
                ?>
            </p>


            <p>
                <strong>Agency:</strong>

                <?php
                echo htmlspecialchars(
                    $request["agency_name"]
                );
                ?>
            </p>


            <p>
                <strong>Current Status:</strong>

                <?php
                echo htmlspecialchars(
                    $request["status"]
                );
                ?>
            </p>

        </div>


        <div class="card">

            <h2>
                Change Status
            </h2>


            <form method="POST">

                <label for="status">
                    Select New Status
                </label>


                <select
                    name="status"
                    id="status"
                    required>

                    <option value="">
                        -- Select Status --
                    </option>


                    <?php

                    $statuses = [
                        "Pending",
                        "Processing",
                        "Approved",
                        "Rejected",
                        "Completed"
                    ];

                    ?>


                    <?php foreach ($statuses as $status): ?>

                        <option
                            value="<?php echo htmlspecialchars($status); ?>"
                            <?php
                            if ($request["status"] === $status) {
                                echo "selected";
                            }
                            ?>>

                            <?php
                            echo htmlspecialchars($status);
                            ?>

                        </option>

                    <?php endforeach; ?>

                </select>


                <br><br>


                <button
                    type="submit"
                    class="btn">
                    Update Status
                </button>


                <a
                    href="request-details.php?id=<?php echo $request_id; ?>"
                    class="btn">
                    Cancel
                </a>


            </form>

        </div>


    </section>


    <footer class="footer">

        <p>
            N-GATE — Academic E-Governance Project |
            B.Sc. CSIT
        </p>

    </footer>


</body>

</html>