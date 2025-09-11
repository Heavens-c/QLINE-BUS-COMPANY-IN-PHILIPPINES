<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - Dimple Star Transport</title>
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
                        <li><a href="about.php" class="nav-link">About Us</a></li>
                        <li><a href="terminal.php" class="nav-link">Terminals</a></li>
                        <li><a href="routeschedule.php" class="nav-link">Routes & Schedules</a></li>
                        <li><a href="contact.php" class="nav-link active">Contact</a></li>
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
                    <h1 style="font-size: 2.5rem; font-weight: 700; color: var(--text-primary); margin-bottom: 1rem;">Contact Us</h1>
                    <p style="font-size: 1.125rem; color: var(--text-secondary); max-width: 600px; margin: 0 auto;">
                        Get in touch with us for any inquiries, bookings, or support
                    </p>
                </div>
            </section>

            <!-- Contact Information and Form -->
            <section class="content-grid">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 3rem; align-items: start;">
                    <!-- Contact Information -->
                    <div class="card">
                        <div class="card-header">
                            <h2 class="card-title">Get In Touch</h2>
                        </div>
                        <div class="card-body">
                            <div style="margin-bottom: 2rem;">
                                <h3 style="color: var(--primary-color); margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem;">
                                    <span style="font-size: 1.5rem;">📍</span> Address
                                </h3>
                                <p style="line-height: 1.6; color: var(--text-secondary);">
                                    Dimple Star Transport<br>
                                    Block 1 lot 10, Southpoint Subd.<br>
                                    Brgy Banay-Banay, Cabuyao, Laguna
                                </p>
                            </div>
                            
                            <div style="margin-bottom: 2rem;">
                                <h3 style="color: var(--primary-color); margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem;">
                                    <span style="font-size: 1.5rem;">📞</span> Phone
                                </h3>
                                <p style="line-height: 1.6; color: var(--text-secondary);">
                                    <strong style="color: var(--text-primary);">0929 209 0712</strong>
                                </p>
                            </div>
                            
                            <div style="margin-bottom: 2rem;">
                                <h3 style="color: var(--primary-color); margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem;">
                                    <span style="font-size: 1.5rem;">🕒</span> Business Hours
                                </h3>
                                <p style="line-height: 1.6; color: var(--text-secondary);">
                                    Monday - Sunday<br>
                                    5:00 AM - 10:00 PM
                                </p>
                            </div>
                            
                            <div>
                                <h3 style="color: var(--primary-color); margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem;">
                                    <span style="font-size: 1.5rem;">✉️</span> Email
                                </h3>
                                <p style="line-height: 1.6; color: var(--text-secondary);">
                                    info@dimplestartransport.com<br>
                                    bookings@dimplestartransport.com
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Contact Form -->
                    <div class="card">
                        <div class="card-header">
                            <h2 class="card-title">Send Us a Message</h2>
                        </div>
                        <div class="card-body">
                            <form class="validate" action="messageexec.php" method="POST">
                                <div class="form-group">
                                    <label for="name" class="form-label">Full Name *</label>
                                    <input id="name" class="form-input" type="text" name="name" required />
                                </div>
                                
                                <div class="form-group">
                                    <label for="email" class="form-label">Email Address *</label>
                                    <input id="email" class="form-input" type="email" name="email" placeholder="your.email@example.com" required />
                                </div>
                                
                                <div class="form-group">
                                    <label for="subject" class="form-label">Subject *</label>
                                    <input id="subject" class="form-input" type="text" name="subject" required />
                                </div>
                                
                                <div class="form-group">
                                    <label for="message" class="form-label">Message *</label>
                                    <textarea id="message" class="form-input" name="message" rows="5" placeholder="Please describe your inquiry or concern..." required></textarea>
                                </div>
                                
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary" style="width: 100%; padding: 0.75rem;">
                                        Send Message
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Additional Information -->
            <section class="content-grid">
                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title">Frequently Asked Questions</h2>
                    </div>
                    <div class="card-body">
                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 2rem;">
                            <div>
                                <h4 style="color: var(--primary-color); margin-bottom: 0.5rem;">How do I book a ticket?</h4>
                                <p style="color: var(--text-secondary); line-height: 1.6;">
                                    You can book tickets online through our website, visit our terminals, or call our booking hotline.
                                </p>
                            </div>
                            
                            <div>
                                <h4 style="color: var(--primary-color); margin-bottom: 0.5rem;">What are your operating hours?</h4>
                                <p style="color: var(--text-secondary); line-height: 1.6;">
                                    We operate daily from 5:00 AM to 10:00 PM, with regular departures throughout the day.
                                </p>
                            </div>
                            
                            <div>
                                <h4 style="color: var(--primary-color); margin-bottom: 0.5rem;">Do you offer refunds?</h4>
                                <p style="color: var(--text-secondary); line-height: 1.6;">
                                    Refund policies vary based on the circumstances. Please contact us for specific details.
                                </p>
                            </div>
                            
                            <div>
                                <h4 style="color: var(--primary-color); margin-bottom: 0.5rem;">Are your buses air-conditioned?</h4>
                                <p style="color: var(--text-secondary); line-height: 1.6;">
                                    Yes, all our buses are equipped with air conditioning for your comfort.
                                </p>
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