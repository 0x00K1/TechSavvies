<?php
session_start();
header("Content-Type: application/json");
require_once "db.php";

// Get POST JSON input
$data = json_decode(file_get_contents("php://input"), true);
if (!isset($data['email']) || !isset($data['otp'])) {
    echo json_encode(["success" => false, "error" => "Email and OTP are required."]);
    exit;
}

$email = $data['email'];
$otp_input = trim($data['otp']);

// Check if the OTP matches and the email is the same as when sent
if (
    !isset($_SESSION['otp'], $_SESSION['otp_email']) ||
    $_SESSION['otp_email'] !== $email ||
    $_SESSION['otp'] != $otp_input
) {
    echo json_encode(["success" => false, "error" => "Incorrect OTP."]);
    exit;
}

try {
    // Check if the user already exists
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$user) {
        // The user does not exist; generate a unique username starting with "savvyclient"
        $username = "";
        do {
            $randomNumber = rand(1000, 9999);
            $username = "savvyclient" . $randomNumber;
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username = ?");
            $stmt->execute([$username]);
            $count = $stmt->fetchColumn();
        } while ($count > 0);
        
        // Temp logic (shall we config the user without pass ?)
        $defaultPassword = password_hash("default_password", PASSWORD_DEFAULT);
        
        $stmt = $pdo->prepare(
            "INSERT INTO users (email, password, username, role, is_verified, created_at)
             VALUES (?, ?, ?, 'user', 1, CURRENT_TIMESTAMP)"
        );
        $stmt->execute([$email, $defaultPassword, $username]);
        
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
    } else {
        // The user exists: update verification status if needed.
        if (!$user['is_verified']) {
            $stmt = $pdo->prepare("UPDATE users SET is_verified = 1 WHERE email = ?");
            $stmt->execute([$email]);
            // Re-fetch the updated user information
            $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
        }
    }
    
    // Clear the OTP session variables since verification succeeded
    unset($_SESSION['otp'], $_SESSION['otp_email']);
    
    // Create a session entry to keep the user logged in
    $_SESSION['user'] = $user;
    
    echo json_encode(["success" => true, "user" => $user]);
} catch (PDOException $e) {
    echo json_encode(["success" => false, "error" => "Database error: " . $e->getMessage()]);
}
?>