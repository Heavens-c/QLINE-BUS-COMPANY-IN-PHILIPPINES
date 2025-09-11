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
                        <?php
                            session_start();
                            if(isset($_SESSION['email'])){
                                $email = $_SESSION['email'];
                                echo '<span class="user-welcome">Welcome, ' . htmlspecialchars($email) . '!</span>';
                                echo '<a href="logout.php" class="btn btn-secondary">Logout</a>';
                            } else {
                                echo '<a href="signlog.php" class="btn btn-primary">Sign Up / Login</a>';
                            }
                        ?>
                    </div>
                </nav>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="main-content">
        <div class="container">
            <!-- Page Header -->
            <section class="content-grid">
                <div class="text-center">
                    <h1 style="font-size: 2.5rem; font-weight: 700; color: var(--text-primary); margin-bottom: 1rem;">About Us</h1>
                    <p style="font-size: 1.125rem; color: var(--text-secondary); max-width: 600px; margin: 0 auto;">
                        Serving the Philippines with reliable transportation since 1993
                    </p>
                </div>
            </section>

            <!-- History Section -->
            <section class="content-grid">
                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title">Our History</h2>
                    </div>
                    <div class="card-body">
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; align-items: start;">
                            <div>
                                <img src="images/oldbus.jpg" alt="Historical Bus Photo" style="width: 100%; border-radius: var(--radius-md);">
                                <p style="margin-top: 1rem; font-size: 0.875rem; color: var(--text-secondary);">
                                    Photo taken on October 16, 1993. Napat Transit (now Dimple Star Transport) NVR-963<br>
                                    (fleet No 800) going to Alabang and jeepneys under the Light Rail Line in Taft Ave near<br>
                                    United Nations Avenue, Ermita, Manila, Philippines.
                                </p>
                            </div>
                            <div>
                                <h3 style="color: var(--primary-color); margin-bottom: 1rem;">A Legacy of Service</h3>
                                <p style="line-height: 1.8; margin-bottom: 1rem;">
                                    Founded in 1993 as Napat Transit, our company has been at the forefront of providing 
                                    reliable transportation services to the Filipino people. Over three decades of experience 
                                    have taught us the importance of safety, comfort, and punctuality.
                                </p>
                                <p style="line-height: 1.8; margin-bottom: 1rem;">
                                    In May 2004, we underwent a significant transformation and rebranded to 
                                    <strong>Dimple Star Transport</strong>, marking a new era of enhanced services 
                                    and expanded routes.
                                </p>
                                <p style="line-height: 1.8;">
                                    Today, we continue to serve as a trusted partner in transportation, connecting 
                                    communities across Metro Manila and Mindoro Province with our modern fleet and 
                                    experienced team.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Mission & Vision -->
            <section class="content-grid">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap: 2rem;">
                    <div class="card">
                        <div class="card-header" style="background: linear-gradient(135deg, var(--primary-color), var(--primary-dark)); color: white;">
                            <h3 class="card-title" style="color: white;">Our Mission</h3>
                        </div>
                        <div class="card-body">
                            <p style="font-size: 1.125rem; line-height: 1.7; color: var(--text-primary);">
                                To provide superior transport service to Metro Manila and Mindoro Province commuters, 
                                ensuring safe, comfortable, and reliable journeys for all our passengers.
                            </p>
                        </div>
                    </div>
                    
                    <div class="card">
                        <div class="card-header" style="background: linear-gradient(135deg, var(--secondary-color), #d97706); color: white;">
                            <h3 class="card-title" style="color: white;">Our Vision</h3>
                        </div>
                        <div class="card-body">
                            <p style="font-size: 1.125rem; line-height: 1.7; color: var(--text-primary);">
                                To lead the bus transport industry through innovative service delivery and commitment 
                                to excellence, setting new standards for passenger transportation in the Philippines.
                            </p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Values Section -->
            <section class="content-grid">
                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title">Our Core Values</h2>
                    </div>
                    <div class="card-body">
                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 2rem;">
                            <div style="text-align: center;">
                                <div style="width: 60px; height: 60px; background: var(--primary-color); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; color: white; font-size: 1.5rem; font-weight: bold;">S</div>
                                <h4 style="color: var(--primary-color); margin-bottom: 0.5rem;">Safety First</h4>
                                <p style="color: var(--text-secondary);">Your safety is our top priority in every journey.</p>
                            </div>
                            
                            <div style="text-align: center;">
                                <div style="width: 60px; height: 60px; background: var(--accent-color); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; color: white; font-size: 1.5rem; font-weight: bold;">R</div>
                                <h4 style="color: var(--accent-color); margin-bottom: 0.5rem;">Reliability</h4>
                                <p style="color: var(--text-secondary);">Consistent and dependable service you can count on.</p>
                            </div>
                            
                            <div style="text-align: center;">
                                <div style="width: 60px; height: 60px; background: var(--secondary-color); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; color: white; font-size: 1.5rem; font-weight: bold;">C</div>
                                <h4 style="color: var(--secondary-color); margin-bottom: 0.5rem;">Comfort</h4>
                                <p style="color: var(--text-secondary);">Modern amenities for a pleasant travel experience.</p>
                            </div>
                            
                            <div style="text-align: center;">
                                <div style="width: 60px; height: 60px; background: var(--text-primary); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; color: white; font-size: 1.5rem; font-weight: bold;">E</div>
                                <h4 style="color: var(--text-primary); margin-bottom: 0.5rem;">Excellence</h4>
                                <p style="color: var(--text-secondary);">Striving for the highest standards in everything we do.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Current Date/Time -->
            <div class="text-right">
                <p class="text-secondary"><?php include_once("php_includes/date_time.php"); ?></p>
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