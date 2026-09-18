
<?php

error_reporting(E_ALL);
ini_set("display_errors", 1);

session_start();

require_once "../config/database.php";


/* ==============================
   Check Login
============================== */

if (!isset($_SESSION["user_id"])) {

    header("Location: ../auth/login.php");

    exit;
}


/* ==============================
   Check Citizen Role
============================== */

if ($_SESSION["user_role"] !== "citizen") {

    die("Access denied.");
}


/* ==============================
   Check Request ID
============================== */

if (!isset($_GET["id"]) || !is_numeric($_GET["id"])) {

    die("Invalid request ID.");
}

$request_id = intval($_GET["id"]);

$user_id = $_SESSION["user_id"];


/* ==============================
   Get Request Information
============================== */

$sql = "
    SELECT
        service_requests.id,
        service_requests.request_number,
        service_requests.description,
        service_requests.status,
        service_requests.created_at,
        government_services.service_name,
        government_agencies.agency_name
    FROM service_requests

    INNER JOIN government_services
        ON service_requests.service_id =
           government_services.id

    INNER JOIN government_agencies
        ON government_services.agency_id =
           government_agencies.id

    WHERE service_requests.id = ?
    AND service_requests.citizen_id = ?
";


$stmt = $conn->prepare($sql);

$stmt->bind_param(
    "ii",
    $request_id,
    $user_id
);

$stmt->execute();

$result = $stmt->get_result();


/* ==============================
   Check Request
============================== */

if ($result->num_rows === 0) {

    die("Request not found or access denied.");
}

$request = $result->fetch_assoc();


/* ==============================
   Get Status History
============================== */

$history_sql = "
    SELECT
        request_status_logs.old_status,
        request_status_logs.new_status,
        request_status_logs.changed_at,
        users.full_name AS changed_by_name
    FROM request_status_logs

    INNER JOIN users
        ON request_status_logs.changed_by = users.id

    WHERE request_status_logs.request_id = ?

    ORDER BY request_status_logs.id ASC
";


$history_stmt = $conn->prepare($history_sql);

$history_stmt->bind_param(
    "i",
    $request_id
);

$history_stmt->execute();

$history_result = $history_stmt->get_result();

?>

<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>
        N-GATE | Track Request
    </title>

    <link
        rel="stylesheet"
        href="../assets/css/style.css">

</head>


<body>


    <!-- Navigation -->

    <nav class="navbar">

        <div class="logo">
            N-GATE
        </div>

        <div class="nav-links">

            <a href="../index.php">
                Home
            </a>

            <a href="dashboard.php">
                Dashboard
            </a>

            <a href="services.php">
                Services
            </a>

            <a href="requests.php">
                My Requests
            </a>

            <a href="../auth/logout.php">
                Logout
            </a>

        </div>

    </nav>


    <!-- Main Section -->

    <section class="section">

        <h1>
            Track Service Request
        </h1>

        <p>
            View the current processing status and
            history of your government service request.
        </p>


        <!-- Request Information -->

        <div class="card">

            <h2>
                Request Information
            </h2>


            <p>

                <strong>
                    Request Number:
                </strong>

                <?php

                echo htmlspecialchars(
                    $request["request_number"]
                );

                ?>

            </p>


            <p>

                <strong>
                    Service:
                </strong>

                <?php

                echo htmlspecialchars(
                    $request["service_name"]
                );

                ?>

            </p>


            <p>

                <strong>
                    Agency:
                </strong>

                <?php

                echo htmlspecialchars(
                    $request["agency_name"]
                );

                ?>

            </p>


            <p>

                <strong>
                    Description:
                </strong>

                <?php

                echo htmlspecialchars(
                    $request["description"]
                );

                ?>

            </p>


            <p>

                <strong>
                    Submitted:
                </strong>

                <?php

                echo htmlspecialchars(
                    $request["created_at"]
                );

                ?>

            </p>


            <p>

                <strong>
                    Current Status:
                </strong>

                <?php

                echo htmlspecialchars(
                    $request["status"]
                );

                ?>

            </p>

        </div>


        <!-- Status History -->

        <div class="card">

            <h2>
                Status History
            </h2>


            <?php if ($history_result->num_rows > 0): ?>

                <div style="overflow-x:auto;">

                    <table
                        border="1"
                        cellpadding="10"
                        cellspacing="0"
                        width="100%">

                        <thead>

                            <tr>

                                <th>
                                    Previous Status
                                </th>

                                <th>
                                    New Status
                                </th>

                                <th>
                                    Updated By
                                </th>

                                <th>
                                    Date & Time
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            <?php while ($history = $history_result->fetch_assoc()): ?>

                                <tr>

                                    <td>

                                        <?php

                                        echo htmlspecialchars(
                                            $history["old_status"]
                                        );

                                        ?>

                                    </td>


                                    <td>

                                        <?php

                                        echo htmlspecialchars(
                                            $history["new_status"]
                                        );

                                        ?>

                                    </td>


                                    <td>

                                        <?php

                                        echo htmlspecialchars(
                                            $history["changed_by_name"]
                                        );

                                        ?>

                                    </td>


                                    <td>

                                        <?php

                                        echo htmlspecialchars(
                                            $history["changed_at"]
                                        );

                                        ?>

                                    </td>

                                </tr>

                            <?php endwhile; ?>

                        </tbody>

                    </table>

                </div>


            <?php else: ?>

                <p>
                    No status changes have been recorded yet.
                </p>

            <?php endif; ?>

        </div>


        <!-- Back Button -->

        <div class="card">

            <a
                class="btn"
                href="requests.php">

                Back to My Requests

            </a>

        </div>

    </section>


    <!-- Footer -->

    <footer class="footer">

        <p>
            N-GATE — Nepal Government Access & Trusted Exchange
        </p>

    </footer>


</body>

</html>
