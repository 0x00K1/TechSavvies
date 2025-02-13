<?php
require_once __DIR__ . '/secure_session.php';
header("Content-Type: application/json");
require_once "db.php";

// Decode input and ensure email, OTP, and CSRF token are provided
$data = json_decode(file_get_contents("php://input"), true);
if (!isset($data['email'], $data['otp'], $data['csrf_token']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
    echo json_encode(["success" => false, "error" => "Valid email, OTP, and CSRF token are required."]);
    exit;
}

// Validate CSRF token
if (!hash_equals($_SESSION['csrf_token'], $data['csrf_token'])) {
    echo json_encode(["success" => false, "error" => "Invalid CSRF token."]);
    exit;
}

$email = filter_var($data['email'], FILTER_SANITIZE_EMAIL);
$otp_input = trim($data['otp']);

// Verify OTP stored in the session
if (!isset($_SESSION['otp'], $_SESSION['otp_email']) || $_SESSION['otp_email'] !== $email || !hash_equals((string)$_SESSION['otp'], $otp_input)) {
    echo json_encode(["success" => false, "error" => "Incorrect OTP."]);
    exit;
}

// Check OTP expiration (180 seconds = 3 minutes)
if ((time() - $_SESSION['last_otp_time']) > 180) {
    echo json_encode(["success" => false, "error" => "OTP expired. Request a new one."]);
    unset($_SESSION['otp'], $_SESSION['otp_email']);
    exit;
}

try {
    // Lookup the user by email
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // If user doesn't exist, register a new one with a unique username
    if (!$user) {
        do {
            $randomNumber = random_int(1, 99999);
            $username = "savvyclient" . $randomNumber;
            $checkStmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username = ?");
            $checkStmt->execute([$username]);
        } while ($checkStmt->fetchColumn() > 0);

        $randomPassword = bin2hex(random_bytes(8));
        $hashedPassword = password_hash($randomPassword, PASSWORD_DEFAULT);

        $insertStmt = $pdo->prepare("INSERT INTO users (email, password, username, role, created_at) VALUES (?, ?, ?, 'user', CURRENT_TIMESTAMP)");
        $insertStmt->execute([$email, $hashedPassword, $username]);

        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Remove OTP data from session
    unset($_SESSION['otp'], $_SESSION['otp_email']);

    // Regenerate session ID after successful authentication to prevent fixation attacks
    session_regenerate_id(true);

    unset($user['password']);
    $_SESSION['user'] = $user;
    echo json_encode(["success" => true, "user" => $user]);

} catch (PDOException $e) {
    error_log("Database error: " . $e->getMessage());
    echo json_encode(["success" => false, "error" => "A database error occurred."]);
}
?>