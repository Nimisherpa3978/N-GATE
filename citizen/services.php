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

$sql = "
    SELECT 
        government_services.id,
        government_services.service_name,
        government_services.description,
        government_agencies.agency_name
    FROM government_services
    INNER JOIN government_agencies
        ON government_services.agency_id = government_agencies.id
    WHERE government_services.status = 'Active'
    ORDER BY government_services.id DESC
";

$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>N-GATE - Government Services</title>

    <link rel="stylesheet" href="../assets/css/style.css">

</head>

<body>

    <nav class="navbar">

        <div class="logo">
            N-GATE
        </div>

        <div class="nav-links">

            <a href="../index.php">Home</a>

            <a href="dashboard.php">Dashboard</a>

            <a href="services.php">Services</a>

            <a href="requests.php">My Requests</a>

            <a href="../auth/logout.php">Logout</a>

        </div>

    </nav>


    <section class="section">

        <h1>Government Services</h1>

        <p>
            Select a government service to submit a digital request.
        </p>


        <div class="cards">

            <?php if ($result && $result->num_rows > 0): ?>

                <?php while ($service = $result->fetch_assoc()): ?>

                    <div class="card">

                        <h2>
                            <?php echo htmlspecialchars($service["service_name"]); ?>
                        </h2>

                        <p>
                            <?php echo htmlspecialchars($service["description"]); ?>
                        </p>

                        <p>
                            <strong>Agency:</strong>
                            <?php echo htmlspecialchars($service["agency_name"]); ?>
                        </p>

                        <br>

                        <a
                            class="btn"
                            href="../services/request-service.php?service_id=<?php echo $service["id"]; ?>">
                            Request Service
                        </a>

                    </div>

                <?php endwhile; ?>

            <?php else: ?>

                <div class="card">

                    <h2>No Services Available</h2>

                    <p>
                        Government services are currently unavailable.
                    </p>

                </div>

            <?php endif; ?>

        </div>

    </section>


    <footer class="footer">

        <p>
            N-GATE — Academic E-Governance Project | B.Sc. CSIT
        </p>

    </footer>

</body>

</html>