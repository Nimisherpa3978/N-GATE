<?php

session_start();

require_once "../config/database.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: ../auth/login.php");
    exit;
}

if ($_SESSION["user_role"] !== "citizen") {
    die("Access denied.");
}

$service_id = isset($_GET["service_id"])
    ? (int) $_GET["service_id"]
    : 0;

if ($service_id <= 0) {
    die("Invalid service.");
}


/* Get service information */

$stmt = $conn->prepare("
    SELECT
        government_services.id,
        government_services.service_name,
        government_services.description,
        government_agencies.agency_name
    FROM government_services
    INNER JOIN government_agencies
        ON government_services.agency_id = government_agencies.id
    WHERE government_services.id = ?
      AND government_services.status = 'Active'
");

$stmt->bind_param("i", $service_id);

$stmt->execute();

$result = $stmt->get_result();

$service = $result->fetch_assoc();

$stmt->close();

if (!$service) {
    die("Service not found.");
}


$message = "";
$error = "";


/* Submit request */

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $description = trim($_POST["description"] ?? "");

    $consent = isset($_POST["consent"]);


    if (!$consent) {

        $error = "You must provide consent before submitting the request.";
    } else {

        /* Generate request number */

        $request_number =
            "NGT-" .
            date("Ymd") .
            "-" .
            rand(1000, 9999);


        /* Start transaction */

        $conn->begin_transaction();

        try {

            /* Insert service request */

            $stmt = $conn->prepare("
                INSERT INTO service_requests
                (
                    citizen_id,
                    service_id,
                    request_number,
                    description,
                    status
                )
                VALUES (?, ?, ?, ?, 'Pending')
            ");

            $stmt->bind_param(
                "iiss",
                $_SESSION["user_id"],
                $service_id,
                $request_number,
                $description
            );

            $stmt->execute();

            $request_id = $conn->insert_id;

            $stmt->close();


            /* Insert consent */

            $stmt = $conn->prepare("
                INSERT INTO consents
                (
                    citizen_id,
                    request_id,
                    consent_status
                )
                VALUES (?, ?, 'Granted')
            ");

            $stmt->bind_param(
                "ii",
                $_SESSION["user_id"],
                $request_id
            );

            $stmt->execute();

            $stmt->close();


            /* Insert audit log */

            $action = "Submitted service request: " . $request_number;

            $table_name = "service_requests";

            $ip_address = $_SERVER["REMOTE_ADDR"] ?? "";

            $stmt = $conn->prepare("
                INSERT INTO audit_logs
                (
                    user_id,
                    action,
                    table_name,
                    record_id,
                    ip_address
                )
                VALUES (?, ?, ?, ?, ?)
            ");

            $stmt->bind_param(
                "issis",
                $_SESSION["user_id"],
                $action,
                $table_name,
                $request_id,
                $ip_address
            );

            $stmt->execute();

            $stmt->close();


            /* Complete transaction */

            $conn->commit();


            header(
                "Location: ../citizen/requests.php?success=1"
            );

            exit;
        } catch (Exception $e) {

            $conn->rollback();

            $error = "Unable to submit request. Please try again.";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>N-GATE - Request Service</title>

    <link rel="stylesheet" href="../assets/css/style.css">

</head>

<body>

    <nav class="navbar">

        <div class="logo">
            N-GATE
        </div>

        <div class="nav-links">

            <a href="../index.php">Home</a>

            <a href="../citizen/dashboard.php">Dashboard</a>

            <a href="../citizen/services.php">Services</a>

            <a href="../citizen/requests.php">My Requests</a>

            <a href="../auth/logout.php">Logout</a>

        </div>

    </nav>


    <section class="section">

        <div class="card">

            <h1>
                Request Government Service
            </h1>

            <hr>

            <h2>
                <?php echo htmlspecialchars($service["service_name"]); ?>
            </h2>

            <p>
                <?php echo htmlspecialchars($service["description"]); ?>
            </p>

            <p>

                <strong>Government Agency:</strong>

                <?php echo htmlspecialchars($service["agency_name"]); ?>

            </p>


            <?php if ($error): ?>

                <p style="color:red;">

                    <?php echo htmlspecialchars($error); ?>

                </p>

            <?php endif; ?>


            <form method="POST">

                <label>
                    Additional Information
                </label>

                <br>

                <textarea
                    name="description"
                    rows="5"
                    style="width:100%; margin-top:10px;"
                    placeholder="Enter any additional information..."></textarea>

                <br><br>


                <label>

                    <input
                        type="checkbox"
                        name="consent"
                        required>

                    I provide consent for N-GATE to securely process
                    and exchange the necessary information for this
                    service request.

                </label>

                <br><br>


                <button
                    type="submit"
                    class="btn">
                    Submit Service Request
                </button>

            </form>

        </div>

    </section>


    <footer class="footer">

        <p>
            N-GATE — Academic E-Governance Project | B.Sc. CSIT
        </p>

    </footer>

</body>

</html>