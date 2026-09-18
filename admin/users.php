
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


/* ==============================
   Get All Users
============================== */

$sql = "
    SELECT
        id,
        full_name,
        email,
        role
    FROM users
    ORDER BY id DESC
";

$result = $conn->query($sql);

?>

<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>
        N-GATE | Manage Users
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

                <a href="users.php">
                    Users
                </a>

            </li>


            <li>

                <a href="../auth/logout.php">
                    Logout
                </a>

            </li>

        </ul>

    </nav>


    <!-- Main Section -->

    <section class="section">

        <div class="section-title">

            <h1>
                Manage Users
            </h1>

            <p>
                View registered citizens, agencies and
                administrators in the N-GATE system.
            </p>

        </div>


        <?php if ($result && $result->num_rows > 0): ?>


            <div style="overflow-x:auto;">

                <table
                    border="1"
                    cellpadding="10"
                    cellspacing="0"
                    width="100%">

                    <thead>

                        <tr>

                            <th>
                                ID
                            </th>

                            <th>
                                Full Name
                            </th>

                            <th>
                                Email
                            </th>

                            <th>
                                Role
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        <?php while ($user = $result->fetch_assoc()): ?>

                            <tr>

                                <td>

                                    <?php

                                    echo (int)$user["id"];

                                    ?>

                                </td>


                                <td>

                                    <?php

                                    echo htmlspecialchars(
                                        $user["full_name"]
                                    );

                                    ?>

                                </td>


                                <td>

                                    <?php

                                    echo htmlspecialchars(
                                        $user["email"]
                                    );

                                    ?>

                                </td>


                                <td>

                                    <?php

                                    echo htmlspecialchars(
                                        $user["role"]
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
                    No Users Found
                </h2>

                <p>
                    There are currently no registered users
                    in the N-GATE system.
                </p>

            </div>


        <?php endif; ?>


        <!-- Back to Dashboard -->

        <div class="card">

            <a
                class="btn"
                href="dashboard.php">

                Back to Admin Dashboard

            </a>

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
