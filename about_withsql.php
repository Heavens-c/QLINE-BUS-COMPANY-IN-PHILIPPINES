<?php
if (session_status() === PHP_SESSION_NONE) session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - Dimple Star Transport</title>
    <link rel="stylesheet" type="text/css" href="style/modern-style.css" />
    <link rel="icon" href="images/icon.ico" type="image/x-icon">
    <script src="js/modern.js" defer></script>
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="container">
            <div class="header-content">
                <a href="index.php">
                    <img src="images/logo.png" class="logo" alt="Dimple Star Transport" />
                </a>

                <nav class="nav">
                    <ul class="nav-list">
                        <li><a href="index.php" class="nav-link">Home</a></li>
                        <li><a href="about.php" class="nav-link active">About Us</a></li>
                        <li><a href="terminal.php" class="nav-link">Terminals</a></li>
                        <li><a href="routeschedule.php" class="nav-link">Routes & Schedules</a></li>
                        <li><a href="contact.php" class="nav-link">Contact</a></li>
                        <li><a href="book.php" class="nav-link">Book Now</a></li>
                    </ul>

                    <div class="user-menu">
                        <?php if (!empty($_SESSION['email'])): ?>
                            <span class="user-welcome">Welcome, <?= htmlspecialchars($_SESSION['email']) ?>!</span>
                            <a href="logout.php" class="btn btn-secondary">Logout</a>
                        <?php else: ?>
                            <a href="signlog.php" class="btn btn-primary">Sign Up / Login</a>
                        <?php endif; ?>
                    </div>
                </nav>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="main-content">
        <div class="container">

            <!-- The ENTIRE About page below is rendered from the database -->
            <?php include __DIR__ . '/php_includes/get_about_page.php'; ?>

            <!-- Current Date/Time -->
            <div class="text-right" style="margin-top:2rem;">
                <p class="text-secondary"><?php include_once __DIR__ . "/php_includes/date_time.php"; ?></p>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h3>Dimple Star Transport</h3>
                    <p>Your trusted partner in transportation since 2004. We provide reliable, safe, and comfortable bus services across Metro Manila and Mindoro Province.</p>
                </div>

                <div class="footer-section">
                    <h3>Quick Links</h3>
                    <p>
                        <a href="about.php" style="color: #9ca3af; text-decoration: none;">About Us</a><br>
                        <a href="routeschedule.php" style="color: #9ca3af; text-decoration: none;">Routes & Schedules</a><br>
                        <a href="contact.php" style="color: #9ca3af; text-decoration: none;">Contact</a><br>
                        <a href="book.php" style="color: #9ca3af; text-decoration: none;">Book Now</a>
                    </p>
                </div>

                <div class="footer-section">
                    <h3>Contact Info</h3>
                    <p>
                        Phone: 0929 209 0712<br>
                        Address: Block 1 lot 10, Southpoint Subd.<br>
                        Brgy Banay-Banay, Cabuyao, Laguna
                    </p>
                </div>
            </div>

            <div class="footer-bottom">
                <p>&copy; 2024 Dimple Star Transport. All rights reserved.</p>
            </div>
        </div>
    </footer>
</body>
</html>
