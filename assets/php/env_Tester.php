<?php
/**
 * env_Tester.php
 * 
 * This file loads your .env variables, sets them as environment variables, 
 * and ensures required variables are present.
 */
require_once __DIR__ . '/../../vendor/autoload.php';

use Dotenv\Dotenv;

/**
 * 1) Create Dotenv instance & load .env
 */
$dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
$dotenv->safeLoad();

/**
 * 2) Convert $_ENV to environment variables
 */
foreach ($_ENV as $key => $value) {
    putenv("$key=$value");
}

echo "<pre>";
echo "DB_HOST: " . getenv('DB_HOST') . "\n";
echo "DB_NAME: " . getenv('DB_NAME') . "\n";
echo "DB_USERNAME: " . getenv('DB_USERNAME') . "\n";
echo "DB_PASSWORD: " . getenv('DB_PASSWORD') . "\n";
echo "SMTP_HOST: " . getenv('SMTP_HOST') . "\n";
echo "SMTP_PORT: " . getenv('SMTP_PORT') . "\n";
echo "SMTP_USERNAME: " . getenv('SMTP_USERNAME') . "\n";
echo "SMTP_PASSWORD: " . getenv('SMTP_PASSWORD') . "\n";
echo "MAIL_FROM_ADDRESS: " . getenv('MAIL_FROM_ADDRESS') . "\n";
echo "MAIL_FROM_NAME: " . getenv('MAIL_FROM_NAME') . "\n";
echo "</pre>";
?>