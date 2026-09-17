<?php

session_start();

require_once "../config/database.php";

$message = "";

if (isset($_GET["registered"])) {

    $message = "Registration successful. Please login.";
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    $stmt = $conn->prepare(
        "SELECT id, full_name, email, password, role
         FROM users
         WHERE email = ?"
    );

    $stmt->bind_param("s", $email);

    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows === 1) {

        $user = $result->fetch_assoc();

        if (password_verify($password, $user["password"])) {

            $_SESSION["user_id"] = $user["id"];
            $_SESSION["user_name"] = $user["full_name"];
            $_SESSION["user_email"] = $user["email"];
            $_SESSION["user_role"] = $user["role"];

            if ($user["role"] === "citizen") {

                header("Location: ../citizen/dashboard.php");
                exit;
            }

            if ($user["role"] === "agency") {

                header("Location: ../agency/dashboard.php");
                exit;
            }

            if ($user["role"] === "admin") {

                header("Location: ../admin/dashboard.php");
                exit;
            }
        } else {

            $message = "Invalid email or password.";
        }
    } else {

        $message = "Invalid email or password.";
    }
}

?>

<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>N-GATE | Login</title>

    <link rel="stylesheet"
        href="../assets/css/style.css">

    <style>
        .auth-container {
            max-width: 500px;
            margin: 60px auto;
            background: white;
            padding: 35px;
            border-radius: 10px;
            box-shadow: 0 3px 15px rgba(0, 0, 0, 0.08);
        }

        .auth-container h1 {
            color: #123c69;
            margin-bottom: 10px;
        }

        .auth-container p {
            color: #6b7280;
            margin-bottom: 25px;
        }

        .form-group {
            margin-bottom: 18px;
        }

        .form-group label {
            display: block;
            margin-bottom: 7px;
            font-weight: bold;
        }

        .form-group input {
            width: 100%;
            padding: 12px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
        }

        .form-button {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 6px;
            background: #123c69;
            color: white;
            font-weight: bold;
            cursor: pointer;
        }

        .message {
            margin-bottom: 20px;
            padding: 10px;
            background: #dbeafe;
            border-radius: 6px;
        }

        .auth-link {
            margin-top: 20px;
            text-align: center;
        }

        .auth-link a {
            color: #123c69;
            font-weight: bold;
        }
    </style>

</head>

<body>

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
                <a href="register.php">Register</a>
            </li>

        </ul>

    </nav>


    <div class="auth-container">

        <h1>Citizen Login</h1>

        <p>
            Login to access N-GATE government services.
        </p>


        <?php if (!empty($message)): ?>

            <div class="message">

                <?php echo htmlspecialchars($message); ?>

            </div>

        <?php endif; ?>


        <form method="POST">

            <div class="form-group">

                <label>Email Address</label>

                <input
                    type="email"
                    name="email"
                    required>

            </div>


            <div class="form-group">

                <label>Password</label>

                <input
                    type="password"
                    name="password"
                    required>

            </div>


            <button
                type="submit"
                class="form-button">
                Login
            </button>

        </form>


        <div class="auth-link">

            Don't have an account?

            <a href="register.php">
                Register
            </a>

        </div>

    </div>


    <footer class="footer">

        <p>
            N-GATE — Academic E-Governance Project
        </p>

    </footer>

</body>

</html>