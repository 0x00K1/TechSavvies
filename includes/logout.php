<?php
session_start();

// Unset all session variables
$_SESSION = [];

// If you want to kill the session (remove the session cookie) 
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    // Set the cookie to expire in the past
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

// Destroy the session
session_destroy();

// Redirect to a dedicated "logged out" page or the homepage
header("Location: /logout.html");
exit;
?>