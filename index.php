<?php
require_once "config/database.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>N-GATE | Nepal Government Access & Trusted Exchange</title>

    <link rel="stylesheet" href="assets/css/style.css">

</head>

<body>

    <!-- NAVIGATION -->

    <nav class="navbar">

        <div class="logo">
            N-GATE
            <span>Nepal Government Access & Trusted Exchange</span>
        </div>

        <ul class="nav-links">

            <li>
                <a href="index.php">Home</a>
            </li>

            <li>
                <a href="#services">Services</a>
            </li>

            <li>
                <a href="#about">About</a>
            </li>

            <li>
                <a href="login.php">Login</a>
            </li>

            <li>
                <a href="register.php">Register</a>
            </li>

        </ul>

    </nav>


    <!-- HERO -->

    <section class="hero">

        <div class="hero-content">

            <h1>
                Nepal Government Access & Trusted Exchange
            </h1>

            <h2>
                Connecting Government Services Through Secure Digital Exchange
            </h2>

            <p>
                N-GATE is an academic e-governance prototype
                demonstrating how authorized government agencies
                can securely exchange and verify necessary citizen
                information through a connected digital platform.
            </p>

            <div class="buttons">

                <a href="#services" class="btn btn-primary">
                    Explore Services
                </a>

                <a href="login.php" class="btn btn-secondary">
                    Citizen Login
                </a>

            </div>

        </div>

    </section>


    <!-- ABOUT -->

    <section class="section" id="about">

        <div class="section-title">

            <h2>How N-GATE Works</h2>

            <p>
                A digital bridge between citizens and authorized government agencies.
            </p>

        </div>


        <div class="cards">

            <div class="card">

                <h3>👤 Citizen Access</h3>

                <p>
                    Citizens can register, access available government
                    services and submit service requests through a
                    centralized digital platform.
                </p>

            </div>


            <div class="card">

                <h3>🏛️ Government Agencies</h3>

                <p>
                    Authorized agencies can receive and process service
                    requests and securely verify required information.
                </p>

            </div>


            <div class="card">

                <h3>🔐 Trusted Exchange</h3>

                <p>
                    N-GATE demonstrates controlled information exchange
                    using authentication, consent and audit logging.
                </p>

            </div>

        </div>

    </section>


    <!-- SERVICES -->

    <section class="section" id="services">

        <div class="section-title">

            <h2>Government Services</h2>

            <p>
                Example services available through the N-GATE prototype.
            </p>

        </div>


        <div class="cards">

            <div class="card">

                <h3>🪪 Identity Verification</h3>

                <p>
                    Request verification of citizen identity information
                    through an authorized government agency.
                </p>

            </div>


            <div class="card">

                <h3>🚗 Transport Services</h3>

                <p>
                    Demonstrate secure verification and information
                    exchange for transport-related government services.
                </p>

            </div>


            <div class="card">

                <h3>🏠 Property Services</h3>

                <p>
                    Demonstrate how authorized agencies could exchange
                    required property-related information.
                </p>

            </div>

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