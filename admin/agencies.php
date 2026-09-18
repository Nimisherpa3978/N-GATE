
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
| Fetch Government Agencies
|--------------------------------------------------------------------------
*/

$sql = "
    SELECT
        government_agencies.id,
        government_agencies.agency_name
    FROM government_agencies
    ORDER BY government_agencies.id ASC
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

    <title>Government Agencies | N-GATE</title>

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
                <a href="agencies.php">Agencies</a>
            </li>

            <li>
                <a href="../auth/logout.php">Logout</a>
            </li>

        </ul>

    </nav>


    <!-- MAIN CONTENT -->

    <section class="section">

        <div class="section-title">

            <h2>Government Agencies</h2>

            <p>
                View government agencies connected to the N-GATE platform.
            </p>

        </div>


        <div class="table-container">

            <table>

                <thead>

                    <tr>

                        <th>ID</th>

                        <th>Government Agency</th>

                    </tr>

                </thead>

                <tbody>

                    <?php if ($result->num_rows > 0): ?>

                        <?php while ($agency = $result->fetch_assoc()): ?>

                            <tr>

                                <td>
                                    <?php echo (int)$agency["id"]; ?>
                                </td>

                                <td>
                                    <?php echo htmlspecialchars(
                                        $agency["agency_name"]
                                    ); ?>
                                </td>

                            </tr>

                        <?php endwhile; ?>

                    <?php else: ?>

                        <tr>

                            <td colspan="2">
                                No government agencies found.
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
