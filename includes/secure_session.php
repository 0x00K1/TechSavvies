<?php
session_set_cookie_params([
    'lifetime' => 0,
    'path' => '/',
    'domain' => 'localhost',       // It should be techsavvies.shop :) 
    'secure' => false,             // Only send cookies over HTTPS [TEMP=>FALSE]
    'httponly' => true,            // Not accessible via JavaScript
    'samesite' => 'Strict'         // Helps mitigate CSRF
]);
session_start();

// Generate a CSRF token if one doesn't exist
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>
