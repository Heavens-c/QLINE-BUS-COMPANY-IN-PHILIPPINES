<?php
if (session_status() === PHP_SESSION_NONE) session_start();

require __DIR__ . '/php_includes/connection.php';
$hasAudit = file_exists(__DIR__ . '/php_includes/audit.php');
if ($hasAudit) require __DIR__ . '/php_includes/audit.php';

$errors = [];
$successful = '';
$val_fname = $_POST['fname'] ?? '';
$val_lname = $_POST['lname'] ?? '';
$val_email = $_POST['email'] ?? '';

function e($s){ return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // --- Validate signup ---
    $fname = trim($_POST['fname'] ?? '');
    $lname = trim($_POST['lname'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm  = $_POST['confirm_password'] ?? '';

    if ($fname === '') $errors['fname'] = '* First Name is required.';
    if ($lname === '') $errors['lname'] = '* Last Name is required.';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors['email'] = '* Not a valid e-mail address.';
    if (strlen($password) < 8) $errors['password'] = '* Password must contain at least 8 characters.';
    if ($password !== $confirm) $errors['confirm_password'] = '* Passwords do not match.';

    // Duplicate email
    if (!$errors) {
        $stmt = $con->prepare("SELECT id FROM members WHERE email = ? LIMIT 1");
        if ($stmt) {
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $stmt->store_result();
            if ($stmt->num_rows > 0) $errors['email'] = 'Email address is unavailable.';
            $stmt->close();
        } else {
            $errors['email'] = 'Server error. Please try again later.';
        }
    }

    // Insert
    if (!$errors) {
        $hash = password_hash($password, PASSWORD_BCRYPT);

        // Try with role column first
        $stmt = $con->prepare("INSERT INTO members (fname, lname, email, password, role) VALUES (?, ?, ?, ?, 'user')");
        if ($stmt) {
            $stmt->bind_param("ssss", $fname, $lname, $email, $hash);
        } else {
            // Fallback if 'role' doesn't exist
            $stmt = $con->prepare("INSERT INTO members (fname, lname, email, password) VALUES (?, ?, ?, ?)");
            if ($stmt) $stmt->bind_param("ssss", $fname, $lname, $email, $hash);
        }

        if ($stmt && $stmt->execute()) {
            if ($hasAudit) audit_log($con, $email, 'signup_success');
            $successful = '<div class="success-message">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 11l3 3l8-8"/><path d="M21 12c0 4.97-4.03 9-9 9s-9-4.03-9-9s4.03-9 9-9c1.66 0 3.22.45 4.56 1.24"/></svg>
                Account created successfully! You can now log in.
            </div>';
            $val_fname = $val_lname = $val_email = '';
        } else {
            $errors['email'] = 'Could not create account: ' . e($stmt ? $stmt->error : $con->error);
        }
        if ($stmt) $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Sign Up / Login - Dimple Star Transport</title>
<link rel="stylesheet" type="text/css" href="style/modern-style.css" />
<link rel="icon" href="images/icon.ico" type="image/x-icon">
<script src="js/modern.js" defer></script>
<style>
  .auth-container { min-height: 100vh; display: flex; flex-direction: column; }
  .auth-main { flex: 1; display: flex; align-items: center; justify-content: center; padding: 2rem 0;
               background: linear-gradient(135deg, var(--surface-light) 0%, var(--surface) 100%); }
  .auth-wrapper { width: 100%; max-width: 1000px; margin: 0 auto; padding: 0 1rem; }
  .auth-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 3rem; background: #fff; border-radius: var(--radius-lg);
               box-shadow: var(--shadow-xl); overflow: hidden; }
  @media (max-width: 768px){ .auth-grid{ grid-template-columns: 1fr; gap: 0; } }
  .auth-section { padding: 2.5rem; }
  .auth-header { text-align: center; margin-bottom: 2rem; }
  .auth-header h2 { font-size: 1.75rem; font-weight: 700; color: var(--text-primary); margin-bottom: .5rem; }
  .auth-header p { color: var(--text-secondary); font-size: .95rem; }
  .form-group { margin-bottom: 1.5rem; }
  .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
  .form-label { display: block; font-weight: 500; color: var(--text-primary); margin-bottom: .5rem; font-size: .875rem; }
  .form-input { width: 100%; padding: .75rem 1rem; border: 2px solid var(--border-light); border-radius: var(--radius-md);
                font-size: .95rem; transition: all .2s ease; background: #fff; }
  .form-input:focus { outline: none; border-color: var(--primary-color); box-shadow: 0 0 0 3px rgba(59,130,246,.1); }
  .form-input::placeholder { color: var(--text-muted); }
  .error-message { color: var(--error-color); font-size: .875rem; margin-top: .5rem; display: flex; align-items: center; gap: .5rem; }
  .error-message svg { width: 1rem; height: 1rem; flex-shrink: 0; }
  .success-message { background: #f0fdf4; border: 1px solid #bbf7d0; color: #166534; padding: 1rem; border-radius: var(--radius-md);
                     font-size: .95rem; display: flex; align-items: center; gap: .75rem; margin-bottom: 1.5rem; }
  .success-message svg { width: 1.25rem; height: 1.25rem; }
  /* login panel background */
  .login-section { background: linear-gradient(135deg, var(--primary-color), var(--primary-dark)); color: #fff; position: relative; overflow: hidden; }
  .login-section::before {
    content: ''; position: absolute; top: -50%; right: -50%; width: 200%; height: 200%;
    background: repeating-linear-gradient(45deg, transparent, transparent 10px, rgba(255,255,255,.05) 10px, rgba(255,255,255,.05) 20px);
    animation: float 20s ease-in-out infinite;
    z-index: 0;             /* so it stays behind */
    pointer-events: none;   /* never block clicks */
  }
  .login-section > * { position: relative; z-index: 1; } /* actual content above overlay */
  @keyframes float { 0%,100%{ transform: translateY(0) rotate(0deg); } 50%{ transform: translateY(-10px) rotate(1deg); } }
  .login-section .auth-header h2, .login-section .auth-header p { color: #fff; }
  .login-section .form-input { background: #fff !important; border-color: rgba(255,255,255,.5) !important; color: var(--text-primary) !important; }
  .login-section .form-input:focus { background: #fff !important; border-color: rgba(255,255,255,.8) !important; box-shadow: 0 0 0 3px rgba(255,255,255,.2) !important; }
  .login-section .form-input::placeholder { color: var(--text-muted) !important; }
  .login-section .form-label { color: rgba(255,255,255,.9); }
  .btn { width: 100%; padding: .875rem 1.5rem; border: none; border-radius: var(--radius-md); font-weight: 600; font-size: .95rem;
         cursor: pointer; transition: all .2s ease; display: inline-flex; align-items: center; justify-content: center; gap: .5rem; text-decoration: none; }
  .btn-primary { background: var(--primary-color); color: #fff; }
  .btn-primary:hover { background: var(--primary-dark); transform: translateY(-1px); box-shadow: var(--shadow-lg); }
  .btn-white { background: #fff; color: var(--primary-color); border: 2px solid rgba(255,255,255,.3); }
  .btn-white:hover { background: rgba(255,255,255,.95); transform: translateY(-1px); }
  .divider { text-align: center; margin: 1.5rem 0; position: relative; color: var(--text-muted); font-size: .875rem; }
  .divider::before { content: ''; position: absolute; top: 50%; left: 0; right: 0; height: 1px; background: var(--border-light); }
  .divider span { background: #fff; padding: 0 1rem; }
  .login-section .divider span { background: var(--primary-color); color: rgba(255,255,255,.8); }
</style>
</head>
<body>
  <!-- Header -->
  <header class="header">
    <div class="container">
      <div class="header-content">
        <a href="index.php"><img src="images/logo.png" class="logo" alt="Dimple Star Transport" /></a>
        <nav class="nav">
          <ul class="nav-list">
            <li><a href="index.php" class="nav-link">Home</a></li>
            <li><a href="about.php" class="nav-link">About Us</a></li>
            <li><a href="terminal.php" class="nav-link">Terminals</a></li>
            <li><a href="routeschedule.php" class="nav-link">Routes & Schedules</a></li>
            <li><a href="contact.php" class="nav-link">Contact</a></li>
            <li><a href="book.php" class="nav-link">Book Now</a></li>
          </ul>
        </nav>
      </div>
    </div>
  </header>

  <div class="auth-container">
    <main class="auth-main">
      <div class="auth-wrapper">
        <div class="auth-grid">

          <!-- Login -->
          <div class="auth-section login-section">
            <div class="auth-header">
              <h2>Welcome Back</h2>
              <p>Sign in to your account to continue</p>
            </div>

            <form method="post" action="login.php">
              <div class="form-group">
                <label for="login_email" class="form-label">Email Address</label>
                <input type="email" name="email" id="login_email" class="form-input" placeholder="Enter your email" required>
              </div>

              <div class="form-group">
                <label for="login_password" class="form-label">Password</label>
                <input type="password" name="password" id="login_password" class="form-input" placeholder="Enter your password" required>
              </div>

              <?php
              if (isset($_SESSION['login_error'])) {
                echo '<div class="error-message"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>' . e($_SESSION['login_error']) . '</div>';
                unset($_SESSION['login_error']);
              }
              if (isset($_GET['message'])) {
                echo '<div class="error-message"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>' . e($_GET['message']) . '</div>';
              }
              ?>

              <button type="submit" class="btn btn-white">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 1.125rem; height: 1.125rem;">
                  <path d="M9 12l2 2 4-4"/><path d="M21 12c0 4.97-4.03 9-9 9s-9-4.03-9-9s4.03-9 9-9c1.66 0 3.22.45 4.56 1.24"/>
                </svg>
                Sign In
              </button>
            </form>
          </div>

          <!-- Sign Up -->
          <div class="auth-section">
            <div class="auth-header">
              <h2>Create Account</h2>
              <p>Join us for easy booking and exclusive offers</p>
            </div>

            <?= $successful ?>

            <form method="post" action="signlog.php">
              <div class="form-row">
                <div class="form-group">
                  <label for="fname" class="form-label">First Name</label>
                  <input type="text" name="fname" id="fname" class="form-input" placeholder="First Name" value="<?= e($val_fname) ?>">
                  <?php if(isset($errors['fname'])): ?><div class="error-message"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg><?= e($errors['fname']) ?></div><?php endif; ?>
                </div>
                <div class="form-group">
                  <label for="lname" class="form-label">Last Name</label>
                  <input type="text" name="lname" id="lname" class="form-input" placeholder="Last Name" value="<?= e($val_lname) ?>">
                  <?php if(isset($errors['lname'])): ?><div class="error-message"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg><?= e($errors['lname']) ?></div><?php endif; ?>
                </div>
              </div>

              <div class="form-group">
                <label for="email" class="form-label">Email Address</label>
                <input type="email" name="email" id="email" class="form-input" placeholder="your.email@example.com" value="<?= e($val_email) ?>">
                <?php if(isset($errors['email'])): ?><div class="error-message"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg><?= e($errors['email']) ?></div><?php endif; ?>
              </div>

              <div class="form-group">
                <label for="pw" class="form-label">Password</label>
                <input type="password" name="password" id="pw" class="form-input" placeholder="Minimum 8 characters" required>
                <?php if(isset($errors['password'])): ?><div class="error-message"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg><?= e($errors['password']) ?></div><?php endif; ?>
              </div>

              <div class="form-group">
                <label for="cpw" class="form-label">Confirm Password</label>
                <input type="password" name="confirm_password" id="cpw" class="form-input" placeholder="Repeat your password" required>
                <?php if(isset($errors['confirm_password'])): ?><div class="error-message"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg><?= e($errors['confirm_password']) ?></div><?php endif; ?>
              </div>

              <button type="submit" class="btn btn-primary">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 1.125rem; height: 1.125rem;">
                  <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                  <circle cx="8.5" cy="7" r="4"/><line x1="20" y1="8" x2="20" y2="14"/><line x1="23" y1="11" x2="17" y2="11"/>
                </svg>
                Create Account
              </button>
              <div class="divider"><span>Benefits of signing up</span></div>
              <!-- your benefits grid ... -->
            </form>
          </div>

        </div>
      </div>
    </main>
  </div>

  <footer class="footer">
    <div class="container">
      <div class="footer-content">
        <div class="footer-section">
          <h3>Dimple Star Transport</h3>
          <p>Your trusted partner in transportation since 2004...</p>
        </div>
        <div class="footer-section">
          <h3>Quick Links</h3>
          <p>
            <a href="about.php" style="color:#9ca3af;text-decoration:none;">About Us</a><br>
            <a href="routeschedule.php" style="color:#9ca3af;text-decoration:none;">Routes & Schedules</a><br>
            <a href="contact.php" style="color:#9ca3af;text-decoration:none;">Contact</a><br>
            <a href="book.php" style="color:#9ca3af;text-decoration:none;">Book Now</a>
          </p>
        </div>
        <div class="footer-section">
          <h3>Contact Info</h3>
          <p>Phone: 0929 209 0712<br>Address: Block 1 lot 10, Southpoint Subd.<br>Brgy Banay-Banay, Cabuyao, Laguna</p>
        </div>
      </div>
      <div class="footer-bottom"><p>&copy; 2024 Dimple Star Transport. All rights reserved.</p></div>
    </div>
  </footer>
</body>
</html>
