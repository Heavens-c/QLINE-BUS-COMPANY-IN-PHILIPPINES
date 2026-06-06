<?php
// php_includes/connection.php
// Do NOT call session_start() in this file. Start sessions only in the pages that need them.

// Load environment variables from .env if it exists
$envPath = __DIR__ . '/../.env';
if (file_exists($envPath)) {
    $lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) {
            continue;
        }
        $parts = explode('=', $line, 2);
        if (count($parts) === 2) {
            $key = trim($parts[0]);
            $val = trim($parts[1]);
            // Strip single or double quotes
            if (preg_match('/^"(.*)"$/', $val, $matches) || preg_match('/^\'(.*)\'$/', $val, $matches)) {
                $val = $matches[1];
            }
            $_ENV[$key] = $val;
            putenv("$key=$val");
        }
    }
}

$DB_HOST = $_ENV['DB_HOST'] ?? 'localhost';
$DB_USER = $_ENV['DB_USER'] ?? 'root';
$DB_PASS = $_ENV['DB_PASS'] ?? '';
$DB_NAME = $_ENV['DB_NAME'] ?? 'dimplestar';

// Reuse the connection if it's already been created.
if (!isset($con) || !($con instanceof mysqli)) {
    // Optional: throw mysqli errors as exceptions (helps debugging)
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

    try {
        $con = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
        $con->set_charset('utf8mb4');
        
        // Run database migrations/schema updates automatically
        require_once __DIR__ . '/migration.php';
        run_migrations($con);
    } catch (mysqli_sql_exception $e) {
        // Fail fast with a clear message (avoid exposing credentials)
        die('Database connection failed.');
    }
}
