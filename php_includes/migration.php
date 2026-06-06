<?php
// php_includes/migration.php
// Ensures the database schema matches the international standard automatically

function run_migrations(mysqli $con) {
    // 1. Check regs table columns
    $cols = [];
    $res = $con->query("SHOW COLUMNS FROM regs");
    if ($res) {
        while ($row = $res->fetch_assoc()) {
            $cols[] = strtolower($row['Field']);
        }
    }

    if (!in_array('travel_date', $cols)) {
        try {
            $con->query("ALTER TABLE regs ADD COLUMN travel_date DATE DEFAULT NULL");
        } catch (\Throwable $e) { /* ignore */ }
    }
    if (!in_array('payment_status', $cols)) {
        try {
            $con->query("ALTER TABLE regs ADD COLUMN payment_status VARCHAR(20) DEFAULT 'pending'");
        } catch (\Throwable $e) { /* ignore */ }
    }
    if (!in_array('payment_ref', $cols)) {
        try {
            $con->query("ALTER TABLE regs ADD COLUMN payment_ref VARCHAR(50) DEFAULT NULL");
        } catch (\Throwable $e) { /* ignore */ }
    }

    // 2. Create audit_log if missing
    $con->query("
    CREATE TABLE IF NOT EXISTS audit_log (
        id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
        user_id VARCHAR(191) NULL,
        email   VARCHAR(191) NULL,
        action  VARCHAR(64)  NOT NULL,
        ip_address VARCHAR(45) NULL,
        user_agent TEXT NULL,
        meta_json  JSON NULL,
        created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        KEY ix_user_id (user_id),
        KEY ix_email (email),
        KEY ix_action (action),
        KEY ix_created_at (created_at)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
    ");

    // 3. Create otp_verifications if missing
    $con->query("
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
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
    ");
}
