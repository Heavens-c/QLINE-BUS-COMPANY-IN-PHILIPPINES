<?php
// login.php
if (session_status() === PHP_SESSION_NONE) session_start();
require __DIR__ . '/php_includes/connection.php';

// Optional audit logging
$hasAudit = file_exists(__DIR__ . '/php_includes/audit.php');
if ($hasAudit) require __DIR__ . '/php_includes/audit.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: signlog.php');
    exit;
}

// Support both old and new field names
$email    = trim($_POST['email']        ?? $_POST['login_email']    ?? '');
$password =        $_POST['password']     ?? $_POST['login_password'] ?? '';

if (!filter_var($email, FILTER_VALIDATE_EMAIL) || $password === '') {
    $_SESSION['login_error'] = 'Please enter a valid email and password.';
    if ($hasAudit) audit_log($con, $email ?: 'unknown', 'login_failed');
    header('Location: signlog.php');
    exit;
}

// Look up user by email
$stmt = $con->prepare("SELECT id, fname, lname, email, password, role
                       FROM members
                       WHERE email = ?
                       LIMIT 1");
if (!$stmt) {
    $_SESSION['login_error'] = 'Server error. Please try again.';
    header('Location: signlog.php');
    exit;
}
$stmt->bind_param('s', $email);
$stmt->execute();
$res  = $stmt->get_result();
$user = $res->fetch_assoc();
$stmt->close();

// Verify bcrypt password
if (!$user || !password_verify($password, $user['password'])) {
    $_SESSION['login_error'] = 'Invalid email or password.';
    if ($hasAudit) audit_log($con, $email, 'login_failed');
    header('Location: signlog.php');
    exit;
}

// Optional: rehash if algorithm/cost changed
if (password_needs_rehash($user['password'], PASSWORD_BCRYPT)) {
    $newHash = password_hash($password, PASSWORD_BCRYPT);
    if ($u = $con->prepare("UPDATE members SET password=? WHERE id=?")) {
        $u->bind_param('si', $newHash, $user['id']);
        $u->execute();
        $u->close();
    }
}

// Success: set session
session_regenerate_id(true);
$_SESSION['user_id'] = (int)$user['id'];
$_SESSION['email']   = $user['email'];
$_SESSION['name']    = trim(($user['fname'] ?? '') . ' ' . ($user['lname'] ?? ''));
$_SESSION['role']    = $user['role'] ?: 'user';

if ($hasAudit) audit_log($con, $user['email'], 'login_success');

// Redirect: admin vs normal user
$adminRedirect = '/admin/dashboard.php';
if (!file_exists(__DIR__ . $adminRedirect)) {
    $adminRedirect = '/admin/dash.php';
    if (!file_exists(__DIR__ . $adminRedirect)) $adminRedirect = '/admin/';
}

if (strtolower($_SESSION['role']) === 'admin') {
    header('Location: ' . $adminRedirect);
} else {
    header('Location: /index.php');
}
exit;
