<?php

session_start();

require_once "../config/database.php";

if (isset($_SESSION["user_id"])) {
    if ($_SESSION["user_role"] === "agency") {
        header("Location: dashboard.php");
        exit;
    }
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";

    if ($email === "" || $password === "") {

        $error = "Please enter email and password.";
    } else {

        $stmt = $conn->prepare("
            SELECT id, full_name, email, password, role
            FROM users
            WHERE email = ?
            LIMIT 1
        ");

        $stmt->bind_param("s", $email);

        $stmt->execute();

        $result = $stmt->get_result();

        $user = $result->fetch_assoc();

        $stmt->close();

        if (
            $user &&
            password_verify($password, $user["password"]) &&
            $user["role"] === "agency"
        ) {

            $_SESSION["user_id"] = $user["id"];
            $_SESSION["user_name"] = $user["full_name"];
            $_SESSION["user_email"] = $user["email"];
            $_SESSION["user_role"] = $user["role"];

            header("Location: dashboard.php");
            exit;
        } else {

            $error = "Invalid agency credentials.";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>N-GATE - Agency Login</title>

    <link rel="stylesheet" href="../assets/css/style.css">

</head>

<body>

    <nav class="navbar">

        <div class="logo">
            N-GATE
        </div>

        <div class="nav-links">

            <a href="../index.php">Home</a>

            <a href="../auth/login.php">Citizen Login</a>

        </div>

    </nav>


    <section class="section">

        <div class="card">

            <h1>Government Agency Login</h1>

            <p>
                Authorized agency personnel can access and process
                government service requests.
            </p>

            <?php if ($error): ?>

                <p style="color:red;">
                    <?php echo htmlspecialchars($error); ?>
                </p>

            <?php endif; ?>


            <form method="POST">

                <label>Email</label>

                <br>

                <input
                    type="email"
                    name="email"
                    required
                    style="width:100%; padding:10px; margin:8px 0 15px;">

                <label>Password</label>

                <br>

                <input
                    type="password"
                    name="password"
                    required
                    style="width:100%; padding:10px; margin:8px 0 15px;">

                <button
                    type="submit"
                    class="btn">
                    Agency Login
                </button>

            </form>

        </div>

    </section>


    <footer class="footer">

        <p>
            N-GATE — Academic E-Governance Project | B.Sc. CSIT
        </p>

    </footer>

</body>

</html>