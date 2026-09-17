<?php

session_start();

require_once "../config/database.php";

if (!isset($_SESSION["user_id"])) {

    header("Location: ../auth/login.php");

    exit;
}

if ($_SESSION["user_role"] !== "citizen") {

    header("Location: ../index.php");

    exit;
}

$user_name = $_SESSION["user_name"];

?>

<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>N-GATE | Citizen Dashboard</title>

    <link rel="stylesheet"
        href="../assets/css/style.css">

</head>

<body>

    <nav class="navbar">

        <div class="logo">

            N-GATE

            <span>
                Citizen Portal
            </span>

        </div>

        <ul class="nav-links">

            <li>
                <a href="../index.php">Home</a>
            </li>

            <li>
                <a href="services.php">Services</a>
            </li>

            <li>
                <a href="requests.php">My Requests</a>
            </li>

            <li>
                <a href="../auth/logout.php">Logout</a>
            </li>

        </ul>

    </nav>


    <section class="hero">

        <div class="hero-content">

            <h1>
                Welcome, <?php echo htmlspecialchars($user_name); ?>
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

            <div class="card">

                <h2>Government Services</h2>

                <p>
                    Browse available digital government services
                    and submit service requests.
                </p>

                <a class="btn" href="services.php">
                    View Services
                </a>

            </div>

            <div class="card">

                <h2>Request Tracking</h2>

                <p>
                    Track the status of your submitted government
                    service requests.
                </p>

                <a class="btn" href="requests.php">
                    My Requests
                </a>

            </div>

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


    <footer class="footer">

        <p>
            N-GATE — Nepal Government Access & Trusted Exchange
        </p>

    </footer>

</body>

</html>