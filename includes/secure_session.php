<?php
// Only set cookie parameters if no session is active
if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'domain' => 'localhost', // Change to techsavvies.shop in production
        'secure' => false,       // Set to true when using HTTPS in production
        'httponly' => true,
        'samesite' => 'Strict'
    ]);
    session_start();
} else {
    // Optionally, log a message if you expect no session to be active here.
    error_log("Session already active; skipping session_set_cookie_params.");
}

// Generate a CSRF token if one doesn't exist
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>
