<?php

session_start();

require_once "../config/database.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

if ($_SESSION["user_role"] !== "agency") {
    die("Access denied.");
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>N-GATE - Agency Dashboard</title>

    <link rel="stylesheet" href="../assets/css/style.css">

</head>

<body>

    <nav class="navbar">

        <div class="logo">
            N-GATE Agency
        </div>

        <div class="nav-links">

            <a href="../index.php">Home</a>

            <a href="dashboard.php">Dashboard</a>

            <a href="requests.php">Service Requests</a>

            <a href="../auth/logout.php">Logout</a>

        </div>

    </nav>


    <section class="hero">

        <h1>
            Government Agency Dashboard
        </h1>

        <p>

            Welcome,
            <?php echo htmlspecialchars($_SESSION["user_name"]); ?>

        </p>

        <p>
            Manage and process citizen service requests
            through the N-GATE platform.
        </p>

        <div class="buttons">

            <a
                href="requests.php"
                class="btn">
                View Service Requests
            </a>

        </div>

    </section>


    <section class="section">

        <div class="cards">

            <div class="card">

                <h2>Service Requests</h2>

                <p>
                    View requests submitted by citizens.
                </p>

                <a
                    href="requests.php"
                    class="btn">
                    View Requests
                </a>

            </div>


            <div class="card">

                <h2>Digital Processing</h2>

                <p>
                    Process citizen requests and update
                    their service status.
                </p>

            </div>


            <div class="card">

                <h2>Audit Trail</h2>

                <p>
                    Government actions are recorded for
                    accountability and transparency.
                </p>

            </div>

        </div>

    </section>


    <footer class="footer">

        <p>
            N-GATE — Academic E-Governance Project | B.Sc. CSIT
        </p>

    </footer>

</body>

</html>