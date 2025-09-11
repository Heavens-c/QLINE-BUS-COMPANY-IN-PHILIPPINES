<?php
// php_includes/connection.php
// Do NOT call session_start() in this file. Start sessions only in the pages that need them.

$DB_HOST = 'localhost';
$DB_USER = 'root';
$DB_PASS = '';          // use '' (empty string) instead of null
$DB_NAME = 'dimplestar';

// Reuse the connection if it's already been created.
if (!isset($con) || !($con instanceof mysqli)) {
    // Optional: throw mysqli errors as exceptions (helps debugging)
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

    try {
        $con = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
        $con->set_charset('utf8mb4');
    } catch (mysqli_sql_exception $e) {
        // Fail fast with a clear message (avoid exposing credentials)
        die('Database connection failed.');
    }
}
