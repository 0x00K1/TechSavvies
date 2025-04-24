<?php
// Only set cookie parameters if no session is active
if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'domain' => 'localhost', // techsavvies.shop
        'secure' => false,       // Set to true when using HTTPS
        'httponly' => true,
        'samesite' => 'Strict'
    ]);
    session_start();
} else {
    error_log("Session already active; skipping session_set_cookie_params.");
}

// Generate a CSRF token if one doesn't exist
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>
