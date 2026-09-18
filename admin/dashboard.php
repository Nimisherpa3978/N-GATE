<?php

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
   Check Admin Role
============================== */

if ($_SESSION["user_role"] !== "admin") {

    header("Location: ../index.php");

    exit;
}


$admin_name = $_SESSION["user_name"];


/* ==============================
   Total Citizens
============================== */

$stmt = $conn->prepare("
    SELECT COUNT(*) AS total
    FROM users
    WHERE role = 'citizen'
");

$stmt->execute();

$result = $stmt->get_result();

$row = $result->fetch_assoc();

$total_citizens = $row["total"];


/* ==============================
   Total Agencies
============================== */

$stmt = $conn->prepare("
    SELECT COUNT(*) AS total
    FROM government_agencies
");

$stmt->execute();

$result = $stmt->get_result();

$row = $result->fetch_assoc();

$total_agencies = $row["total"];


/* ==============================
   Total Services
============================== */

$stmt = $conn->prepare("
    SELECT COUNT(*) AS total
    FROM government_services
");

$stmt->execute();

$result = $stmt->get_result();

$row = $result->fetch_assoc();

$total_services = $row["total"];


/* ==============================
   Total Requests
============================== */

$stmt = $conn->prepare("
    SELECT COUNT(*) AS total
    FROM service_requests
");

$stmt->execute();

$result = $stmt->get_result();

$row = $result->fetch_assoc();

$total_requests = $row["total"];


/* ==============================
   Pending Requests
============================== */

$stmt = $conn->prepare("
    SELECT COUNT(*) AS total
    FROM service_requests
    WHERE status = 'Pending'
");

$stmt->execute();

$result = $stmt->get_result();

$row = $result->fetch_assoc();

$pending_requests = $row["total"];


/* ==============================
   Processing Requests
============================== */

$stmt = $conn->prepare("
    SELECT COUNT(*) AS total
    FROM service_requests
    WHERE status = 'Processing'
");

$stmt->execute();

$result = $stmt->get_result();

$row = $result->fetch_assoc();

$processing_requests = $row["total"];


/* ==============================
   Completed Requests
============================== */

$stmt = $conn->prepare("
    SELECT COUNT(*) AS total
    FROM service_requests
    WHERE status = 'Completed'
");

$stmt->execute();

$result = $stmt->get_result();

$row = $result->fetch_assoc();

$completed_requests = $row["total"];


/* ==============================
   Rejected Requests
============================== */

$stmt = $conn->prepare("
    SELECT COUNT(*) AS total
    FROM service_requests
    WHERE status = 'Rejected'
");

$stmt->execute();

$result = $stmt->get_result();

$row = $result->fetch_assoc();

$rejected_requests = $row["total"];

?>

<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>
        N-GATE | Admin Dashboard
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

            <span>
                Admin Portal
            </span>

        </div>


        <ul class="nav-links">

            <li>

                <a href="../index.php">
                    Home
                </a>

            </li>


            <li>

                <a href="dashboard.php">
                    Dashboard
                </a>

            </li>

            <li>
                <a href="services.php">Services</a>
            </li>

            <li>
                <a href="agencies.php">Agencies</a>
            </li>

            <li>

                <a href="../auth/logout.php">
                    Logout
                </a>

            </li>

        </ul>

    </nav>


    <!-- Hero -->

    <section class="hero">

        <div class="hero-content">

            <h1>

                Welcome,
                <?php echo htmlspecialchars($admin_name); ?>

            </h1>


            <h2>
                Administrator Dashboard
            </h2>


            <p>

                Monitor citizens, government agencies,
                services and service requests through
                the N-GATE administration portal.

            </p>

        </div>

    </section>


    <!-- System Statistics -->

    <section class="section">

        <div class="section-title">

            <h2>
                System Overview
            </h2>

            <p>
                Current statistics from the N-GATE system.
            </p>

        </div>


        <div class="cards">


            <!-- Citizens -->

            <div class="card">

                <h2>
                    <?php echo $total_citizens; ?>
                </h2>

                <h3>
                    Citizens
                </h3>

                <p>
                    Registered citizen accounts.
                </p>

            </div>


            <!-- Agencies -->

            <div class="card">

                <h2>
                    <?php echo $total_agencies; ?>
                </h2>

                <h3>
                    Agencies
                </h3>

                <p>
                    Government agencies connected
                    to N-GATE.
                </p>

            </div>


            <!-- Services -->

            <div class="card">

                <h2>
                    <?php echo $total_services; ?>
                </h2>

                <h3>
                    Services
                </h3>

                <p>
                    Digital government services
                    available to citizens.
                </p>

            </div>


            <!-- Requests -->

            <div class="card">

                <h2>
                    <?php echo $total_requests; ?>
                </h2>

                <h3>
                    Total Requests
                </h3>

                <p>
                    Service requests submitted
                    through N-GATE.
                </p>

            </div>

        </div>

    </section>


    <!-- Request Status -->

    <section class="section">

        <div class="section-title">

            <h2>
                Request Status Overview
            </h2>

            <p>
                Current processing status of government
                service requests.
            </p>

        </div>


        <div class="cards">


            <!-- Pending -->

            <div class="card">

                <h2>
                    <?php echo $pending_requests; ?>
                </h2>

                <h3>
                    Pending
                </h3>

                <p>
                    Requests awaiting processing.
                </p>

            </div>


            <!-- Processing -->

            <div class="card">

                <h2>
                    <?php echo $processing_requests; ?>
                </h2>

                <h3>
                    Processing
                </h3>

                <p>
                    Requests currently being processed.
                </p>

            </div>


            <!-- Completed -->

            <div class="card">

                <h2>
                    <?php echo $completed_requests; ?>
                </h2>

                <h3>
                    Completed
                </h3>

                <p>
                    Successfully completed requests.
                </p>

            </div>


            <!-- Rejected -->

            <div class="card">

                <h2>
                    <?php echo $rejected_requests; ?>
                </h2>

                <h3>
                    Rejected
                </h3>

                <p>
                    Requests rejected by agencies.
                </p>

            </div>


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