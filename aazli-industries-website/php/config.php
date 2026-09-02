<?php
// AAZLI INDUSTRIES — database connection & admin settings
// XAMPP defaults: host=localhost, user=root, password="" (empty).

$DB_HOST = 'localhost';
$DB_NAME = 'aazli_industries';
$DB_USER = 'root';
$DB_PASS = '';

// Admin panel login password. CHANGE THIS before putting the site anywhere public.
define('ADMIN_PASSWORD', 'AazliAdmin2026');

try {
    $pdo = new PDO(
        "mysql:host=$DB_HOST;dbname=$DB_NAME;charset=utf8mb4",
        $DB_USER,
        $DB_PASS,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
} catch (PDOException $e) {
    http_response_code(500);
    die(json_encode(['ok' => false, 'error' => 'Database connection failed. Did you run sql/schema.sql in phpMyAdmin?']));
}
