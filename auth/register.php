<?php

session_start();

require_once "../config/database.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $full_name = trim($_POST["full_name"]);
    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    if (empty($full_name) || empty($email) || empty($password)) {

        $message = "Please fill in all fields.";
    } else {

        $check = $conn->prepare(
            "SELECT id FROM users WHERE email = ?"
        );

        $check->bind_param("s", $email);
        $check->execute();

        $result = $check->get_result();

        if ($result->num_rows > 0) {

            $message = "An account with this email already exists.";
        } else {

            $hashed_password = password_hash(
                $password,
                PASSWORD_DEFAULT
            );

            $role = "citizen";

            $stmt = $conn->prepare(
                "INSERT INTO users
                (full_name, email, password, role)
                VALUES (?, ?, ?, ?)"
            );

            $stmt->bind_param(
                "ssss",
                $full_name,
                $email,
                $hashed_password,
                $role
            );

            if ($stmt->execute()) {

                header("Location: login.php?registered=1");
                exit;
            } else {

                $message = "Registration failed. Please try again.";
            }
        }
    }
}

?>

<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>N-GATE | Citizen Registration</title>

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
            background: #fef3c7;
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
                <a href="login.php">Login</a>
            </li>

        </ul>

    </nav>


    <div class="auth-container">

        <h1>Citizen Registration</h1>

        <p>
            Create an N-GATE citizen account to access
            government services.
        </p>


        <?php if (!empty($message)): ?>

            <div class="message">

                <?php echo htmlspecialchars($message); ?>

            </div>

        <?php endif; ?>


        <form method="POST">

            <div class="form-group">

                <label>Full Name</label>

                <input
                    type="text"
                    name="full_name"
                    required>

            </div>


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
                Create Citizen Account
            </button>

        </form>


        <div class="auth-link">

            Already have an account?

            <a href="login.php">
                Login
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