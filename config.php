<?php
// config.php
$DB_HOST = 'localhost';
$DB_NAME = 'fitzone_db';
$DB_USER = 'root';
$DB_PASS = ''; // Laragon default

// Dynamically determine the base path (e.g. "/" or "/fitzone_fitness/")
$base_dir = str_replace($_SERVER['DOCUMENT_ROOT'] ?? '', '', __DIR__);
$base_dir = str_replace('\\', '/', $base_dir);
$base_dir = trim($base_dir, '/');
define('BASE_URL', '/' . ($base_dir ? $base_dir . '/' : ''));

try {
    $pdo = new PDO("mysql:host=$DB_HOST;dbname=$DB_NAME;charset=utf8mb4", $DB_USER, $DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
} catch (PDOException $e) {
    die("DB Connection failed: " . $e->getMessage());
}

if (session_status() === PHP_SESSION_NONE) session_start();

function require_role($roles = []) {
    if (empty($_SESSION['user'])) {
        header('Location: /auth/login.php'); exit;
    }
    if ($roles && !in_array($_SESSION['user']['role'], $roles)) {
        http_response_code(403); echo "<h2>403 Forbidden</h2>"; exit;
    }
}
?>
