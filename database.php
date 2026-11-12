<?php
$dbHost = getenv('DB_HOST') ?: '127.0.0.1';
$dbPort = (int) (getenv('DB_PORT') ?: 3306);
$dbName = getenv('DB_NAME');
$dbUser = getenv('DB_USER');
$dbPassword = getenv('DB_PASSWORD') ?: '';
if (!$dbName || !$dbUser) {
    http_response_code(503);
    exit('Set DB_NAME and DB_USER in the local environment before starting PHP.');
}
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
try {
    $koneksi = new mysqli($dbHost, $dbUser, $dbPassword, $dbName, $dbPort);
    $koneksi->set_charset('utf8mb4');
} catch (mysqli_sql_exception $exception) {
    http_response_code(503);
    exit('Database unavailable. Check the local configuration.');
}
