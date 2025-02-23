<?php
require_once __DIR__ . '/secure_session.php';
header("Content-Type: application/json");
ob_start(); // Start output buffering to prevent unintended output

require_once "db.php";

$data = json_decode(file_get_contents("php://input"), true);

// Enable debugging logs
// error_log("DEBUG: Incoming OTP request");

// Validate input
if (!isset($data['email'], $data['otp'], $data['csrf_token']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
    error_log("ERROR: Missing email, OTP, or CSRF token");
    echo json_encode(["success" => false, "error" => "Valid email, OTP, and CSRF token are required."]);
    exit;
}

// Validate CSRF token
if (!isset($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $data['csrf_token'])) {
    error_log("ERROR: Invalid CSRF token");
    echo json_encode(["success" => false, "error" => "Invalid CSRF token."]);
    exit;
}

$email = filter_var($data['email'], FILTER_SANITIZE_EMAIL);
$otp_input = trim($data['otp']);

// Debugging: Log session values
// error_log("DEBUG: Session OTP Stored: " . ($_SESSION['otp'] ?? 'NOT SET'));
// error_log("DEBUG: Session OTP Email: " . ($_SESSION['otp_email'] ?? 'NOT SET'));
// error_log("DEBUG: User Input OTP: " . $otp_input);
// error_log("DEBUG: Last OTP Time: " . ($_SESSION['last_otp_time'] ?? 'NOT SET'));

// Ensure OTP exists in session and matches the user's email
if (!isset($_SESSION['otp'], $_SESSION['otp_email']) || $_SESSION['otp_email'] !== $email) {
    error_log("ERROR: OTP mismatch or missing for email: $email");
    echo json_encode(["success" => false, "error" => "Incorrect OTP."]);
    exit;
}

// Check OTP expiration (180 seconds = 3 minutes)
if ((time() - $_SESSION['last_otp_time']) > 180) {
    error_log("ERROR: OTP expired");
    unset($_SESSION['otp'], $_SESSION['otp_email']);
    echo json_encode(["success" => false, "error" => "OTP expired. Request a new one."]);
    exit;
}

// OTP verified successfully
// error_log("DEBUG: OTP verified successfully");

// Secure OTP comparison
if (!hash_equals($_SESSION['otp'], $otp_input)) {
    $_SESSION['otp_fail_count']++;
    
    if ($_SESSION['otp_fail_count'] >= 3) {
        unset($_SESSION['otp'], $_SESSION['otp_email'], $_SESSION['last_otp_time'], $_SESSION['otp_fail_count']);
        echo json_encode(["success" => false, "error" => "Too many attempts. OTP invalidated."]);
        exit;
    }

    echo json_encode(["success" => false, "error" => "Incorrect OTP."]);
    exit;
}

// If we reach here, OTP is correct
// Reset the failure counter
$_SESSION['otp_fail_count'] = 0;

try {
    // Lookup the user by email
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // If user does not exist, register a new one
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

    // Debugging: Confirm successful authentication
    // error_log("DEBUG: User authenticated successfully: " . json_encode($user));

    // OTP is valid, clear session values
    unset($_SESSION['otp'], $_SESSION['otp_email'], $_SESSION['last_otp_time']);

    // Regenerate session ID after successful authentication to prevent fixation attacks
    session_regenerate_id(true);

    /////// ammmmmmm
    if ($user['role'] === 'root') {
        $_SESSION['is_root'] = true;
    }

    unset($user['password']);
    $_SESSION['user'] = $user;
    $_SESSION['logged_in'] = true;

    // Send JSON response
    ob_clean(); // Clean any output before sending JSON
    echo json_encode(["success" => true, "user" => $user]);
} catch (PDOException $e) {
    error_log("Database error: " . $e->getMessage());
    ob_clean(); // Prevent any errors from corrupting JSON
    echo json_encode(["success" => false, "error" => "A database error occurred."]);
}
exit;
?>