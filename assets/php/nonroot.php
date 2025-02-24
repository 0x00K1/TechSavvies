<?php
session_start(); // Ensure the session is started
if (!isset($_SESSION['is_root']) || $_SESSION['is_root'] !== true) {
    // We'll log it as event for security monitoring ------ [LATER]
    header("Location: /"); // Redirect non-root users to the homepage
    // [.htaccess] ------ [LATER]
    exit;
}
?>