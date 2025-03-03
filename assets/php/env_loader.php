<?php
/**
 * env_loader.php
 *
 * Loads your .env file using vlucas/phpdotenv,
 * sets them as environment variables, and provides a helper
 * function to dynamically check for required env vars.
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

/**
 * 3) Dynamic env var checker
 * 
 * @param string[] $vars
 * @return void (or throws exit)
 */
function checkRequiredEnv(array $vars = []): void
{
    foreach ($vars as $var) {
        if (!getenv($var)) {
            error_log("Missing required environment variable: $var");
            exit("Error: Missing required environment variable: $var. Check your .env file.");
        }
    }
}