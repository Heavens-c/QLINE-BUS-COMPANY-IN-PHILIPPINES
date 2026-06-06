<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/php_includes/connection.php';
require_once __DIR__ . '/php_includes/otp_helper.php';

$hasAudit = file_exists(__DIR__ . '/php_includes/audit.php');
if ($hasAudit) require_once __DIR__ . '/php_includes/audit.php';

function e($s){ return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }

// Determine current step
$step = 1;
$error = '';
$success = false;
$routes = [];
$selected_route = null;
$booking_receipt = null;

// Developer Mode: Print OTP to screen for easy copy-paste during testing
$dev_otp_preview = '';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['search_routes'])) {
        // Step 1: Search routes
        $origin = trim($_POST['Origin'] ?? '');
        $destination = trim($_POST['Destination'] ?? '');
        $departure = trim($_POST['Departure'] ?? '');
        $no_of_pass = (int)($_POST['no_of_pass'] ?? 1);
        $way = (int)($_POST['way'] ?? 1);
        $bustype = trim($_POST['bustype'] ?? '');

        if ($origin === '' || $destination === '' || $departure === '' || $bustype === '') {
            $error = 'Please complete all fields in the route selection.';
        } elseif ($origin === $destination) {
            $error = 'Origin and Destination cannot be the same.';
        } else {
            // Prepared statement to search routes
            $stmt = $con->prepare("SELECT * FROM routes WHERE origin = ? AND destination = ? AND bustype = ?");
            if ($stmt) {
                $stmt->bind_param("sss", $origin, $destination, $bustype);
                $stmt->execute();
                $res = $stmt->get_result();
                while ($row = $res->fetch_assoc()) {
                    $routes[] = $row;
                }
                $stmt->close();
                
                if (empty($routes)) {
                    $error = 'No available buses found matching your criteria.';
                } else {
                    $step = 2; // Move to results/selection
                }
            } else {
                $error = 'Database query failed. Please try again.';
            }
        }
    } elseif (isset($_POST['select_route'])) {
        // Step 2: Route selected, proceed to passenger info
        $busid = (int)($_POST['busid'] ?? 0);
        $departure = trim($_POST['departure_date'] ?? '');
        $no_of_pass = (int)($_POST['no_of_pass'] ?? 1);
        $way = (int)($_POST['way'] ?? 1);

        $stmt = $con->prepare("SELECT * FROM routes WHERE busid = ?");
        if ($stmt) {
            $stmt->bind_param("i", $busid);
            $stmt->execute();
            $res = $stmt->get_result();
            $selected_route = $res->fetch_assoc();
            $stmt->close();

            if ($selected_route) {
                $step = 3; // Move to passenger details form
            } else {
                $error = 'Invalid route selected.';
            }
        } else {
            $error = 'Database query failed.';
        }
    } elseif (isset($_POST['request_otp'])) {
        // Step 3: Passenger details filled, request OTP
        $busid = (int)($_POST['busid'] ?? 0);
        $departure = trim($_POST['departure_date'] ?? '');
        $no_of_pass = (int)($_POST['no_of_pass'] ?? 1);
        $way = (int)($_POST['way'] ?? 1);
        
        $name = trim($_POST['rn'] ?? '');
        $address = trim($_POST['addr'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $contact = trim($_POST['cont'] ?? '');
        $seats_str = trim($_POST['seat'] ?? '');

        // Re-verify route details
        $stmt = $con->prepare("SELECT * FROM routes WHERE busid = ?");
        if ($stmt) {
            $stmt->bind_param("i", $busid);
            $stmt->execute();
            $res = $stmt->get_result();
            $selected_route = $res->fetch_assoc();
            $stmt->close();
        }

        // Validate details
        $seats = preg_split('/[\s,]+/', $seats_str);
        $seats = array_filter($seats); // remove empty elements
        
        if (!$selected_route) {
            $error = 'Invalid route details.';
            $step = 1;
        } elseif ($name === '' || $address === '' || $email === '' || $contact === '' || $seats_str === '') {
            $error = 'Please complete all passenger info fields and select your seat(s).';
            $step = 3;
        } elseif (count($seats) !== $no_of_pass) {
            $error = 'Please select exactly ' . $no_of_pass . ' seat(s) (currently selected: ' . count($seats) . ').';
            $step = 3;
        } else {
            // Generate OTP
            $otp_data = generate_otp($con, $email, 'booking');
            if ($otp_data) {
                // Mock sending SMS and Email
                send_otp_email($email, $otp_data['code']);
                send_otp_sms($contact, $otp_data['code']);

                // Store temporary booking data in Session
                $_SESSION['temp_booking'] = [
                    'busid' => $busid,
                    'departure_date' => $departure,
                    'no_of_pass' => $no_of_pass,
                    'way' => $way,
                    'name' => $name,
                    'address' => $address,
                    'email' => $email,
                    'contact' => $contact,
                    'seats' => $seats,
                    'otp_token' => $otp_data['token']
                ];
                
                // Show OTP preview in developer mode (using session to pass it)
                $_SESSION['dev_otp_preview'] = $otp_data['code'];
                $dev_otp_preview = $otp_data['code'];
                
                $step = 4; // Move to OTP entry step
            } else {
                $error = 'Failed to generate security OTP. Please try again.';
                $step = 3;
            }
        }
    } elseif (isset($_POST['verify_otp'])) {
        // Step 4: OTP verification submitted
        $otp_code = trim($_POST['otp_code'] ?? '');
        $temp = $_SESSION['temp_booking'] ?? null;

        if (!$temp) {
            $error = 'Session expired. Please restart the booking process.';
            $step = 1;
        } elseif ($otp_code === '') {
            $error = 'Please enter the verification OTP code.';
            $step = 4;
            if (isset($_SESSION['dev_otp_preview'])) $dev_otp_preview = $_SESSION['dev_otp_preview'];
        } else {
            // Verify OTP
            $verified = verify_otp($con, $temp['email'], $otp_code, $temp['otp_token'], 'booking');
            if ($verified) {
                // Clear temporary dev OTP
                unset($_SESSION['dev_otp_preview']);
                
                // Create pending booking records
                $ticket_ids = [];
                $con->begin_transaction();
                try {
                    $stmt = $con->prepare("SELECT * FROM routes WHERE busid = ?");
                    $stmt->bind_param("i", $temp['busid']);
                    $stmt->execute();
                    $route_info = $stmt->get_result()->fetch_assoc();
                    $stmt->close();

                    if (!$route_info) throw new Exception('Route no longer exists.');

                    $stmt = $con->prepare("INSERT INTO regs (name, address, mobile, email, bustype, origin, destination, price, seat_no, timetodep, travel_date, payment_status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending')");
                    if ($stmt) {
                        foreach ($temp['seats'] as $seat_no) {
                            $stmt->bind_param(
                                "sssssssssss",
                                $temp['name'],
                                $temp['address'],
                                $temp['contact'],
                                $temp['email'],
                                $route_info['bustype'],
                                $route_info['origin'],
                                $route_info['destination'],
                                $route_info['price'],
                                $seat_no,
                                $route_info['time'],
                                $temp['departure_date']
                            );
                            $stmt->execute();
                            $ticket_ids[] = $con->insert_id;
                        }
                        $stmt->close();
                        $con->commit();
                        
                        // Set session payment details
                        $_SESSION['pending_payment'] = [
                            'ticket_ids' => $ticket_ids,
                            'amount' => $route_info['price'] * $temp['no_of_pass'],
                            'email' => $temp['email'],
                            'name' => $temp['name'],
                            'contact' => $temp['contact'],
                            'route_name' => $route_info['origin'] . ' to ' . $route_info['destination'],
                            'departure_date' => $temp['departure_date']
                        ];
                        
                        unset($_SESSION['temp_booking']);
                        
                        // Redirect to payment gateway simulated checkout
                        header("Location: payment_checkout.php");
                        exit;

                    } else {
                        throw new Exception('Database statement preparation failed.');
                    }
                } catch (Exception $ex) {
                    $con->rollback();
                    $error = 'Booking saving failed: ' . $ex->getMessage();
                    $step = 4;
                }
            } else {
                $error = 'Invalid or expired OTP verification code.';
                $step = 4;
                if (isset($_SESSION['dev_otp_preview'])) $dev_otp_preview = $_SESSION['dev_otp_preview'];
            }
        }
    }
}

// Check if showing receipt step
if (isset($_GET['step']) && $_GET['step'] === 'receipt' && isset($_SESSION['last_receipt'])) {
    $booking_receipt = $_SESSION['last_receipt'];
    $step = 5;
    $success = true;
}

// Load occupied seats for seat selector if step 3
$occupied_seats = [];
if ($step === 3) {
    $r_stmt = $con->prepare("SELECT * FROM routes WHERE busid = ?");
    $r_stmt->bind_param("i", $busid);
    $r_stmt->execute();
    $selected_route = $r_stmt->get_result()->fetch_assoc();
    $r_stmt->close();

    if ($selected_route) {
        $occ_stmt = $con->prepare("SELECT seat_no FROM regs WHERE origin = ? AND destination = ? AND bustype = ? AND timetodep = ? AND travel_date = ? AND payment_status = 'paid'");
        if ($occ_stmt) {
            $occ_stmt->bind_param(
                "sssss",
                $selected_route['origin'],
                $selected_route['destination'],
                $selected_route['bustype'],
                $selected_route['time'],
                $departure
            );
            $occ_stmt->execute();
            $occ_res = $occ_stmt->get_result();
            while ($occ_row = $occ_res->fetch_assoc()) {
                $occupied_seats[] = trim($occ_row['seat_no']);
            }
            $occ_stmt->close();
        }
    }
}
?>
<!DOCTYPE html>
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

        .booking-form, .booking-card {
            background: var(--bg-primary);
            border-radius: var(--radius-lg);
            padding: 2.5rem;
            box-shadow: var(--shadow-lg);
            border: 1px solid var(--border-color);
            margin-bottom: 2rem;
        }

        .form-section {
            margin-bottom: 2.5rem;
        }

        .form-section h3 {
            color: var(--secondary-color);
            margin-bottom: 1.5rem;
            font-size: 1.25rem;
            font-weight: 600;
            border-bottom: 2px solid var(--primary-color);
            padding-bottom: 0.5rem;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 1.5rem;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
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
            background: var(--bg-secondary);
            color: var(--text-primary);
            transition: all 0.3s ease;
        }

        .form-input:focus, .form-select:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.1);
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
            background: rgba(245, 158, 11, 0.05);
        }

        .radio-group input[type="radio"] {
            margin: 0;
        }

        .radio-group.active {
            border-color: var(--primary-color);
            background: rgba(245, 158, 11, 0.1);
        }

        .submit-btn, .action-btn {
            width: 100%;
            padding: 1rem 2rem;
            background: linear-gradient(135deg, var(--secondary-color), #d97706);
            color: white;
            border: none;
            border-radius: var(--radius-md);
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-align: center;
            text-decoration: none;
            display: inline-block;
        }

        .submit-btn:hover, .action-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(245, 158, 11, 0.3);
        }

        .submit-btn:active, .action-btn:active {
            transform: translateY(0);
        }

        .alert-error {
            background-color: #fef2f2;
            border: 1px solid #fecaca;
            color: #991b1b;
            padding: 1rem;
            border-radius: var(--radius-md);
            margin-bottom: 1.5rem;
            font-weight: 500;
        }

        .alert-success {
            background-color: #f0fdf4;
            border: 1px solid #bbf7d0;
            color: #166534;
            padding: 1.5rem;
            border-radius: var(--radius-lg);
            margin-bottom: 2rem;
            font-weight: 500;
            text-align: center;
        }

        .route-indicator {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 1rem;
            margin: 1.5rem 0;
            padding: 1rem;
            background: rgba(245, 158, 11, 0.05);
            border-radius: var(--radius-md);
        }

        .route-point {
            padding: 0.5rem 1rem;
            background: var(--primary-color);
            color: var(--text-primary);
            border-radius: var(--radius-md);
            font-weight: 600;
        }

        .route-arrow {
            font-size: 1.5rem;
            color: var(--secondary-color);
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
            background: linear-gradient(135deg, var(--text-primary), var(--secondary-color));
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

        /* Results table */
        .results-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
            background: white;
            border-radius: var(--radius-md);
            overflow: hidden;
            box-shadow: var(--shadow-sm);
        }

        .results-table th {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: var(--text-primary);
            padding: 1rem;
            text-align: left;
            font-weight: 600;
        }

        .results-table td {
            padding: 1rem;
            border-bottom: 1px solid var(--border-color);
        }

        .results-table tr:hover {
            background-color: rgba(245, 158, 11, 0.02);
        }

        .btn-select {
            padding: 0.5rem 1rem;
            background-color: var(--primary-color);
            color: var(--text-primary);
            border: none;
            border-radius: var(--radius-sm);
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .btn-select:hover {
            background-color: var(--secondary-color);
            color: white;
        }

        /* Dev preview badge */
        .dev-badge {
            background-color: #fef3c7;
            border: 1px dashed #d97706;
            color: #92400e;
            padding: 0.75rem 1rem;
            border-radius: var(--radius-md);
            margin-bottom: 1.5rem;
            font-family: monospace;
            font-size: 0.95rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        /* Bus Layout Seat Selection Map Grid */
        .bus-grid {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 12px;
            max-width: 340px;
            margin: 1.5rem auto;
            background: var(--bg-secondary);
            padding: 24px;
            border-radius: var(--radius-lg);
            border: 3px solid var(--border-color);
            position: relative;
        }
        .bus-grid::before {
            content: 'FRONT / DRIVER';
            grid-column: span 5;
            text-align: center;
            font-weight: 800;
            font-size: 0.75rem;
            letter-spacing: 2px;
            color: var(--text-secondary);
            border-bottom: 2px solid var(--border-color);
            padding-bottom: 12px;
            margin-bottom: 12px;
        }
        .seat {
            background: white;
            border: 2px solid var(--border-color);
            border-radius: var(--radius-sm);
            height: 42px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.9rem;
            cursor: pointer;
            user-select: none;
            transition: all 0.2s ease;
            color: var(--text-primary);
        }
        .seat.occupied {
            background: #fee2e2;
            border-color: #fca5a5;
            color: #ef4444;
            cursor: not-allowed;
            pointer-events: none;
        }
        .seat.selected {
            background: var(--primary-color);
            border-color: var(--secondary-color);
            color: var(--text-primary);
            box-shadow: 0 0 8px rgba(245, 158, 11, 0.4);
        }
        .seat.aisle {
            visibility: hidden;
            cursor: default;
            pointer-events: none;
        }

        /* Receipt styling */
        .receipt {
            border: 2px dashed var(--border-color);
            background: #fff;
            padding: 2rem;
            border-radius: var(--radius-lg);
            margin-top: 1.5rem;
        }

        .receipt-header {
            text-align: center;
            border-bottom: 2px solid var(--border-color);
            padding-bottom: 1rem;
            margin-bottom: 1.5rem;
        }

        .receipt-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.75rem;
            font-size: 0.95rem;
        }

        .receipt-total {
            border-top: 2px solid var(--border-color);
            padding-top: 1rem;
            margin-top: 1rem;
            font-weight: 700;
            font-size: 1.2rem;
        }

        @media (max-width: 768px) {
            .booking-container {
                padding: 1rem;
            }
            .booking-form, .booking-card {
                padding: 1.5rem;
            }
            .form-row {
                grid-template-columns: 1fr;
                gap: 0;
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
                        <li><a href="routeschedule.php" class="nav-link">Routes & Schedules</a></li>
                        <li><a href="contact.php" class="nav-link">Contact</a></li>
                        <li><a href="book.php" class="nav-link active">Book Now</a></li>
                    </ul>
                    
                    <div class="user-menu">
                        <?php if (isset($_SESSION['email'])): ?>
                            <span class="user-welcome">Welcome, <?= htmlspecialchars($_SESSION['email'], ENT_QUOTES, 'UTF-8') ?>!</span>
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
            <div class="booking-container">

                <?php if ($error !== ''): ?>
                    <div class="alert-error"><?= e($error) ?></div>
                <?php endif; ?>

                <?php if ($step === 1): ?>
                    <!-- STEP 1: Search Form -->
                    <form action="book.php" method="post" class="booking-form" id="bookingForm">
                        <div class="form-section">
                            <h3>Trip Type</h3>
                            <div class="trip-type">
                                <label class="radio-group active" id="oneWayGroup">
                                    <input type="radio" name="way" value="1" id="oneWay" checked>
                                    <span>One Way</span>
                                </label>
                                <label class="radio-group" id="twoWayGroup">
                                    <input type="radio" name="way" value="2" id="twoWay">
                                    <span>Round Trip</span>
                                </label>
                            </div>
                        </div>

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
                                        <option value="Ali Mall Cubao">Ali Mall Cubao Terminal</option>
                                        <option value="Pasay">Pasay Terminal</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="destination" class="form-label">Destination</label>
                                    <select name="Destination" id="destination" class="form-select" required>
                                        <option value="">Select Destination</option>
                                        <option value="San Jose">San Jose</option>
                                        <option value="San Lazaro">San Lazaro Terminal</option>
                                        <option value="Espana">España Terminal</option>
                                        <option value="Alabang">Alabang Terminal</option>
                                        <option value="Cabuyao">Cabuyao Terminal</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="route-indicator" id="routeIndicator" style="display: none;">
                                <div class="route-point" id="originPoint">-</div>
                                <div class="route-arrow">→</div>
                                <div class="route-point" id="destinationPoint">-</div>
                            </div>
                        </div>

                        <div class="form-section">
                            <h3>Travel Details</h3>
                            <div class="form-grid">
                                <div class="form-group">
                                    <label for="passengers" class="form-label">Number of Passengers</label>
                                    <input type="number" name="no_of_pass" id="passengers" class="form-input" min="1" max="10" value="1" required>
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

                        <div class="form-section">
                            <h3>Travel Dates</h3>
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="datepick1" class="form-label">Departure Date</label>
                                    <input type="date" id="datepick1" name="Departure" class="form-input" required min="<?= date('Y-m-d') ?>">
                                </div>
                                <div class="form-group">
                                    <label for="datepick2" class="form-label">Return Date</label>
                                    <input type="date" id="datepick2" name="Return" class="form-input" disabled>
                                </div>
                            </div>
                        </div>

                        <div class="submit-section">
                            <button type="submit" name="search_routes" class="submit-btn">
                                <span>Search Routes</span>
                            </button>
                        </div>
                    </form>

                <?php elseif ($step === 2): ?>
                    <!-- STEP 2: Display Results -->
                    <div class="booking-card">
                        <h3>Available Trips</h3>
                        <p style="color: var(--text-secondary); margin-bottom: 1.5rem;">
                            Showing routes from <strong><?= e($origin) ?></strong> to <strong><?= e($destination) ?></strong> (<?= e($bustype) ?>)
                        </p>

                        <table class="results-table">
                            <thead>
                                <tr>
                                    <th>Origin</th>
                                    <th>Destination</th>
                                    <th>Departure Time</th>
                                    <th>Bus Type</th>
                                    <th>Price</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($routes as $route): ?>
                                    <tr>
                                        <td><?= e($route['origin']) ?></td>
                                        <td><?= e($route['destination']) ?></td>
                                        <td><?= e($route['time']) ?></td>
                                        <td><?= e($route['bustype']) ?></td>
                                        <td>₱<?= number_format((float)$route['price'], 2) ?></td>
                                        <td>
                                            <form action="book.php" method="post">
                                                <input type="hidden" name="busid" value="<?= (int)$route['busid'] ?>">
                                                <input type="hidden" name="departure_date" value="<?= e($departure) ?>">
                                                <input type="hidden" name="no_of_pass" value="<?= (int)$no_of_pass ?>">
                                                <input type="hidden" name="way" value="<?= (int)$way ?>">
                                                <button type="submit" name="select_route" class="btn-select">Book</button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>

                        <div style="margin-top: 2rem; text-align: center;">
                            <a href="book.php" class="btn btn-secondary">← Back to Search</a>
                        </div>
                    </div>

                <?php elseif ($step === 3): ?>
                    <!-- STEP 3: Passenger Info & Seat Selection -->
                    <div class="booking-card">
                        <h3>Passenger & Seat Details</h3>
                        <div style="background: var(--bg-secondary); padding: 1rem; border-radius: var(--radius-md); margin-bottom: 2rem; font-size: 0.95rem;">
                            <strong>Selected Journey:</strong> <?= e($selected_route['origin']) ?> → <?= e($selected_route['destination']) ?><br>
                            <strong>Bus Type:</strong> <?= e($selected_route['bustype']) ?> | <strong>Departure Time:</strong> <?= e($selected_route['time']) ?><br>
                            <strong>Departure Date:</strong> <?= e($departure) ?> | <strong>Passengers:</strong> <?= (int)$no_of_pass ?><br>
                            <strong>Price per Seat:</strong> ₱<?= number_format((float)$selected_route['price'], 2) ?>
                        </div>

                        <form action="book.php" method="post" id="detailsForm">
                            <input type="hidden" name="busid" value="<?= (int)$selected_route['busid'] ?>">
                            <input type="hidden" name="departure_date" value="<?= e($departure) ?>">
                            <input type="hidden" name="no_of_pass" value="<?= (int)$no_of_pass ?>">
                            <input type="hidden" name="way" value="<?= (int)$way ?>">
                            <input type="hidden" name="seat" id="seat" value="">

                            <div class="form-group">
                                <label for="rn" class="form-label">Full Name *</label>
                                <input type="text" name="rn" id="rn" class="form-input" placeholder="Enter traveler/contact person full name" required>
                            </div>

                            <div class="form-group">
                                <label for="addr" class="form-label">Address *</label>
                                <input type="text" name="addr" id="addr" class="form-input" placeholder="Enter contact address" required>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label for="email" class="form-label">Email Address *</label>
                                    <input type="email" name="email" id="email" class="form-input" placeholder="traveler@example.com" required>
                                </div>
                                <div class="form-group">
                                    <label for="cont" class="form-label">Contact Number *</label>
                                    <input type="text" name="cont" id="cont" class="form-input" placeholder="Mobile or Phone number" required>
                                </div>
                            </div>

                            <!-- Interactive Seat Selection Map -->
                            <div class="form-group" style="margin-top: 2rem;">
                                <label class="form-label">Select Your Seat(s) *</label>
                                <p style="font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 1rem;">
                                    Please choose exactly <strong><?= (int)$no_of_pass ?></strong> seat(s).
                                </p>
                                
                                <div style="display: flex; justify-content: center; gap: 2rem; margin-bottom: 1.5rem; font-size: 0.85rem; font-weight: 600;">
                                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                                        <div style="width: 20px; height: 20px; border: 2px solid var(--border-color); background: white; border-radius: 4px;"></div>
                                        <span>Available</span>
                                    </div>
                                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                                        <div style="width: 20px; height: 20px; background: #fee2e2; border: 2px solid #fca5a5; border-radius: 4px;"></div>
                                        <span>Occupied</span>
                                    </div>
                                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                                        <div style="width: 20px; height: 20px; background: var(--primary-color); border: 2px solid var(--secondary-color); border-radius: 4px;"></div>
                                        <span>Selected</span>
                                    </div>
                                </div>
                                
                                <div class="bus-grid">
                                    <?php
                                    // 40 Seat Layout (10 rows, 4 seats per row, column 2 is Aisle)
                                    for ($row = 0; $row < 10; $row++) {
                                        for ($col = 0; $col < 5; $col++) {
                                            if ($col === 2) {
                                                echo '<div class="seat aisle"></div>';
                                            } else {
                                                // Seat index: 1 to 40
                                                $seat_num = ($row * 4) + ($col < 2 ? $col + 1 : $col);
                                                $is_occupied = in_array((string)$seat_num, $occupied_seats);
                                                $occupied_class = $is_occupied ? 'occupied' : '';
                                                echo '<div class="seat ' . $occupied_class . '" data-seat="' . $seat_num . '" onclick="toggleSeat(this)">' . $seat_num . '</div>';
                                            }
                                        }
                                    }
                                    ?>
                                </div>
                                <div style="text-align: center; margin-top: 1rem; font-weight: 700; color: var(--secondary-color); font-size: 1rem;">
                                    Selected Seat(s): <span id="selectedSeatsLabel" style="background: white; padding: 4px 10px; border-radius: var(--radius-sm); border: 1px solid var(--border-color); margin-left: 0.5rem;">None</span>
                                </div>
                            </div>

                            <div class="submit-section" style="display: flex; gap: 1rem; margin-top: 2rem;">
                                <a href="book.php" class="btn btn-secondary" style="flex: 1;">Cancel</a>
                                <button type="submit" name="request_otp" class="submit-btn" style="flex: 2;" onclick="return validateSeatsForm();">Send Verification OTP</button>
                            </div>
                        </form>
                    </div>

                <?php elseif ($step === 4): ?>
                    <!-- STEP 4: OTP Verification Code Entry -->
                    <div class="booking-card">
                        <h3>Security Verification</h3>
                        <p style="color: var(--text-secondary); margin-bottom: 1.5rem;">
                            To secure your reservation, we have sent a 6-digit verification code (OTP) to your email <strong><?= e($temp['email'] ?? '') ?></strong> and mobile number <strong><?= e($temp['contact'] ?? '') ?></strong>.
                        </p>

                        <?php if ($dev_otp_preview !== ''): ?>
                            <div class="dev-badge">
                                <strong>[Dev Mode Helper]</strong> Code sent to APIs: <span style="background: white; padding: 2px 6px; border-radius: 4px; font-weight: bold; border: 1px solid #d97706;"><?= e($dev_otp_preview) ?></span>
                            </div>
                        <?php endif; ?>

                        <form action="book.php" method="post">
                            <div class="form-group">
                                <label for="otp_code" class="form-label">Enter 6-Digit OTP Code</label>
                                <input type="text" name="otp_code" id="otp_code" class="form-input" style="font-size: 1.5rem; text-align: center; letter-spacing: 0.5rem; font-weight: bold;" maxlength="6" required placeholder="000000" autocomplete="off">
                            </div>

                            <div class="submit-section" style="display: flex; gap: 1rem; margin-top: 2rem;">
                                <a href="book.php" class="btn btn-secondary" style="flex: 1;">Cancel Booking</a>
                                <button type="submit" name="verify_otp" class="submit-btn" style="flex: 2;">Verify & Proceed to Payment</button>
                            </div>
                        </form>
                    </div>

                <?php elseif ($step === 5 && $success): ?>
                    <!-- STEP 5: Success Receipt -->
                    <div class="alert-success">
                        <svg viewBox="0 0 24 24" width="32" height="32" fill="none" stroke="currentColor" stroke-width="2.5" style="display: inline-block; margin-bottom: 0.5rem;">
                            <path d="M9 11l3 3l8-8"/><path d="M21 12c0 4.97-4.03 9-9 9s-9-4.03-9-9s4.03-9 9-9c1.66 0 3.22.45 4.56 1.24"/>
                        </svg>
                        <h3>Payment Completed Successfully!</h3>
                        <p style="margin: 0.5rem 0 0 0;">Thank you for choosing Dimple Star Transport. Your ticket is active and paid.</p>
                    </div>

                    <div class="receipt">
                        <div class="receipt-header">
                            <h2>Booking Receipt & E-Ticket</h2>
                            <p style="color: var(--text-secondary);">Dimple Star Transport Ticket Voucher</p>
                        </div>

                        <div class="receipt-row">
                            <span><strong>Traveler Name:</strong></span>
                            <span><?= e($booking_receipt['name']) ?></span>
                        </div>
                        <div class="receipt-row">
                            <span><strong>Email:</strong></span>
                            <span><?= e($booking_receipt['email']) ?></span>
                        </div>
                        <div class="receipt-row">
                            <span><strong>Contact No:</strong></span>
                            <span><?= e($booking_receipt['contact']) ?></span>
                        </div>
                        <div class="receipt-row">
                            <span><strong>Route:</strong></span>
                            <span><?= e($booking_receipt['origin']) ?> → <?= e($booking_receipt['destination']) ?></span>
                        </div>
                        <div class="receipt-row">
                            <span><strong>Bus Type:</strong></span>
                            <span><?= e($booking_receipt['bustype']) ?></span>
                        </div>
                        <div class="receipt-row">
                            <span><strong>Departure Date:</strong></span>
                            <span><?= e($booking_receipt['departure']) ?></span>
                        </div>
                        <div class="receipt-row">
                            <span><strong>Departure Time:</strong></span>
                            <span><?= e($booking_receipt['time']) ?></span>
                        </div>
                        <div class="receipt-row">
                            <span><strong>Seat Number(s):</strong></span>
                            <span><?= e(implode(', ', $booking_receipt['seats'])) ?></span>
                        </div>
                        <div class="receipt-row">
                            <span><strong>E-Ticket Codes:</strong></span>
                            <span>#<?= implode(', #', $booking_receipt['tickets']) ?></span>
                        </div>
                        <div class="receipt-row">
                            <span><strong>Payment Reference:</strong></span>
                            <span style="font-family: monospace; font-weight: 600;"><?= e($booking_receipt['payment_ref']) ?></span>
                        </div>
                        <div class="receipt-row">
                            <span><strong>Payment Status:</strong></span>
                            <span style="color: #166534; font-weight: 700;">PAID</span>
                        </div>

                        <div class="receipt-row receipt-total">
                            <span>Total Amount Paid:</span>
                            <span>₱<?= number_format((float)$booking_receipt['total_price'], 2) ?></span>
                        </div>
                    </div>

                    <div style="margin-top: 2rem; text-align: center;">
                        <a href="book.php" class="action-btn">Book Another Journey</a>
                    </div>
                <?php endif; ?>

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
    <script>
        // JS helpers
        const tripTypeRadios = document.getElementsByName('way');
        const returnDateInput = document.getElementById('datepick2');

        function updateTripTypeFields() {
            let isRoundTrip = false;
            for(let radio of tripTypeRadios) {
                if(radio.checked && radio.value === '2') {
                    isRoundTrip = true;
                }
            }
            if(isRoundTrip) {
                if (returnDateInput) {
                    returnDateInput.removeAttribute('disabled');
                    returnDateInput.setAttribute('required', 'required');
                }
                document.getElementById('twoWayGroup')?.classList.add('active');
                document.getElementById('oneWayGroup')?.classList.remove('active');
            } else {
                if (returnDateInput) {
                    returnDateInput.setAttribute('disabled', 'disabled');
                    returnDateInput.removeAttribute('required');
                }
                document.getElementById('oneWayGroup')?.classList.add('active');
                document.getElementById('twoWayGroup')?.classList.remove('active');
            }
        }

        for(let radio of tripTypeRadios) {
            radio.addEventListener('change', updateTripTypeFields);
        }

        // Route indicator
        const originSelect = document.getElementById('origin');
        const destinationSelect = document.getElementById('destination');
        const routeIndicator = document.getElementById('routeIndicator');
        const originPoint = document.getElementById('originPoint');
        const destinationPoint = document.getElementById('destinationPoint');

        function updateRouteIndicator() {
            if(!originSelect || !destinationSelect || !routeIndicator) return;
            const origin = originSelect.options[originSelect.selectedIndex]?.text;
            const destination = destinationSelect.options[destinationSelect.selectedIndex]?.text;
            
            if (originSelect.value && destinationSelect.value && originSelect.value !== destinationSelect.value) {
                originPoint.textContent = origin;
                destinationPoint.textContent = destination;
                routeIndicator.style.display = 'flex';
            } else {
                routeIndicator.style.display = 'none';
            }
        }

        originSelect?.addEventListener('change', updateRouteIndicator);
        destinationSelect?.addEventListener('change', updateRouteIndicator);

        // Form validation
        document.getElementById('bookingForm')?.addEventListener('submit', function(e) {
            if (originSelect.value === destinationSelect.value) {
                e.preventDefault();
                alert('Please select different origin and destination locations.');
                return false;
            }
        });

        // Seat Selector Logic
        const maxSeats = <?= ($step === 3) ? (int)$no_of_pass : 1 ?>;
        let selectedSeats = [];

        function toggleSeat(element) {
            if (element.classList.contains('occupied')) return;
            
            const seatNum = element.dataset.seat;
            const index = selectedSeats.indexOf(seatNum);
            
            if (index > -1) {
                // Unselect
                selectedSeats.splice(index, 1);
                element.classList.remove('selected');
            } else {
                // Select
                if (selectedSeats.length >= maxSeats) {
                    // Remove oldest
                    const oldSeat = selectedSeats.shift();
                    const oldElement = document.querySelector(`.seat[data-seat="${oldSeat}"]`);
                    if (oldElement) oldElement.classList.remove('selected');
                }
                selectedSeats.push(seatNum);
                element.classList.add('selected');
            }
            
            // Update hidden field and label
            const seatInput = document.getElementById('seat');
            if (seatInput) seatInput.value = selectedSeats.join(' ');
            
            const label = document.getElementById('selectedSeatsLabel');
            if (label) label.textContent = selectedSeats.length > 0 ? selectedSeats.join(', ') : 'None';
        }

        function validateSeatsForm() {
            if (selectedSeats.length !== maxSeats) {
                alert('Please select exactly ' + maxSeats + ' seat(s) on the bus layout.');
                return false;
            }
            return true;
        }
    </script>
</body>
</html>
