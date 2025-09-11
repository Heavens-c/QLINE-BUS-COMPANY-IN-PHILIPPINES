<?php
// php_includes/admin_only.php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/connection.php';

function is_admin_session(mysqli $con): bool {
    // 1) Direct admin session flag (older flow)
    if (!empty($_SESSION['admin_email'])) return true;

    // 2) Member session with role=admin (if column exists)
    if (!empty($_SESSION['email'])) {
        $email = $_SESSION['email'];

        // Try members.role
        if ($stmt = $con->prepare("SELECT role FROM members WHERE email = ? LIMIT 1")) {
            $stmt->bind_param("s", $email);
            if ($stmt->execute() && ($res = $stmt->get_result()) && ($row = $res->fetch_assoc())) {
                if (isset($row['role']) && strtolower((string)$row['role']) === 'admin') {
                    $stmt->close();
                    return true;
                }
            }
            $stmt->close();
        }

        // Fallback: separate admins table
        if ($stmt = $con->prepare("SELECT 1 FROM information_schema.tables WHERE table_schema = DATABASE() AND table_name = 'admins'")) {
            if ($stmt->execute()) {
                $stmt->store_result();
                if ($stmt->num_rows > 0) {
                    $stmt->close();
                    if ($stmt2 = $con->prepare("SELECT 1 FROM admins WHERE email = ? LIMIT 1")) {
                        $stmt2->bind_param("s", $email);
                        $stmt2->execute();
                        $stmt2->store_result();
                        $ok = $stmt2->num_rows > 0;
                        $stmt2->close();
                        if ($ok) return true;
                    }
                } else {
                    $stmt->close();
                }
            } else {
                $stmt->close();
            }
        }
    }
    return false;
}

if (!is_admin_session($con)) {
    $_SESSION['login_error'] = 'You must be an admin to access that page.';
    header('Location: ' . rtrim(dirname(dirname($_SERVER['SCRIPT_NAME'] ?? '/')), '/') . '/signlog.php');
    exit;
}
