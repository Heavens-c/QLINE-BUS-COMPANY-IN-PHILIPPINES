<!DOCTYPE html>
<?php
    include 'php_includes/connection.php';
    include 'php_includes/book.php';
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Your Journey - Dimple Star Transport</title>
    <link rel="stylesheet" type="text/css" href="style/modern-style.css" />
    <link rel="icon" href="images/icon.ico" type="image/x-icon">
    <script src="js/modern.js" defer></script>
    <style>
        .booking-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 2rem;
        }

        .booking-form {
            background: var(--card-background);
            border-radius: var(--radius-lg);
            padding: 2rem;
            box-shadow: var(--shadow-lg);
            border: 1px solid var(--border-color);
        }

        .form-section {
            margin-bottom: 2rem;
        }

        .form-section h3 {
            color: var(--primary-color);
            margin-bottom: 1rem;
            font-size: 1.25rem;
            font-weight: 600;
            border-bottom: 2px solid var(--primary-color);
            padding-bottom: 0.5rem;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: block;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
        }

        .form-input, .form-select {
            width: 100%;
            padding: 0.75rem;
            border: 2px solid var(--border-color);
            border-radius: var(--radius-md);
            font-size: 1rem;
            background: var(--input-background);
            color: var(--text-primary);
            transition: all 0.3s ease;
        }

        .form-input:focus, .form-select:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .trip-type {
            display: flex;
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .radio-group {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
            padding: 0.5rem 1rem;
            border: 2px solid var(--border-color);
            border-radius: var(--radius-md);
            transition: all 0.3s ease;
        }

        .radio-group:hover {
            border-color: var(--primary-color);
            background: rgba(59, 130, 246, 0.05);
        }

        .radio-group input[type="radio"] {
            margin: 0;
        }

        .radio-group.active {
            border-color: var(--primary-color);
            background: rgba(59, 130, 246, 0.1);
        }

        .submit-section {
            margin-top: 2rem;
            padding-top: 2rem;
            border-top: 1px solid var(--border-color);
        }

        .submit-btn {
            width: 100%;
            padding: 1rem 2rem;
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: white;
            border: none;
            border-radius: var(--radius-md);
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(59, 130, 246, 0.3);
        }

        .submit-btn:active {
            transform: translateY(0);
        }

        .booking-summary {
            background: linear-gradient(135deg, #f8fafc, #e2e8f0);
            border-radius: var(--radius-lg);
            padding: 1.5rem;
            margin-top: 1rem;
            border-left: 4px solid var(--primary-color);
        }

        .booking-hero {
            text-align: center;
            margin-bottom: 3rem;
        }

        .booking-hero h1 {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 1rem;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .booking-hero p {
            font-size: 1.125rem;
            color: var(--text-secondary);
            max-width: 600px;
            margin: 0 auto;
        }

        .route-indicator {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 1rem;
            margin: 1rem 0;
            padding: 1rem;
            background: rgba(59, 130, 246, 0.05);
            border-radius: var(--radius-md);
        }

        .route-point {
            padding: 0.5rem 1rem;
            background: var(--primary-color);
            color: white;
            border-radius: var(--radius-md);
            font-weight: 600;
        }

        .route-arrow {
            font-size: 1.5rem;
            color: var(--primary-color);
        }

        .date-input-wrapper {
            position: relative;
        }

        .date-input-wrapper input[type="text"] {
            cursor: pointer;
        }

        .disabled-field {
            opacity: 0.5;
            pointer-events: none;
        }

        @media (max-width: 768px) {
            .booking-container {
                padding: 1rem;
            }

            .booking-form {
                padding: 1.5rem;
            }

            .form-row {
                grid-template-columns: 1fr;
            }

            .trip-type {
                flex-direction: column;
            }

            .booking-hero h1 {
                font-size: 2rem;
            }
        }

        /* Calendar Styling */
        .calendar {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            font-size: 0.9em;
            background: white;
            color: var(--text-primary);
            border: 1px solid var(--border-color);
            border-radius: var(--radius-md);
            padding: 1rem;
            box-shadow: var(--shadow-lg);
            width: 250px;
        }

        .calendar .months {
            background: var(--primary-color);
            border-radius: var(--radius-sm);
            color: white;
            padding: 0.5rem;
            text-align: center;
            margin-bottom: 1rem;
        }

        .calendar .prev-month,
        .calendar .next-month {
            padding: 0.25rem 0.5rem;
            border-radius: var(--radius-sm);
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .calendar .prev-month:hover,
        .calendar .next-month:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        .calendar td span {
            display: block;
            padding: 0.5rem;
            text-align: center;
            border-radius: var(--radius-sm);
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .calendar td span:hover {
            background: var(--primary-color);
            color: white;
        }

        .calendar td.today span {
            background: var(--secondary-color);
            color: white;
            font-weight: 600;
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
                        <li><a href="routeschedule.php" class="nav-link">Routes & Schedules</a></li>
                        <li><a href="contact.php" class="nav-link">Contact</a></li>
                        <li><a href="book.php" class="nav-link active">Book Now</a></li>
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
            <div class="booking-container">
                <!-- Booking Hero Section -->
                <div class="booking-hero">
                    <h1>Book Your Journey</h1>
                    <p>Reserve your comfortable seat and enjoy a safe, reliable trip with Dimple Star Transport</p>
                </div>

                <!-- Booking Form -->
                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" class="booking-form" id="bookingForm">
                    <!-- Trip Type Section -->
                    <div class="form-section">
                        <h3>Trip Type</h3>
                        <div class="trip-type">
                            <label class="radio-group" id="oneWayGroup">
                                <input type="radio" name="way" value="1" id="oneWay" checked>
                                <span>One Way</span>
                            </label>
                            <label class="radio-group" id="twoWayGroup">
                                <input type="radio" name="way" value="2" id="twoWay">
                                <span>Round Trip</span>
                            </label>
                        </div>
                    </div>

                    <!-- Route Selection -->
                    <div class="form-section">
                        <h3>Route Selection</h3>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="origin" class="form-label">Origin</label>
                                <select name="Origin" id="origin" class="form-select" required>
                                    <option value="">Select Origin</option>
                                    <option value="San Lazaro">San Lazaro Terminal</option>
                                    <option value="Espana">España Terminal</option>
                                    <option value="Alabang">Alabang Terminal</option>
                                    <option value="Cabuyao">Cabuyao Terminal</option>
                                    <option value="Naujan">Naujan</option>
                                    <option value="Victoria">Victoria</option>
                                    <option value="Pinamalayan">Pinamalayan</option>
                                    <option value="Gloria">Gloria</option>
                                    <option value="Bongabong">Bongabong</option>
                                    <option value="Roxas">Roxas</option>
                                    <option value="Mansalay">Mansalay</option>
                                    <option value="Bulalacao">Bulalacao</option>
                                    <option value="Magsaysay">Magsaysay</option>
                                    <option value="San Jose">San Jose</option>
                                    <option value="Pola">Pola</option>
                                    <option value="Soccoro">Socorro</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="destination" class="form-label">Destination</label>
                                <select name="Destination" id="destination" class="form-select" required>
                                    <option value="">Select Destination</option>
                                    <option value="San Lazaro">San Lazaro Terminal</option>
                                    <option value="Espana">España Terminal</option>
                                    <option value="Alabang">Alabang Terminal</option>
                                    <option value="Cabuyao">Cabuyao Terminal</option>
                                    <option value="Naujan">Naujan</option>
                                    <option value="Victoria">Victoria</option>
                                    <option value="Pinamalayan">Pinamalayan</option>
                                    <option value="Gloria">Gloria</option>
                                    <option value="Bongabong">Bongabong</option>
                                    <option value="Roxas">Roxas</option>
                                    <option value="Mansalay">Mansalay</option>
                                    <option value="Bulalacao">Bulalacao</option>
                                    <option value="Magsaysay">Magsaysay</option>
                                    <option value="San Jose">San Jose</option>
                                    <option value="Pola">Pola</option>
                                    <option value="Soccoro">Socorro</option>
                                </select>
                            </div>
                        </div>
                        <div class="route-indicator" id="routeIndicator" style="display: none;">
                            <div class="route-point" id="originPoint">-</div>
                            <div class="route-arrow">→</div>
                            <div class="route-point" id="destinationPoint">-</div>
                        </div>
                    </div>

                    <!-- Travel Details -->
                    <div class="form-section">
                        <h3>Travel Details</h3>
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="passengers" class="form-label">Number of Passengers</label>
                                <input type="number" name="no_of_pass" id="passengers" class="form-input" min="1" max="10" required>
                            </div>
                            <div class="form-group">
                                <label for="bustype" class="form-label">Bus Type</label>
                                <select name="bustype" id="bustype" class="form-select" required>
                                    <option value="">Select Bus Type</option>
                                    <option value="Air Conditioned">Air Conditioned</option>
                                    <option value="Ordinary">Ordinary</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Date Selection -->
                    <div class="form-section">
                        <h3>Travel Dates</h3>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="datepick1" class="form-label">Departure Date</label>
                                <div class="date-input-wrapper">
                                    <input type="text" id="datepick1" name="Departure" class="form-input" placeholder="Select departure date" required readonly>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="datepick2" class="form-label">Return Date</label>
                                <div class="date-input-wrapper disabled-field" id="returnDateWrapper">
                                    <input type="text" id="datepick2" name="Return" class="form-input" placeholder="Select return date" readonly disabled>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Booking Summary -->
                    <div class="booking-summary" id="bookingSummary" style="display: none;">
                        <h4 style="margin-bottom: 1rem; color: var(--primary-color);">Booking Summary</h4>
                        <div id="summaryContent"></div>
                    </div>

                    <!-- Submit Section -->
                    <div class="submit-section">
                        <button type="submit" name="submit" class="submit-btn">
                            <span>Book Your Journey</span>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Current Date/Time -->
            <div class="text-right" style="margin-top: 2rem;">
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

    <!-- JavaScript -->
    <script type="text/javascript" src="js/datepickr.js"></script>
    <script>
        // Initialize date pickers
        new datepickr('datepick1', {
            'dateFormat': '20y-m-d'
        });
        
        new datepickr('datepick2', {
            'dateFormat': '20y-m-d'
        });

        // Trip type handling
        const oneWayRadio = document.getElementById('oneWay');
        const twoWayRadio = document.getElementById('twoWay');
        const oneWayGroup = document.getElementById('oneWayGroup');
        const twoWayGroup = document.getElementById('twoWayGroup');
        const returnDateWrapper = document.getElementById('returnDateWrapper');
        const returnDateInput = document.getElementById('datepick2');

        function updateTripType() {
            if (oneWayRadio.checked) {
                oneWayGroup.classList.add('active');
                twoWayGroup.classList.remove('active');
                returnDateWrapper.classList.add('disabled-field');
                returnDateInput.disabled = true;
                returnDateInput.required = false;
            } else {
                twoWayGroup.classList.add('active');
                oneWayGroup.classList.remove('active');
                returnDateWrapper.classList.remove('disabled-field');
                returnDateInput.disabled = false;
                returnDateInput.required = true;
            }
        }

        oneWayRadio.addEventListener('change', updateTripType);
        twoWayRadio.addEventListener('change', updateTripType);

        // Route indicator
        const originSelect = document.getElementById('origin');
        const destinationSelect = document.getElementById('destination');
        const routeIndicator = document.getElementById('routeIndicator');
        const originPoint = document.getElementById('originPoint');
        const destinationPoint = document.getElementById('destinationPoint');

        function updateRouteIndicator() {
            const origin = originSelect.value;
            const destination = destinationSelect.value;
            
            if (origin && destination && origin !== destination) {
                originPoint.textContent = origin;
                destinationPoint.textContent = destination;
                routeIndicator.style.display = 'flex';
            } else {
                routeIndicator.style.display = 'none';
            }
            
            updateBookingSummary();
        }

        originSelect.addEventListener('change', updateRouteIndicator);
        destinationSelect.addEventListener('change', updateRouteIndicator);

        // Booking summary
        function updateBookingSummary() {
            const origin = originSelect.value;
            const destination = destinationSelect.value;
            const passengers = document.getElementById('passengers').value;
            const busType = document.getElementById('bustype').value;
            const departure = document.getElementById('datepick1').value;
            const returnDate = document.getElementById('datepick2').value;
            const tripType = twoWayRadio.checked ? 'Round Trip' : 'One Way';
            
            if (origin && destination && passengers && busType && departure) {
                const summary = document.getElementById('bookingSummary');
                const content = document.getElementById('summaryContent');
                
                let summaryHTML = `
                    <div style="display: grid; gap: 0.5rem;">
                        <div><strong>Route:</strong> ${origin} → ${destination}</div>
                        <div><strong>Trip Type:</strong> ${tripType}</div>
                        <div><strong>Passengers:</strong> ${passengers}</div>
                        <div><strong>Bus Type:</strong> ${busType}</div>
                        <div><strong>Departure:</strong> ${departure}</div>
                `;
                
                if (tripType === 'Round Trip' && returnDate) {
                    summaryHTML += `<div><strong>Return:</strong> ${returnDate}</div>`;
                }
                
                summaryHTML += `</div>`;
                content.innerHTML = summaryHTML;
                summary.style.display = 'block';
            } else {
                document.getElementById('bookingSummary').style.display = 'none';
            }
        }

        // Add event listeners for summary updates
        document.getElementById('passengers').addEventListener('input', updateBookingSummary);
        document.getElementById('bustype').addEventListener('change', updateBookingSummary);
        document.getElementById('datepick1').addEventListener('change', updateBookingSummary);
        document.getElementById('datepick2').addEventListener('change', updateBookingSummary);

        // Form validation
        document.getElementById('bookingForm').addEventListener('submit', function(e) {
            const origin = originSelect.value;
            const destination = destinationSelect.value;
            
            if (origin === destination) {
                e.preventDefault();
                alert('Please select different origin and destination locations.');
                return false;
            }
        });

        // Initialize
        updateTripType();
    </script>
</body>
</html>