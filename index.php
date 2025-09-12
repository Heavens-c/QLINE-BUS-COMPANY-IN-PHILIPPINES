<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dimple Star Transport - Modern Transportation Solutions</title>
    <link rel="stylesheet" type="text/css" href="style/modern-style.css" />
    <link rel="icon" href="images/icon.ico" type="image/x-icon">
    <script src="slide/js/jquery.js"></script>
    <script src="slide/js/amazingslider.js"></script>
    <script src="slide/js/initslider-1.js"></script>
    <script src="js/modern.js"></script>
</head>
<body>

    <header class="header">
        <div class="container">
            <div class="header-content">
                <a href="index.php">
                    <img src="images/logo.png" class="logo" alt="Dimple Star Transport" />
                </a>
                
                <nav class="nav">
                    <button class="nav-toggle" aria-label="Toggle navigation">
                        <span class="hamburger"></span>
                    </button>
                    <ul class="nav-list">
                        <li><a href="index.php" class="nav-link active">Home</a></li>
                        <li><a href="about.php" class="nav-link">About Us</a></li>
                        <li><a href="terminal.php" class="nav-link">Terminals</a></li>
                        <li><a href="routeschedule.php" class="nav-link">Routes & Schedules</a></li>
                        <li><a href="contact.php" class="nav-link">Contact</a></li>
                        <li><a href="book.php" class="nav-link">Book Now</a></li>
                        <li class="nav-divider"></li>
                        <li class="mobile-user-menu">
                            <?php
                                // Check if the user is logged in and display their email
                                if (isset($_SESSION['email'])) {
                                    echo '<span class="user-welcome">Welcome, ' . htmlspecialchars($_SESSION['email']) . '!</span>';
                                    echo '<a href="logout.php" class="nav-link nav-link-auth">Logout</a>';
                                } else {
                                    echo '<a href="signlog.php" class="nav-link nav-link-auth nav-link-signup">Sign Up</a>';
                                    echo '<a href="signlog.php" class="nav-link nav-link-auth nav-link-login">Login</a>';
                                }
                            ?>
                        </li>

                    </ul>
                    
                    <div class="user-menu desktop-only">
                        <?php
                        if (isset($_SESSION['email'])) {
                            echo '<span class="user-welcome">Welcome, ' . htmlspecialchars($_SESSION['email']) . '!</span>';
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

    <section class="hero">
        <div class="container">
            <div class="hero-content">
                <div class="hero-left">
                    <h1 class="hero-title">Experience Safe & Comfortable Travel</h1>
                    <p class="hero-subtitle">Your trusted transport partner connecting Metro Manila and Mindoro Province with modern buses and professional service.</p>
                    <div class="hero-stats">
                        <div class="stat-item">
                            <span class="stat-number" data-suffix="+">20</span>
                            <span class="stat-label">Routes</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-number" data-suffix="K+">50</span>
                            <span class="stat-label">Happy Passengers</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-number" data-suffix=" hrs">24</span>
                            <span class="stat-label">Daily Service</span>
                        </div>
                    </div>
                    <div class="hero-actions">
                        <a href="book.php" class="btn btn-primary">
                            <span>Book Now</span>
                            <svg class="btn-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M5 12h14m-7-7l7 7-7 7"/>
                            </svg>
                        </a>
                        <a href="routeschedule.php" class="btn btn-secondary">View Schedule</a>
                    </div>
                </div>
                <div class="hero-right">
                    <div class="hero-image-wrapper">
                        <img src="images/oldbus.jpg" alt="Dimple Star Transport Bus" class="hero-image">
                        <div class="experience-badge">
                            <span class="years">19</span>
                            <span class="text">Years of<br>Excellence</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="hero-features">
                <div class="feature-card">
                    <div class="feature-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/>
                        </svg>
                    </div>
                    <div class="feature-content">
                        <h3>Multiple Routes Daily</h3>
                        <p>Regular trips between Metro Manila and Mindoro, with strategic stops along the way</p>
                        <a href="routeschedule.php" class="feature-link">View Routes →</a>
                    </div>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M20 12h-9.5m9.5 0l-3-3m3 3l-3 3m-5-11c-2.5 0-4 1-7 3 3 2 4.5 3 7 3 2.5 0 4-1 7-3-3-2-4.5-3-7-3z"/>
                        </svg>
                    </div>
                    <div class="feature-content">
                        <h3>Real-Time Tracking</h3>
                        <p>Track your bus location and get instant updates about your journey status</p>
                        <a href="#" class="feature-link">Track Now →</a>
                    </div>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M3 10h18M7 15h.01M11 15h.01M15 15h.01M19 15h.01M4 21h16a1 1 0 001-1V6a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z"/>
                        </svg>
                    </div>
                    <div class="feature-content">
                        <h3>Easy Online Booking</h3>
                        <p>Reserve your seats in advance with our simple online booking system</p>
                        <a href="book.php" class="feature-link">Book Now →</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <main class="main-content">
        <div class="container">
            <section class="content-grid">
                <div class="slideshow-container">
                    <div id="amazingslider-1">
                        <ul class="amazingslider-slides" style="display:none;">
                            <li><img src="slide/images/b1.png" class="slideshow-slide" alt="Transport Service 1" /></li>
                            <li><img src="slide/images/b2.png" class="slideshow-slide" alt="Transport Service 2" /></li>
                            <li><img src="slide/images/b3.png" class="slideshow-slide" alt="Transport Service 3" /></li>
                            <li><img src="slide/images/b4.png" class="slideshow-slide" alt="Transport Service 4" /></li>
                        </ul>
                    </div>
                </div>
            </section>

            <section class="content-grid">
                <div class="contact-info">
                    <div class="contact-phone">
                        <span class="contact-phone-label">Contact Us 24/7</span>
                        <span class="contact-phone-number">0929 209 0712</span>
                    </div>
                    <div class="contact-address">
                        <span class="contact-address-label">Main Office</span>
                        <p class="contact-address-text">
                            Block 1 lot 10, Southpoint Subd.<br>
                            Brgy Banay-Banay, Cabuyao, Laguna
                        </p>
                    </div>
                </div>
            </section>

            <section class="content-grid">
                <div class="service-cards">
                    <div class="service-card">
                        <div class="service-card-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div class="service-card-content">
                            <h3>Modern Fleet Services</h3>
                            <p>Experience comfort and safety with our modern bus fleet and professional drivers. Every journey is designed with your comfort in mind.</p>
                            <div class="service-card-features">
                                <span>✓ Professional Drivers</span>
                                <span>✓ Modern Buses</span>
                                <span>✓ Regular Maintenance</span>
                            </div>
                            <a href="about.php" class="service-card-link">Learn More →</a>
                        </div>
                    </div>
                    
                    <div class="service-card">
                        <div class="service-card-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L16 4m0 13V4m0 0L9 7" />
                            </svg>
                        </div>
                        <div class="service-card-content">
                            <h3>Extensive Routes</h3>
                            <p>Connecting Metro Manila and Mindoro Province with regular daily schedules. Choose from multiple departure times that suit your schedule.</p>
                            <div class="service-card-features">
                                <span>✓ Multiple Routes</span>
                                <span>✓ Daily Departures</span>
                                <span>✓ Strategic Stops</span>
                            </div>
                            <a href="routeschedule.php" class="service-card-link">View Routes →</a>
                        </div>
                    </div>
                    
                    <div class="service-card">
                        <div class="service-card-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div class="service-card-content">
                            <h3>Easy Booking</h3>
                            <p>Book your tickets anytime, anywhere with our 24/7 online booking system. Secure your seats in advance for a hassle-free journey.</p>
                            <div class="service-card-features">
                                <span>✓ 24/7 Booking</span>
                                <span>✓ Instant Confirmation</span>
                                <span>✓ Secure Payment</span>
                            </div>
                            <a href="book.php" class="service-card-link">Book Now →</a>
                        </div>
                    </div>
                </div>
            </section>

            <div class="date-time-container">
                <div class="date-time-wrapper">
                    <div class="date-time-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <div class="date-time-content">
                        <span class="date-time-label">Current Date & Time</span>
                        <span class="date-time-value"><?php include_once("php_includes/date_time.php"); ?></span>
                    </div>
                </div>
            </div>
        </div>
    </main>

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
