<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/php_includes/connection.php';

$temp = $_SESSION['pending_payment'] ?? null;
if (!$temp) {
    header("Location: book.php");
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['pay_simulation'])) {
    $method = trim($_POST['payment_method'] ?? 'gcash');
    $payment_ref = 'TXN-' . strtoupper(bin2hex(random_bytes(6)));

    $con->begin_transaction();
    try {
        $stmt = $con->prepare("UPDATE regs SET payment_status = 'paid', payment_ref = ? WHERE ticket = ?");
        if ($stmt) {
            foreach ($temp['ticket_ids'] as $ticket_id) {
                $stmt->bind_param("si", $payment_ref, $ticket_id);
                $stmt->execute();
            }
            $stmt->close();
            $con->commit();

            // Load first ticket info to generate receipt details
            $stmt2 = $con->prepare("SELECT * FROM regs WHERE ticket = ? LIMIT 1");
            $first_id = $temp['ticket_ids'][0];
            $stmt2->bind_param("i", $first_id);
            $stmt2->execute();
            $reg = $stmt2->get_result()->fetch_assoc();
            $stmt2->close();

            // Collect seat numbers from all tickets
            $seats = [];
            $stmt3 = $con->prepare("SELECT seat_no FROM regs WHERE ticket = ?");
            foreach ($temp['ticket_ids'] as $t_id) {
                $stmt3->bind_param("i", $t_id);
                $stmt3->execute();
                $s_row = $stmt3->get_result()->fetch_assoc();
                if ($s_row) $seats[] = $s_row['seat_no'];
            }
            $stmt3->close();

            // Store receipt details in session
            $_SESSION['last_receipt'] = [
                'tickets' => $temp['ticket_ids'],
                'name' => $reg['name'] ?? $temp['name'],
                'email' => $reg['email'] ?? $temp['email'],
                'contact' => $reg['mobile'] ?? $temp['contact'],
                'origin' => $reg['origin'] ?? '',
                'destination' => $reg['destination'] ?? '',
                'bustype' => $reg['bustype'] ?? '',
                'time' => $reg['timetodep'] ?? '',
                'price' => $reg['price'] ?? 0,
                'seats' => $seats,
                'departure' => date('Y-m-d'), // current date simulation
                'payment_ref' => $payment_ref,
                'total_price' => $temp['amount']
            ];

            unset($_SESSION['pending_payment']);

            // Redirect to receipt
            header("Location: book.php?step=receipt");
            exit;
        } else {
            throw new Exception("SQL statement prepare failed.");
        }
    } catch (Exception $ex) {
        $con->rollback();
        $error = 'Payment processing failed: ' . $ex->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Secure Checkout - Dimple Star Transport</title>
    <link rel="stylesheet" type="text/css" href="style/modern-style.css" />
    <link rel="icon" href="images/icon.ico" type="image/x-icon">
    <style>
        .checkout-container {
            max-width: 600px;
            margin: 4rem auto;
            padding: 0 1rem;
        }
        .checkout-card {
            background: white;
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-lg);
            border: 1px solid var(--border-color);
            overflow: hidden;
        }
        .checkout-header {
            background: linear-gradient(135deg, var(--bg-dark), #1f2937);
            color: white;
            padding: 2rem;
            text-align: center;
        }
        .checkout-body {
            padding: 2.5rem;
        }
        .gateway-logo {
            font-size: 1.5rem;
            font-weight: 800;
            letter-spacing: -0.05em;
            color: #ffc75e;
            margin-bottom: 0.5rem;
        }
        .summary-box {
            background-color: var(--bg-secondary);
            border: 1px solid var(--border-color);
            border-radius: var(--radius-md);
            padding: 1.25rem;
            margin-bottom: 2rem;
            font-size: 0.95rem;
        }
        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.5rem;
        }
        .summary-total {
            border-top: 1px solid var(--border-color);
            padding-top: 0.75rem;
            margin-top: 0.75rem;
            font-weight: 700;
            font-size: 1.15rem;
            color: var(--secondary-color);
        }
        .payment-methods {
            display: grid;
            gap: 1rem;
            margin-bottom: 2rem;
        }
        .method-option {
            border: 2px solid var(--border-color);
            border-radius: var(--radius-md);
            padding: 1.25rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        .method-option:hover {
            border-color: var(--primary-color);
            background-color: rgba(245, 158, 11, 0.02);
        }
        .method-option input[type="radio"] {
            margin: 0;
            width: 1.25rem;
            height: 1.25rem;
        }
        .method-option.selected {
            border-color: var(--secondary-color);
            background-color: rgba(245, 158, 11, 0.05);
        }
        .method-details {
            display: flex;
            flex-direction: column;
        }
        .method-title {
            font-weight: 700;
            color: var(--text-primary);
        }
        .method-desc {
            font-size: 0.85rem;
            color: var(--text-secondary);
        }
        .pay-button {
            width: 100%;
            padding: 1rem;
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            color: white;
            border: none;
            border-radius: var(--radius-md);
            font-size: 1.1rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s ease;
            text-align: center;
            box-shadow: 0 4px 6px rgba(37, 99, 235, 0.2);
        }
        .pay-button:hover {
            transform: translateY(-1px);
            box-shadow: 0 8px 15px rgba(37, 99, 235, 0.3);
        }
        .pay-button:active {
            transform: translateY(0);
        }
        .cancel-link {
            display: block;
            text-align: center;
            margin-top: 1.5rem;
            color: var(--text-secondary);
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 500;
        }
        .cancel-link:hover {
            color: var(--text-primary);
            text-decoration: underline;
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
    </style>
</head>
<body style="background-color: var(--bg-secondary);">

    <div class="checkout-container">
        <div class="checkout-card">
            <div class="checkout-header">
                <div class="gateway-logo">Xendit / Paymongo Payment Wall</div>
                <p style="margin: 0; opacity: 0.8; font-size: 0.9rem;">Secure Transaction Simulation</p>
            </div>
            
            <div class="checkout-body">
                <?php if ($error !== ''): ?>
                    <div class="alert-error"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>

                <div class="summary-box">
                    <h4 style="margin-top: 0; margin-bottom: 0.75rem; color: var(--text-primary);">Order Summary</h4>
                    <div class="summary-row">
                        <span>Description:</span>
                        <span>Booking for <?= htmlspecialchars($temp['route_name']) ?></span>
                    </div>
                    <div class="summary-row">
                        <span>Passenger:</span>
                        <span><?= htmlspecialchars($temp['name']) ?></span>
                    </div>
                    <div class="summary-row">
                        <span>Ticket Count:</span>
                        <span><?= count($temp['ticket_ids']) ?> tickets</span>
                    </div>
                    <div class="summary-row summary-total">
                        <span>Total Due:</span>
                        <span>₱<?= number_format((float)$temp['amount'], 2) ?></span>
                    </div>
                </div>

                <form method="post" id="paymentForm">
                    <h4 style="margin-top: 0; margin-bottom: 1rem; color: var(--text-primary);">Select Payment Method</h4>
                    
                    <div class="payment-methods">
                        <label class="method-option selected" id="opt-gcash">
                            <input type="radio" name="payment_method" value="gcash" checked onclick="selectMethod('gcash')">
                            <div class="method-details">
                                <span class="method-title">GCash</span>
                                <span class="method-desc">Pay instantly using your GCash e-wallet.</span>
                            </div>
                        </label>
                        
                        <label class="method-option" id="opt-maya">
                            <input type="radio" name="payment_method" value="maya" onclick="selectMethod('maya')">
                            <div class="method-details">
                                <span class="method-title">Maya</span>
                                <span class="method-desc">Pay using your Maya wallet app.</span>
                            </div>
                        </label>
                        
                        <label class="method-option" id="opt-card">
                            <input type="radio" name="payment_method" value="card" onclick="selectMethod('card')">
                            <div class="method-details">
                                <span class="method-title">Credit / Debit Card</span>
                                <span class="method-desc">Visa, Mastercard, or JCB credit cards.</span>
                            </div>
                        </label>
                    </div>

                    <button type="submit" name="pay_simulation" class="pay-button">
                        Authorize Payment of ₱<?= number_format((float)$temp['amount'], 2) ?>
                    </button>
                </form>

                <a href="book.php" class="cancel-link">← Cancel & Return to Booking</a>
            </div>
        </div>
    </div>

    <script>
        function selectMethod(methodId) {
            document.querySelectorAll('.method-option').forEach(el => {
                el.classList.remove('selected');
            });
            document.getElementById('opt-' + methodId).classList.add('selected');
        }
    </script>
</body>
</html>
