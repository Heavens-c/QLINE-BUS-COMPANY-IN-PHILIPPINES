<?php
// php_includes/otp_helper.php
require_once __DIR__ . '/connection.php';

function check_otp_table(mysqli $con): void {
    $create = "
    CREATE TABLE IF NOT EXISTS otp_verifications (
        id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
        contact_target VARCHAR(191) NOT NULL,
        code VARCHAR(10) NOT NULL,
        token VARCHAR(64) NOT NULL,
        action VARCHAR(32) NOT NULL,
        verified TINYINT UNSIGNED NOT NULL DEFAULT 0,
        expires_at DATETIME NOT NULL,
        created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        KEY ix_contact_target (contact_target),
        KEY ix_token (token)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
    try { $con->query($create); } catch (\Throwable $e) { /* ignore */ }
}

/**
 * Generates a new 6-digit OTP and returns array with ['code', 'token']
 */
function generate_otp(mysqli $con, string $contact_target, string $action = 'booking'): ?array {
    check_otp_table($con);

    $code = sprintf("%06d", random_int(100000, 999999));
    $token = bin2hex(random_bytes(16));
    
    // Default expiry: 5 minutes from now
    $minutes = (int)(getenv('OTP_EXPIRY_MINUTES') ?: ($_ENV['OTP_EXPIRY_MINUTES'] ?? 5));
    $expires = date('Y-m-d H:i:s', time() + ($minutes * 60));

    $sql = "INSERT INTO otp_verifications (contact_target, code, token, action, expires_at) VALUES (?, ?, ?, ?, ?)";
    $stmt = $con->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("sssss", $contact_target, $code, $token, $action, $expires);
        if ($stmt->execute()) {
            $stmt->close();
            return ['code' => $code, 'token' => $token];
        }
        $stmt->close();
    }
    return null;
}

/**
 * Send OTP via SMS API (Placeholder for Twilio, Semaphore, etc.)
 */
function send_otp_sms(string $mobile, string $code): bool {
    // --- INTEGRATION PLACEHOLDER ---
    // Example Semaphore API implementation:
    /*
    $ch = curl_init();
    $parameters = array(
        'apikey' => getenv('SMS_API_KEY') ?: 'YOUR_SEMAPHORE_API_KEY',
        'number' => $mobile,
        'message' => 'Your Dimple Star booking OTP code is: ' . $code . '. Valid for 5 minutes.',
        'sendername' => 'DIMPLESTAR'
    );
    curl_setopt($ch, CURLOPT_URL, 'https://semaphore.co/api/v4/messages');
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($parameters));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $output = curl_exec($ch);
    curl_close($ch);
    */
    
    // Simulate logging OTP for development/testing visibility
    error_log("[MOCK SMS OTP] Sent OTP code " . $code . " to mobile: " . $mobile);
    return true;
}

/**
 * Send OTP via Email API (Placeholder for PHPMailer, SendGrid, SMTP, etc.)
 */
function send_otp_email(string $email, string $code): bool {
    // --- INTEGRATION PLACEHOLDER ---
    /*
    $to = $email;
    $subject = "Dimple Star Transport - OTP Verification";
    $message = "Your OTP code is: " . $code . "\r\nValid for 5 minutes.";
    $headers = "From: no-reply@dimplestartransport.com" . "\r\n" .
               "Reply-To: no-reply@dimplestartransport.com" . "\r\n" .
               "X-Mailer: PHP/" . phpversion();
    mail($to, $subject, $message, $headers);
    */

    error_log("[MOCK EMAIL OTP] Sent OTP code " . $code . " to email: " . $email);
    return true;
}

/**
 * Verifies if the OTP is correct and not expired. Returns true/false.
 */
function verify_otp(mysqli $con, string $contact_target, string $code, string $token, string $action = 'booking'): bool {
    check_otp_table($con);
    
    $now = date('Y-m-d H:i:s');
    $sql = "SELECT id FROM otp_verifications 
            WHERE contact_target = ? AND code = ? AND token = ? AND action = ? AND verified = 0 AND expires_at > ? 
            LIMIT 1";
            
    $stmt = $con->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("sssss", $contact_target, $code, $token, $action, $now);
        $stmt->execute();
        $stmt->store_result();
        $num_rows = $stmt->num_rows;
        $stmt->close();
        
        if ($num_rows > 0) {
            // Mark it as verified so it cannot be used again
            $update = "UPDATE otp_verifications SET verified = 1 WHERE contact_target = ? AND code = ? AND token = ? AND action = ?";
            $uStmt = $con->prepare($update);
            if ($uStmt) {
                $uStmt->bind_param("ssss", $contact_target, $code, $token, $action);
                $uStmt->execute();
                $uStmt->close();
            }
            return true;
        }
    }
    return false;
}
