<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Terminals - Dimple Star Transport</title>
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
                        <li><a href="terminal.php" class="nav-link active">Terminals</a></li>
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
                    <h1 style="font-size: 2.5rem; font-weight: 700; color: var(--text-primary); margin-bottom: 1rem;">Our Terminals</h1>
                    <p style="font-size: 1.125rem; color: var(--text-secondary); max-width: 600px; margin: 0 auto;">
                        Find our terminal locations with maps and contact information
                    </p>
                </div>
            </section>

            <!-- Terminal List -->
            <section class="content-grid">
                <div style="display: grid; gap: 3rem;">
                    <!-- España Terminal -->
                    <div class="card">
                        <div class="card-header" style="background: linear-gradient(135deg, var(--primary-color), var(--primary-dark)); color: white;">
                            <h2 class="card-title" style="color: white; display: flex; align-items: center; gap: 0.5rem;">
                                <svg style="width: 1.5rem; height: 1.5rem;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                                    <circle cx="12" cy="10" r="3"/>
                                </svg>
                                España Terminal
                            </h2>
                        </div>
                        <div class="card-body">
                            <div style="display: grid; grid-template-columns: 1fr 300px; gap: 2rem; align-items: start;">
                                <div>
                                    <div style="margin-bottom: 2rem;">
                                        <h3 style="color: var(--primary-color); margin-bottom: 1rem;">Location & Contact</h3>
                                        <div style="display: grid; gap: 1rem;">
                                            <div style="display: flex; align-items: start; gap: 0.75rem;">
                                                <svg style="width: 1.25rem; height: 1.25rem; color: var(--primary-color); margin-top: 0.125rem;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                                                    <circle cx="12" cy="10" r="3"/>
                                                </svg>
                                                <div>
                                                    <p style="font-weight: 600; color: var(--text-primary); margin: 0;">836B Antipolo St, Sampaloc</p>
                                                    <p style="color: var(--text-secondary); margin: 0;">Manila, Philippines</p>
                                                </div>
                                            </div>
                                            <div style="display: flex; align-items: center; gap: 0.75rem;">
                                                <svg style="width: 1.25rem; height: 1.25rem; color: var(--primary-color);" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
                                                </svg>
                                                <div>
                                                    <span style="font-weight: 600; color: var(--text-primary);">+63.02.985.1451</span>
                                                    <span style="color: var(--text-secondary); margin-left: 0.5rem;">/ +63.908.926.9163</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div style="margin-bottom: 2rem;">
                                        <h4 style="color: var(--primary-color); margin-bottom: 1rem;">Services Available</h4>
                                        <div style="display: flex; flex-wrap: wrap; gap: 0.5rem;">
                                            <span style="background: var(--surface-light); color: var(--primary-color); padding: 0.5rem 1rem; border-radius: var(--radius-sm); font-size: 0.875rem; font-weight: 500;">Ticket Booking</span>
                                            <span style="background: var(--surface-light); color: var(--primary-color); padding: 0.5rem 1rem; border-radius: var(--radius-sm); font-size: 0.875rem; font-weight: 500;">Passenger Information</span>
                                            <span style="background: var(--surface-light); color: var(--primary-color); padding: 0.5rem 1rem; border-radius: var(--radius-sm); font-size: 0.875rem; font-weight: 500;">Waiting Area</span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div>
                                    <div style="border-radius: var(--radius-md); overflow: hidden; box-shadow: var(--shadow-md);">
                                        <iframe 
                                            width="100%" 
                                            height="250" 
                                            frameborder="0" 
                                            scrolling="no" 
                                            marginheight="0" 
                                            marginwidth="0" 
                                            src="https://maps.google.com.ph/maps?f=q&amp;source=s_q&amp;hl=en&amp;geocode=&amp;q=Dimple+Star,+836BAntipoloStSampaloc,521,Manila,&amp;aq=0&amp;oq=Metro+Manila&amp;sll=14.6125312,120.9948033&amp;sspn=0.011772,0.021136&amp;t=h&amp;ie=UTF8&amp;hq=&amp;hnear=Dimple+Star&amp;ll=14.6125312,120.9948033&amp;spn=0.011772,0.021136&amp;z=14&amp;output=embed"
                                            style="border: none;">
                                        </iframe>
                                    </div>
                                    <div style="text-align: center; margin-top: 0.5rem;">
                                        <a href="https://www.google.com/maps/place/Dimple+Star/@14.6125312,120.9948033,770m/data=!3m2!1e3!4b1!4m2!3m1!1s0x3397b60300001d5d:0xd30645794daddf84?hl=en;z=14" 
                                           target="_blank" 
                                           class="btn btn-outline btn-sm" 
                                           style="font-size: 0.875rem;">
                                            View Larger Map
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- San Jose Terminal -->
                    <div class="card">
                        <div class="card-header" style="background: linear-gradient(135deg, var(--secondary-color), #d97706); color: white;">
                            <h2 class="card-title" style="color: white; display: flex; align-items: center; gap: 0.5rem;">
                                <svg style="width: 1.5rem; height: 1.5rem;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                                    <circle cx="12" cy="10" r="3"/>
                                </svg>
                                San Jose Terminal
                            </h2>
                        </div>
                        <div class="card-body">
                            <div style="display: grid; grid-template-columns: 1fr 300px; gap: 2rem; align-items: start;">
                                <div>
                                    <div style="margin-bottom: 2rem;">
                                        <h3 style="color: var(--secondary-color); margin-bottom: 1rem;">Location & Contact</h3>
                                        <div style="display: grid; gap: 1rem;">
                                            <div style="display: flex; align-items: start; gap: 0.75rem;">
                                                <svg style="width: 1.25rem; height: 1.25rem; color: var(--secondary-color); margin-top: 0.125rem;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                                                    <circle cx="12" cy="10" r="3"/>
                                                </svg>
                                                <div>
                                                    <p style="font-weight: 600; color: var(--text-primary); margin: 0;">Bonifacio Street</p>
                                                    <p style="color: var(--text-secondary); margin: 0;">San Jose, Occidental Mindoro</p>
                                                </div>
                                            </div>
                                            <div style="display: flex; align-items: center; gap: 0.75rem;">
                                                <svg style="width: 1.25rem; height: 1.25rem; color: var(--secondary-color);" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
                                                </svg>
                                                <div>
                                                    <span style="font-weight: 600; color: var(--text-primary);">+63.02.6684151</span>
                                                    <span style="color: var(--text-secondary); margin-left: 0.5rem;">/ +63.921.568.6449</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div style="margin-bottom: 2rem;">
                                        <h4 style="color: var(--secondary-color); margin-bottom: 1rem;">Services Available</h4>
                                        <div style="display: flex; flex-wrap: wrap; gap: 0.5rem;">
                                            <span style="background: #fef3e2; color: var(--secondary-color); padding: 0.5rem 1rem; border-radius: var(--radius-sm); font-size: 0.875rem; font-weight: 500;">Ticket Booking</span>
                                            <span style="background: #fef3e2; color: var(--secondary-color); padding: 0.5rem 1rem; border-radius: var(--radius-sm); font-size: 0.875rem; font-weight: 500;">Passenger Information</span>
                                            <span style="background: #fef3e2; color: var(--secondary-color); padding: 0.5rem 1rem; border-radius: var(--radius-sm); font-size: 0.875rem; font-weight: 500;">Waiting Area</span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div>
                                    <div style="border-radius: var(--radius-md); overflow: hidden; box-shadow: var(--shadow-md);">
                                        <iframe 
                                            width="100%" 
                                            height="250" 
                                            frameborder="0" 
                                            scrolling="no" 
                                            marginheight="0" 
                                            marginwidth="0" 
                                            src="https://maps.google.com.ph/maps?f=q&amp;source=s_q&amp;hl=en&amp;geocode=&amp;q=Dimple+Star+Transport,+BonifacioSt,SanJose,OccidentalMindoro,&amp;aq=0&amp;oq=&amp;sll=12.3540632,121.0618653&amp;sspn=0.011772,0.021136&amp;t=h&amp;ie=UTF8&amp;hq=&amp;hnear=Dimple+Star+Transport&amp;ll=12.3540632,121.0618653&amp;spn=0.011772,0.021136&amp;z=14&amp;output=embed"
                                            style="border: none;">
                                        </iframe>
                                    </div>
                                    <div style="text-align: center; margin-top: 0.5rem;">
                                        <a href="https://www.google.com/maps/place/Dimple+Star+Transport/@14.6143711,120.9841972,458m/data=!3m2!1e3!4b1!4m2!3m1!1s0x3397b5fe6f7ebf6b:0xc34baa5ed38261eb?hl=en" 
                                           target="_blank" 
                                           class="btn btn-outline btn-sm" 
                                           style="font-size: 0.875rem;">
                                            View Larger Map
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Terminal Information -->
                    <div class="card">
                        <div class="card-header">
                            <h2 class="card-title">Terminal Information</h2>
                        </div>
                        <div class="card-body">
                            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 2rem;">
                                <div>
                                    <h4 style="color: var(--primary-color); margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem;">
                                        <svg style="width: 1.25rem; height: 1.25rem;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <circle cx="12" cy="12" r="10"/>
                                            <polyline points="12,6 12,12 16,14"/>
                                        </svg>
                                        Operating Hours
                                    </h4>
                                    <p style="color: var(--text-secondary); line-height: 1.6;">
                                        <strong>Daily:</strong> 5:00 AM - 10:00 PM<br>
                                        Ticket counters open 30 minutes before first departure
                                    </p>
                                </div>
                                
                                <div>
                                    <h4 style="color: var(--primary-color); margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem;">
                                        <svg style="width: 1.25rem; height: 1.25rem;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/>
                                            <rect x="8" y="2" width="8" height="4" rx="1" ry="1"/>
                                        </svg>
                                        Services
                                    </h4>
                                    <ul style="color: var(--text-secondary); line-height: 1.8; margin: 0; padding-left: 1rem;">
                                        <li>Ticket booking and reservations</li>
                                        <li>Baggage handling assistance</li>
                                        <li>Waiting areas with seating</li>
                                        <li>Restroom facilities</li>
                                    </ul>
                                </div>
                                
                                <div>
                                    <h4 style="color: var(--primary-color); margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem;">
                                        <svg style="width: 1.25rem; height: 1.25rem;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M14 9V5a3 3 0 0 0-6 0v4"/>
                                            <rect x="2" y="9" width="20" height="11" rx="2" ry="2"/>
                                        </svg>
                                        Safety & Security
                                    </h4>
                                    <p style="color: var(--text-secondary); line-height: 1.6;">
                                        CCTV monitoring, security personnel on duty,<br>
                                        and regular safety inspections of all vehicles.
                                    </p>
                                </div>
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