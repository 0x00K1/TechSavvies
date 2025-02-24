<?php
require_once __DIR__ . '/../assets/php/env_loader.php';

// Dynamically check for DB credentials
checkRequiredEnv(['DB_HOST', 'DB_NAME', 'DB_USERNAME', 'DB_PASSWORD']);

use PDO;
use PDOException;

// Now we know these exist
$host     = getenv('DB_HOST');
$dbname   = getenv('DB_NAME');
$username = getenv('DB_USERNAME');
$password = getenv('DB_PASSWORD');

// Connect to DB
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    error_log("Database Connection Failed: " . $e->getMessage());
    exit("Error: Database connection failed. Please check logs.");
}
