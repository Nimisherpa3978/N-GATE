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

$success = isset($_GET["success"]);


/* Get citizen requests */

$stmt = $conn->prepare("
    SELECT
        service_requests.id,
        service_requests.request_number,
        service_requests.description,
        service_requests.status,
        service_requests.created_at,
        government_services.service_name,
        government_agencies.agency_name,
        consents.consent_status
    FROM service_requests

    INNER JOIN government_services
        ON service_requests.service_id =
           government_services.id

    INNER JOIN government_agencies
        ON government_services.agency_id =
           government_agencies.id

    LEFT JOIN consents
        ON service_requests.id =
           consents.request_id

    WHERE service_requests.citizen_id = ?

    ORDER BY service_requests.id DESC
");

$stmt->bind_param(
    "i",
    $_SESSION["user_id"]
);

$stmt->execute();

$result = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>N-GATE - My Requests</title>

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

        <h1>
            My Service Requests
        </h1>


        <?php if ($success): ?>

            <p style="color:green;">

                Service request submitted successfully.

            </p>

        <?php endif; ?>


        <?php if ($result->num_rows > 0): ?>

            <div style="overflow-x:auto;">

                <table
                    border="1"
                    cellpadding="10"
                    cellspacing="0"
                    width="100%">

                    <thead>

                        <tr>

                            <th>Request Number</th>

                            <th>Service</th>

                            <th>Agency</th>

                            <th>Status</th>

                            <th>Consent</th>

                            <th>Date</th>

                        </tr>

                    </thead>


                    <tbody>

                        <?php while ($request = $result->fetch_assoc()): ?>

                            <tr>

                                <td>
                                    <?php
                                    echo htmlspecialchars(
                                        $request["request_number"]
                                    );
                                    ?>
                                </td>

                                <td>
                                    <?php
                                    echo htmlspecialchars(
                                        $request["service_name"]
                                    );
                                    ?>
                                </td>

                                <td>
                                    <?php
                                    echo htmlspecialchars(
                                        $request["agency_name"]
                                    );
                                    ?>
                                </td>

                                <td>
                                    <?php
                                    echo htmlspecialchars(
                                        $request["status"]
                                    );
                                    ?>
                                </td>

                                <td>
                                    <?php
                                    echo htmlspecialchars(
                                        $request["consent_status"]
                                    );
                                    ?>
                                </td>

                                <td>
                                    <?php
                                    echo htmlspecialchars(
                                        $request["created_at"]
                                    );
                                    ?>
                                </td>

                            </tr>

                        <?php endwhile; ?>

                    </tbody>

                </table>

            </div>

        <?php else: ?>

            <div class="card">

                <h2>
                    No Requests Yet
                </h2>

                <p>
                    You have not submitted any government service
                    requests yet.
                </p>

                <a
                    class="btn"
                    href="services.php">
                    Explore Services
                </a>

            </div>

        <?php endif; ?>

    </section>


    <footer class="footer">

        <p>
            N-GATE — Academic E-Governance Project | B.Sc. CSIT
        </p>

    </footer>

</body>

</html>