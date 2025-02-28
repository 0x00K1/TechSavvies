<?php
function loadEnv() {
    $envFile = __DIR__ . '/../../.env';
    if (file_exists($envFile)) {
        $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            if (strpos($line, '=') !== false && strpos($line, '#') !== 0) {
                list($key, $value) = explode('=', $line, 2);
                $key = trim($key);
                $value = trim($value);
                putenv("$key=$value");
            }
        }
    }
}

function checkRequiredEnv($required) {
    loadEnv();
    $missing = [];
    foreach ($required as $var) {
        if (getenv($var) === false) {
            $missing[] = $var;
        }
    }
    if (!empty($missing)) {
        error_log('Missing required environment variables: ' . implode(', ', $missing));
        exit('Error: Missing required environment variables. Check error log for details.');
    }
}