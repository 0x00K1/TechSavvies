<?php
require_once __DIR__ . '/secure_session.php';
header("Content-Type: application/json");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../assets/php/Exception.php';
require '../assets/php/PHPMailer.php';
require '../assets/php/SMTP.php';

$data = json_decode(file_get_contents("php://input"), true);
if (!isset($data['email'], $data['csrf_token']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
    echo json_encode(["success" => false, "error" => "Invalid email or CSRF token."]);
    exit;
}

// Validate CSRF token
if (!hash_equals($_SESSION['csrf_token'], $data['csrf_token'])) {
    echo json_encode(["success" => false, "error" => "Invalid CSRF token."]);
    exit;
}

// Enforce a 60-second wait between OTP requests
if (isset($_SESSION['last_otp_time']) && (time() - $_SESSION['last_otp_time'] < 60)) {
    echo json_encode(["success" => false, "error" => "Wait before requesting another OTP."]);
    exit;
}

$email = $data['email'];

function generateOTP($length = 6) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!@#$%^&*()';
    $otp = '';
    for ($i = 0; $i < $length; $i++) {
        $otp .= $characters[random_int(0, strlen($characters) - 1)];
    }
    return $otp;
}

$otp = generateOTP(6);
$_SESSION['otp'] = $otp;
$_SESSION['otp_email'] = $email;
$_SESSION['last_otp_time'] = time();

$subject = "OTP for TechSavvies.shop";
// Hello <username> ... [LATER]
$message = "Hello,\n\nYour OTP is: $otp\nIf you did not request OTP from TechSavvies.shop, just ignore this email.\n\nThank you.";

$mail = new PHPMailer(true);
try {
    $mail->isSMTP();
    $mail->Host       = 'smtp.mailersend.net';
    $mail->SMTPAuth   = true;
    $mail->Username   = "MS_b0ALC5@trial-z86org8qqe1gew13.mlsender.net";  
    $mail->Password   = "mssp.WGtNcX0.pq3enl6y86mg2vwr.uayIzPO";  
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;
    $mail->setFrom("MS_b0ALC5@trial-z86org8qqe1gew13.mlsender.net", 'TechSavvies');
    $mail->addAddress($email);
    $mail->isHTML(false);
    $mail->Subject = $subject;
    $mail->Body    = $message;
    $mail->send();
    echo json_encode(["success" => true]);
} catch (Exception $e) {
    error_log("Mailer Error: " . $mail->ErrorInfo);
    echo json_encode(["success" => false, "error" => "Unable to send email."]);
}
?>
