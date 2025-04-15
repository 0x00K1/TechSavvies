<?php
require_once __DIR__ . '/secure_session.php';

// Unset all session variables
$_SESSION = [];

// Remove the session cookie
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(), 
        '', 
        time() - 42000,
        $params["path"], 
        $params["domain"],
        $params["secure"], 
        $params["httponly"]
    );
}

// setcookie('cartItems', '', time() - 3600, '/'); // This forces the cartItems cookie to expire
session_destroy();

// Redirect to a logged-out page
header("Location: /logout.php");
exit;
?>