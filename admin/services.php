
<?php

error_reporting(E_ALL);
ini_set("display_errors", 1);

session_start();

require_once "../config/database.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: ../auth/login.php");
    exit;
}

if ($_SESSION["user_role"] !== "admin") {
    die("Access denied.");
}

/*
|--------------------------------------------------------------------------
| Fetch Government Services
|--------------------------------------------------------------------------
*/

$sql = "
    SELECT
        government_services.id,
        government_services.service_name,
        government_agencies.agency_name
    FROM government_services
    LEFT JOIN government_agencies
        ON government_services.agency_id = government_agencies.id
    ORDER BY government_services.id ASC
";

$result = $conn->query($sql);

if (!$result) {
    die("Database query failed: " . $conn->error);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Manage Services | N-GATE</title>

    <link rel="stylesheet" href="../assets/css/style.css">

</head>

<body>

    <!-- NAVIGATION -->

    <nav class="navbar">

        <div class="logo">

            N-GATE

            <span>
                Nepal Government Access & Trusted Exchange
            </span>

        </div>

        <ul class="nav-links">

            <li>
                <a href="../index.php">Home</a>
            </li>

            <li>
                <a href="dashboard.php">Dashboard</a>
            </li>

            <li>
                <a href="users.php">Users</a>
            </li>

            <li>
                <a href="services.php">Services</a>
            </li>

            <li>
                <a href="../auth/logout.php">Logout</a>
            </li>

        </ul>

    </nav>


    <!-- MAIN CONTENT -->

    <section class="section">

        <div class="section-title">

            <h2>Government Services</h2>

            <p>
                Manage government services available through N-GATE.
            </p>

        </div>


        <div class="table-container">

            <table>

                <thead>

                    <tr>

                        <th>ID</th>

                        <th>Service Name</th>

                        <th>Government Agency</th>

                    </tr>

                </thead>

                <tbody>

                    <?php if ($result->num_rows > 0): ?>

                        <?php while ($service = $result->fetch_assoc()): ?>

                            <tr>

                                <td>
                                    <?php echo (int)$service["id"]; ?>
                                </td>

                                <td>
                                    <?php echo htmlspecialchars($service["service_name"]); ?>
                                </td>

                                <td>
                                    <?php echo htmlspecialchars(
                                        $service["agency_name"] ?? "Not Assigned"
                                    ); ?>
                                </td>

                            </tr>

                        <?php endwhile; ?>

                    <?php else: ?>

                        <tr>

                            <td colspan="3">
                                No government services found.
                            </td>

                        </tr>

                    <?php endif; ?>

                </tbody>

            </table>

        </div>

    </section>


    <!-- FOOTER -->

    <footer class="footer">

        <p>
            N-GATE — Nepal Government Access & Trusted Exchange
        </p>

        <p>
            Academic E-Governance Project | B.Sc. CSIT
        </p>

    </footer>

</body>

</html>
