<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Routes & Schedules - Dimple Star Transport</title>
    <link rel="stylesheet" type="text/css" href="style/modern-style.css" />
    <link rel="icon" href="images/icon.ico" type="image/x-icon">
    <script src="js/modern.js" defer></script>
    <style>
        .route-map {
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .route-map img {
            max-width: 100%;
            height: auto;
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-md);
        }
        
        .schedule-table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: var(--radius-md);
            overflow: hidden;
            box-shadow: var(--shadow-sm);
        }
        
        .schedule-table th {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: white;
            padding: 1rem;
            text-align: left;
            font-weight: 600;
            font-size: 0.95rem;
        }
        
        .schedule-table td {
            padding: 1rem;
            border-bottom: 1px solid var(--border-light);
            vertical-align: top;
        }
        
        .schedule-table tr:last-child td {
            border-bottom: none;
        }
        
        .schedule-table tr:nth-child(even) {
            background: var(--surface-light);
        }
        
        .schedule-table tr:hover {
            background: rgba(59, 130, 246, 0.05);
            transition: background-color 0.2s ease;
        }
        
        .origin-cell {
            font-weight: 600;
            color: var(--text-primary);
        }
        
        .destination-cell {
            font-weight: 600;
            color: var(--secondary-color);
        }
        
        .schedule-times {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
        }
        
        .time-badge {
            background: var(--accent-color);
            color: white;
            padding: 0.375rem 0.75rem;
            border-radius: var(--radius-sm);
            font-size: 0.875rem;
            font-weight: 500;
            display: inline-block;
        }
        
        .route-info-banner {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 1.5rem;
            border-radius: var(--radius-lg);
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .route-info-banner h3 {
            margin: 0;
            font-size: 1.25rem;
            font-weight: 600;
        }
        
        .filter-section {
            background: white;
            padding: 1.5rem;
            border-radius: var(--radius-md);
            box-shadow: var(--shadow-sm);
            margin-bottom: 2rem;
        }
        
        .filter-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            align-items: end;
        }
        
        .filter-group {
            display: flex;
            flex-direction: column;
        }
        
        .filter-label {
            font-weight: 500;
            color: var(--text-primary);
            margin-bottom: 0.5rem;
            font-size: 0.875rem;
        }
        
        .filter-select {
            padding: 0.75rem;
            border: 2px solid var(--border-light);
            border-radius: var(--radius-md);
            background: white;
            font-size: 0.95rem;
            transition: border-color 0.2s ease;
        }
        
        .filter-select:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }
        
        .btn-filter {
            padding: 0.75rem 1.5rem;
            background: var(--primary-color);
            color: white;
            border: none;
            border-radius: var(--radius-md);
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        
        .btn-filter:hover {
            background: var(--primary-dark);
            transform: translateY(-1px);
        }
        
        .route-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .stat-card {
            background: white;
            padding: 1.5rem;
            border-radius: var(--radius-md);
            box-shadow: var(--shadow-sm);
            text-align: center;
            border-top: 4px solid var(--primary-color);
        }
        
        .stat-number {
            font-size: 2rem;
            font-weight: 700;
            color: var(--primary-color);
            display: block;
        }
        
        .stat-label {
            color: var(--text-secondary);
            font-size: 0.875rem;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .legend {
            background: white;
            padding: 1.5rem;
            border-radius: var(--radius-md);
            box-shadow: var(--shadow-sm);
            margin-top: 2rem;
        }
        
        .legend-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
        }
        
        .legend-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        
        .legend-icon {
            width: 2.5rem;
            height: 2.5rem;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            color: white;
        }
        
        .legend-icon.ac { background: var(--primary-color); }
        .legend-icon.ord { background: var(--secondary-color); }
        .legend-icon.exp { background: var(--accent-color); }
        
        .note-section {
            background: #fef3e2;
            border: 1px solid #fed7aa;
            border-radius: var(--radius-md);
            padding: 1.5rem;
            margin-top: 2rem;
        }
        
        .note-title {
            font-weight: 600;
            color: var(--secondary-color);
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        @media (max-width: 768px) {
            .schedule-table {
                font-size: 0.875rem;
            }
            
            .schedule-table th,
            .schedule-table td {
                padding: 0.75rem 0.5rem;
            }
            
            .time-badge {
                font-size: 0.75rem;
                padding: 0.25rem 0.5rem;
            }
            
            .route-stats {
                grid-template-columns: repeat(2, 1fr);
            }
        }
    </style>
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
                        <li><a href="routeschedule.php" class="nav-link active">Routes & Schedules</a></li>
                        <li><a href="contact.php" class="nav-link">Contact</a></li>
                        <li><a href="book.php" class="nav-link">Book Now</a></li>
                    </ul>
                    
                    <div class="user-menu">
                        <?php
                          
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
                    <h1 style="font-size: 2.5rem; font-weight: 700; color: var(--text-primary); margin-bottom: 1rem;">Routes & Schedules</h1>
                    <p style="font-size: 1.125rem; color: var(--text-secondary); max-width: 600px; margin: 0 auto;">
                        Find departure times and routes for your journey across Metro Manila and Mindoro Province
                    </p>
                </div>
            </section>

            <!-- Route Statistics -->
            <section class="content-grid">
                <div class="route-stats">
                    <div class="stat-card">
                        <span class="stat-number">6</span>
                        <span class="stat-label">Terminal Locations</span>
                    </div>
                    <div class="stat-card">
                        <span class="stat-number">16</span>
                        <span class="stat-label">Destinations</span>
                    </div>
                    <div class="stat-card">
                        <span class="stat-number">25+</span>
                        <span class="stat-label">Daily Departures</span>
                    </div>
                    <div class="stat-card">
                        <span class="stat-number">17</span>
                        <span class="stat-label">Hours of Service</span>
                    </div>
                </div>
            </section>

            <!-- Route Map -->
            <section class="content-grid">
                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title">Route Network Map</h2>
                    </div>
                    <div class="card-body">
                        <div class="route-map">
                            <img src="images/route.png" alt="Dimple Star Transport Route Map" />
                        </div>
                    </div>
                </div>
            </section>

            <!-- Important Notice -->
            <section class="content-grid">
                <div class="route-info-banner">
                    <h3>📍 All trips are vice versa (two-way service available)</h3>
                    <p style="margin: 0.5rem 0 0 0; opacity: 0.9;">Return trips available from all destinations back to origin points</p>
                </div>
            </section>

            <!-- Filter Section -->
            <section class="content-grid">
                <div class="filter-section">
                    <h3 style="margin-bottom: 1rem; color: var(--text-primary);">Filter Routes</h3>
                    <div class="filter-grid">
                        <div class="filter-group">
                            <label class="filter-label">From Terminal</label>
                            <select class="filter-select" id="originFilter">
                                <option value="">All Origins</option>
                                <option value="Ali Mall Cubao">Ali Mall Cubao</option>
                                <option value="Alabang">Alabang</option>
                                <option value="Cabuyao">Cabuyao</option>
                                <option value="Espana">España</option>
                                <option value="San Lazaro">San Lazaro</option>
                                <option value="Pasay">Pasay</option>
                            </select>
                        </div>
                        <div class="filter-group">
                            <label class="filter-label">To Destination</label>
                            <select class="filter-select" id="destinationFilter">
                                <option value="">All Destinations</option>
                                <option value="San Jose">San Jose</option>
                            </select>
                        </div>
                        <div class="filter-group">
                            <label class="filter-label">Time Period</label>
                            <select class="filter-select" id="timeFilter">
                                <option value="">All Times</option>
                                <option value="morning">Morning (5AM-12PM)</option>
                                <option value="afternoon">Afternoon (12PM-6PM)</option>
                                <option value="evening">Evening (6PM-11PM)</option>
                            </select>
                        </div>
                        <button class="btn-filter" onclick="filterSchedules()">Apply Filters</button>
                    </div>
                </div>
            </section>

            <!-- Schedule Table -->
            <section class="content-grid">
                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title">Regular Departure Schedules</h2>
                    </div>
                    <div class="card-body" style="padding: 0; overflow-x: auto;">
                        <table class="schedule-table">
                            <thead>
                                <tr>
                                    <th style="min-width: 180px;">
                                        <svg style="width: 1.25rem; height: 1.25rem; display: inline-block; margin-right: 0.5rem;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                                            <circle cx="12" cy="10" r="3"/>
                                        </svg>
                                        Origin Terminal
                                    </th>
                                    <th style="min-width: 200px;">
                                        <svg style="width: 1.25rem; height: 1.25rem; display: inline-block; margin-right: 0.5rem;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <circle cx="12" cy="12" r="10"/>
                                            <polyline points="12,6 12,12 16,14"/>
                                        </svg>
                                        Departure Times
                                    </th>
                                    <th style="min-width: 150px;">
                                        <svg style="width: 1.25rem; height: 1.25rem; display: inline-block; margin-right: 0.5rem;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                                            <circle cx="12" cy="10" r="3"/>
                                        </svg>
                                        Destination
                                    </th>
                                </tr>
                            </thead>
                            <tbody id="scheduleTableBody">
                                <tr class="schedule-row" data-origin="Ali Mall Cubao" data-destination="San Jose" data-times="9:00,10:00,13:00,16:00">
                                    <td class="origin-cell">Ali Mall Cubao Terminal</td>
                                    <td>
                                        <div class="schedule-times">
                                            <span class="time-badge">9:00 AM</span>
                                            <span class="time-badge">10:00 AM</span>
                                            <span class="time-badge">1:00 PM</span>
                                            <span class="time-badge">4:00 PM</span>
                                        </div>
                                    </td>
                                    <td class="destination-cell">San Jose</td>
                                </tr>
                                <tr class="schedule-row" data-origin="Alabang" data-destination="San Jose" data-times="6:00,7:00,14:00,18:00,22:00">
                                    <td class="origin-cell">Alabang Terminal</td>
                                    <td>
                                        <div class="schedule-times">
                                            <span class="time-badge">6:00 AM</span>
                                            <span class="time-badge">7:00 AM</span>
                                            <span class="time-badge">2:00 PM</span>
                                            <span class="time-badge">6:00 PM</span>
                                            <span class="time-badge">10:00 PM</span>
                                        </div>
                                    </td>
                                    <td class="destination-cell">San Jose</td>
                                </tr>
                                <tr class="schedule-row" data-origin="Cabuyao" data-destination="San Jose" data-times="8:00,9:00,16:00,20:00">
                                    <td class="origin-cell">Cabuyao Terminal</td>
                                    <td>
                                        <div class="schedule-times">
                                            <span class="time-badge">8:00 AM</span>
                                            <span class="time-badge">9:00 AM</span>
                                            <span class="time-badge">4:00 PM</span>
                                            <span class="time-badge">8:00 PM</span>
                                        </div>
                                    </td>
                                    <td class="destination-cell">San Jose</td>
                                </tr>
                                <tr class="schedule-row" data-origin="Espana" data-destination="San Jose" data-times="4:30,5:30,12:00,16:00,20:00">
                                    <td class="origin-cell">España Terminal</td>
                                    <td>
                                        <div class="schedule-times">
                                            <span class="time-badge">4:30 AM</span>
                                            <span class="time-badge">5:30 AM</span>
                                            <span class="time-badge">12:00 PM</span>
                                            <span class="time-badge">4:00 PM</span>
                                            <span class="time-badge">8:00 PM</span>
                                        </div>
                                    </td>
                                    <td class="destination-cell">San Jose</td>
                                </tr>
                                <tr class="schedule-row" data-origin="San Lazaro" data-destination="San Jose" data-times="3:00,4:30,11:00,15:00,19:00">
                                    <td class="origin-cell">San Lazaro Terminal</td>
                                    <td>
                                        <div class="schedule-times">
                                            <span class="time-badge">3:00 AM</span>
                                            <span class="time-badge">4:30 AM</span>
                                            <span class="time-badge">11:00 AM</span>
                                            <span class="time-badge">3:00 PM</span>
                                            <span class="time-badge">7:00 PM</span>
                                        </div>
                                    </td>
                                    <td class="destination-cell">San Jose</td>
                                </tr>
                                <tr class="schedule-row" data-origin="Pasay" data-destination="San Jose" data-times="5:00,6:00,13:00,15:00">
                                    <td class="origin-cell">Pasay Terminal</td>
                                    <td>
                                        <div class="schedule-times">
                                            <span class="time-badge">5:00 AM</span>
                                            <span class="time-badge">6:00 AM</span>
                                            <span class="time-badge">1:00 PM</span>
                                            <span class="time-badge">3:00 PM</span>
                                        </div>
                                    </td>
                                    <td class="destination-cell">San Jose</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>

            <!-- Legend and Additional Information -->
            <section class="content-grid">
                <div class="legend">
                    <h3 style="margin-bottom: 1.5rem; color: var(--text-primary);">Service Information</h3>
                    <div class="legend-grid">
                        <div class="legend-item">
                            <div class="legend-icon ac">AC</div>
                            <div>
                                <h4 style="margin: 0; color: var(--text-primary);">Air Conditioned</h4>
                                <p style="margin: 0; color: var(--text-secondary); font-size: 0.875rem;">Premium comfort buses with AC</p>
                            </div>
                        </div>
                        <div class="legend-item">
                            <div class="legend-icon ord">ORD</div>
                            <div>
                                <h4 style="margin: 0; color: var(--text-primary);">Ordinary</h4>
                                <p style="margin: 0; color: var(--text-secondary); font-size: 0.875rem;">Standard comfortable buses</p>
                            </div>
                        </div>
                        <div class="legend-item">
                            <div class="legend-icon exp">EXP</div>
                            <div>
                                <h4 style="margin: 0; color: var(--text-primary);">Express</h4>
                                <p style="margin: 0; color: var(--text-secondary); font-size: 0.875rem;">Limited stops, faster travel</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Important Notes -->
            <section class="content-grid">
                <div class="note-section">
                    <h4 class="note-title">
                        <svg style="width: 1.25rem; height: 1.25rem;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"/>
                            <path d="M12 6v6l4 2"/>
                        </svg>
                        Important Schedule Information
                    </h4>
                    <ul style="margin: 0; padding-left: 1.5rem; color: var(--text-secondary); line-height: 1.6;">
                        <li>Schedules may vary during holidays and special events</li>
                        <li>Please arrive at the terminal at least 30 minutes before departure</li>
                        <li>All departure times are approximate and subject to traffic conditions</li>
                        <li>Advanced booking is recommended, especially during peak seasons</li>
                        <li>Contact our terminals directly for real-time schedule updates</li>
                    </ul>
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

    <script>
        function filterSchedules() {
            const originFilter = document.getElementById('originFilter').value.toLowerCase();
            const destinationFilter = document.getElementById('destinationFilter').value.toLowerCase();
            const timeFilter = document.getElementById('timeFilter').value;
            const rows = document.querySelectorAll('.schedule-row');
            
            rows.forEach(row => {
                const origin = row.dataset.origin.toLowerCase();
                const destination = row.dataset.destination.toLowerCase();
                const times = row.dataset.times.split(',');
                
                let showRow = true;
                
                // Filter by origin
                if (originFilter && !origin.includes(originFilter)) {
                    showRow = false;
                }
                
                // Filter by destination
                if (destinationFilter && !destination.includes(destinationFilter)) {
                    showRow = false;
                }
                
                // Filter by time period
                if (timeFilter && showRow) {
                    const hasTimeMatch = times.some(time => {
                        const hour = parseInt(time.split(':')[0]);
                        if (timeFilter === 'morning' && hour >= 5 && hour < 12) return true;
                        if (timeFilter === 'afternoon' && hour >= 12 && hour < 18) return true;
                        if (timeFilter === 'evening' && hour >= 18 && hour <= 23) return true;
                        return false;
                    });
                    if (!hasTimeMatch) showRow = false;
                }
                
                row.style.display = showRow ? '' : 'none';
            });
        }
        
        // Reset filters
        function resetFilters() {
            document.getElementById('originFilter').value = '';
            document.getElementById('destinationFilter').value = '';
            document.getElementById('timeFilter').value = '';
            filterSchedules();
        }
    </script>
</body>
</html>