<?php
// php_includes/audit.php
function audit_log(mysqli $con, $actor, string $action, array $meta = []): void {
    // Create table if needed (safe/cheap to run)
    $create = "
    CREATE TABLE IF NOT EXISTS audit_log (
        id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
        user_id VARCHAR(191) NULL,
        email   VARCHAR(191) NULL,
        action  VARCHAR(64)  NOT NULL,
        ip_address VARCHAR(45) NULL,
        user_agent TEXT NULL,
        meta_json  JSON NULL,
        created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
    try { $con->query($create); } catch (\Throwable $e) { /* ignore */ }

    $ip   = $_SERVER['REMOTE_ADDR']  ?? null;
    $ua   = $_SERVER['HTTP_USER_AGENT'] ?? null;
    $metaJson = $meta ? json_encode($meta, JSON_UNESCAPED_UNICODE) : null;

    // Try with meta_json
    $sql = "INSERT INTO audit_log (user_id, email, action, ip_address, user_agent, meta_json)
            VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $con->prepare($sql);
    if ($stmt) {
        // actor may be ID or email; we’ll set both best-effort
        $userId = is_numeric($actor) ? (string)$actor : null;
        $email  = is_numeric($actor) ? null : (string)$actor;
        $stmt->bind_param("ssssss", $userId, $email, $action, $ip, $ua, $metaJson);
        try { $stmt->execute(); } catch (\Throwable $e) { /* ignore */ }
        $stmt->close();
        return;
    }

    // Fallback without meta_json (older MySQL)
    $sql2 = "INSERT INTO audit_log (user_id, email, action, ip_address, user_agent)
             VALUES (?, ?, ?, ?, ?)";
    if ($stmt2 = $con->prepare($sql2)) {
        $userId = is_numeric($actor) ? (string)$actor : null;
        $email  = is_numeric($actor) ? null : (string)$actor;
        $stmt2->bind_param("sssss", $userId, $email, $action, $ip, $ua);
        try { $stmt2->execute(); } catch (\Throwable $e) { /* ignore */ }
        $stmt2->close();
    }
}
