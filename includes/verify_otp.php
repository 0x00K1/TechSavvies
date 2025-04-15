<?php
header("Content-Type: application/json");
ob_start(); // Start output buffering

require_once __DIR__ . '/db.php';

$data = json_decode(file_get_contents("php://input"), true);

// Validate input
if (!isset($data['email'], $data['otp'], $data['csrf_token']) 
    || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
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

// Ensure OTP in session matches this email
if (!isset($_SESSION['otp'], $_SESSION['otp_email']) || $_SESSION['otp_email'] !== $email) {
    error_log("ERROR: OTP mismatch or missing for email: $email");
    echo json_encode(["success" => false, "error" => "Incorrect OTP."]);
    exit;
}

// Check if OTP is expired (180 seconds)
if ((time() - $_SESSION['last_otp_time']) > 180) {
    error_log("ERROR: OTP expired");
    unset($_SESSION['otp'], $_SESSION['otp_email']);
    echo json_encode(["success" => false, "error" => "OTP expired. Request a new one."]);
    exit;
}

// Check OTP correctness
if (!hash_equals($_SESSION['otp'], $otp_input)) {
    $_SESSION['otp_fail_count'] = ($_SESSION['otp_fail_count'] ?? 0) + 1;
    
    if ($_SESSION['otp_fail_count'] >= 3) {
        unset($_SESSION['otp'], $_SESSION['otp_email'], $_SESSION['last_otp_time'], $_SESSION['otp_fail_count']);
        echo json_encode(["success" => false, "error" => "Too many attempts. OTP invalidated."]);
        exit;
    }
    
    echo json_encode(["success" => false, "error" => "Incorrect OTP."]);
    exit;
}

// OTP is correct
$_SESSION['otp_fail_count'] = 0; // reset fail counter

try {
    $stmt = $pdo->prepare("SELECT * FROM roots WHERE email = ?");
    $stmt->execute([$email]);
    $rootUser = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($rootUser) {
        $user = $rootUser;
        $isRoot = true;
    } else {
        $stmt = $pdo->prepare("SELECT * FROM customers WHERE email = ?");
        $stmt->execute([$email]);
        $customer = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($customer) {
            $user = $customer;
            $isRoot = false;
        } else {
            // Customer not found, create a new one with default username as savvycleint[------]
            do {
                $randomNumber = random_int(100000, 999999); // Ensures a 6-digit number
                $username = "savvyclient" . $randomNumber;

                $checkStmt = $pdo->prepare("SELECT COUNT(*) FROM customers WHERE username = ?");
                $checkStmt->execute([$username]);
            } while ($checkStmt->fetchColumn() > 0);

            $insertStmt = $pdo->prepare("
                INSERT INTO customers (email, username, created_at) 
                VALUES (?, ?, CURRENT_TIMESTAMP)
            ");
            $insertStmt->execute([$email, $username]);

            // Fetch the newly created record
            $stmt = $pdo->prepare("SELECT * FROM customers WHERE email = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            $isRoot = false;
        }
    }

    // Clear OTP-related session data
    unset($_SESSION['otp'], $_SESSION['otp_email'], $_SESSION['last_otp_time']);

    // Regenerate session to prevent fixation
    session_regenerate_id(true);

    // Set session accordingly
    $_SESSION['is_root'] = $isRoot; // [WATCH OUT] => [LATER]
    $_SESSION['user'] = $user;
    $_SESSION['logged_in'] = true;

    ob_clean(); // ensure a clean JSON response
    echo json_encode(["success" => true, "user" => $user, "is_root" => $isRoot]);
} catch (PDOException $e) {
    error_log("Database error: " . $e->getMessage());
    ob_clean();
    echo json_encode(["success" => false, "error" => "A database error occurred."]);
}
exit;
?>