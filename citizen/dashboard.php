
<?php

session_start();

require_once "../config/database.php";

/* Check login */

if (!isset($_SESSION["user_id"])) {

    header("Location: ../auth/login.php");

    exit;
}

/* Check citizen role */

if ($_SESSION["user_role"] !== "citizen") {

    header("Location: ../index.php");

    exit;
}

$user_name = $_SESSION["user_name"];

$user_id = $_SESSION["user_id"];


/* ================================
   Get Request Statistics
================================ */

$total_requests = 0;
$pending_requests = 0;
$processing_requests = 0;
$completed_requests = 0;
$rejected_requests = 0;


/* Total Requests */

$stmt = $conn->prepare("
    SELECT COUNT(*) AS total
    FROM service_requests
    WHERE citizen_id = ?
");

$stmt->bind_param(
    "i",
    $user_id
);

$stmt->execute();

$result = $stmt->get_result();

$row = $result->fetch_assoc();

$total_requests = $row["total"];


/* Pending Requests */

$stmt = $conn->prepare("
    SELECT COUNT(*) AS total
    FROM service_requests
    WHERE citizen_id = ?
    AND status = 'Pending'
");

$stmt->bind_param(
    "i",
    $user_id
);

$stmt->execute();

$result = $stmt->get_result();

$row = $result->fetch_assoc();

$pending_requests = $row["total"];


/* Processing Requests */

$stmt = $conn->prepare("
    SELECT COUNT(*) AS total
    FROM service_requests
    WHERE citizen_id = ?
    AND status = 'Processing'
");

$stmt->bind_param(
    "i",
    $user_id
);

$stmt->execute();

$result = $stmt->get_result();

$row = $result->fetch_assoc();

$processing_requests = $row["total"];


/* Completed Requests */

$stmt = $conn->prepare("
    SELECT COUNT(*) AS total
    FROM service_requests
    WHERE citizen_id = ?
    AND status = 'Completed'
");

$stmt->bind_param(
    "i",
    $user_id
);

$stmt->execute();

$result = $stmt->get_result();

$row = $result->fetch_assoc();

$completed_requests = $row["total"];


/* Rejected Requests */

$stmt = $conn->prepare("
    SELECT COUNT(*) AS total
    FROM service_requests
    WHERE citizen_id = ?
    AND status = 'Rejected'
");

$stmt->bind_param(
    "i",
    $user_id
);

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

    <title>N-GATE | Citizen Dashboard</title>

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
                Citizen Portal
            </span>

        </div>


        <ul class="nav-links">

            <li>
                <a href="../index.php">
                    Home
                </a>
            </li>

            <li>
                <a href="services.php">
                    Services
                </a>
            </li>

            <li>
                <a href="requests.php">
                    My Requests
                </a>
            </li>

            <li>
                <a href="../auth/logout.php">
                    Logout
                </a>
            </li>

        </ul>

    </nav>


    <!-- Hero Section -->

    <section class="hero">

        <div class="hero-content">

            <h1>
                Welcome,
                <?php echo htmlspecialchars($user_name); ?>
            </h1>

            <h2>
                Citizen Dashboard
            </h2>

            <p>
                Access government services, submit requests,
                manage consent and track your applications
                through N-GATE.
            </p>


            <div class="buttons">

                <a
                    href="services.php"
                    class="btn btn-primary">

                    Explore Services

                </a>


                <a
                    href="requests.php"
                    class="btn btn-secondary">

                    My Requests

                </a>

            </div>

        </div>

    </section>


    <!-- Request Statistics -->

    <section class="section">

        <div class="section-title">

            <h2>
                Request Statistics
            </h2>

            <p>
                Overview of your government service requests.
            </p>

        </div>


        <div class="cards">


            <!-- Total -->

            <div class="card">

                <h2>
                    <?php echo $total_requests; ?>
                </h2>

                <h3>
                    Total Requests
                </h3>

                <p>
                    Total government service requests
                    submitted by you.
                </p>

            </div>


            <!-- Pending -->

            <div class="card">

                <h2>
                    <?php echo $pending_requests; ?>
                </h2>

                <h3>
                    Pending
                </h3>

                <p>
                    Requests waiting for agency processing.
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
                    Requests currently being processed
                    by government agencies.
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
                    Successfully completed government
                    service requests.
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
                    Requests that were rejected by
                    the responsible agency.
                </p>

            </div>


        </div>

    </section>


    <!-- Citizen Services -->

    <section class="section">

        <div class="section-title">

            <h2>
                Citizen Services
            </h2>

            <p>
                Manage your government service activities.
            </p>

        </div>


        <div class="cards">


            <!-- Government Services -->

            <div class="card">

                <h2>
                    Government Services
                </h2>

                <p>
                    Browse available digital government
                    services and submit service requests.
                </p>

                <a
                    class="btn"
                    href="services.php">

                    View Services

                </a>

            </div>


            <!-- Request Tracking -->

            <div class="card">

                <h2>
                    Request Tracking
                </h2>

                <p>
                    Track the status and history of your
                    submitted government service requests.
                </p>

                <a
                    class="btn"
                    href="requests.php">

                    My Requests

                </a>

            </div>


            <!-- Consent Management -->

            <div class="card">

                <h3>
                    🔐 Consent Management
                </h3>

                <p>
                    Review and manage consent related to
                    authorized information exchange.
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
```