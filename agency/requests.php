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


/* Get all service requests */

$sql = "
    SELECT
        service_requests.id,
        service_requests.request_number,
        service_requests.status,
        service_requests.created_at,
        users.full_name AS citizen_name,
        government_services.service_name,
        government_agencies.agency_name

    FROM service_requests

    INNER JOIN users
        ON service_requests.citizen_id = users.id

    INNER JOIN government_services
        ON service_requests.service_id =
           government_services.id

    INNER JOIN government_agencies
        ON government_services.agency_id =
           government_agencies.id

    ORDER BY service_requests.id DESC
";

$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>N-GATE - Agency Requests</title>

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

            <a href="requests.php">Requests</a>

            <a href="../auth/logout.php">Logout</a>

        </div>

    </nav>


    <section class="section">

        <h1>
            Citizen Service Requests
        </h1>

        <p>
            Authorized government agencies can review and
            process submitted service requests.
        </p>


        <?php if ($result && $result->num_rows > 0): ?>

            <div style="overflow-x:auto;">

                <table
                    border="1"
                    cellpadding="10"
                    cellspacing="0"
                    width="100%">

                    <thead>

                        <tr>

                            <th>Request Number</th>

                            <th>Citizen</th>

                            <th>Service</th>

                            <th>Agency</th>

                            <th>Status</th>

                            <th>Date</th>

                            <th>Action</th>

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
                                        $request["citizen_name"]
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
                                        $request["created_at"]
                                    );
                                    ?>
                                </td>

                                <td>

                                    <a
                                        class="btn"
                                        href="request-details.php?id=<?php echo $request["id"]; ?>">
                                        View
                                    </a>

                                </td>

                            </tr>

                        <?php endwhile; ?>

                    </tbody>

                </table>

            </div>

        <?php else: ?>

            <div class="card">

                <h2>No Requests Found</h2>

                <p>
                    There are currently no citizen service requests.
                </p>

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