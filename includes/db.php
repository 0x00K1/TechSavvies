<?php
$host = "localhost";
$dbname = "techsavvies";
$username = "root"; // default XAMPP username
$password = ""; // no password for local development
require_once __DIR__ . '/../assets/php/env_loader.php';

// Dynamically check for DB credentials
checkRequiredEnv(['DB_HOST', 'DB_NAME', 'DB_USER']);

 $host     = getenv('DB_HOST');
$dbname   = getenv('DB_NAME');
$username = getenv('DB_USER');
$password = ""; // Explicitly set empty password for local development

// Connect to DB
try {
    $port = getenv('DB_PORT');
    $pdo = new \PDO("mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
} catch(\PDOException $e) {
    error_log("Database Connection Failed: " . $e->getMessage());
    exit("Error: Database connection failed. Please check logs.");
}
?>
