<?php
require_once __DIR__ . '/secure_session.php';
require_once __DIR__ . '/../assets/php/env_loader.php';
require '../assets/php/Exception.php';
require '../assets/php/PHPMailer.php';
require '../assets/php/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

header("Content-Type: application/json");
header("X-Content-Type-Options: nosniff");
header("X-Frame-Options: DENY");

// Only require SMTP env vars
checkRequiredEnv([
    'SMTP_HOST', 
    'SMTP_PORT', 
    'SMTP_USERNAME', 
    'SMTP_PASSWORD', 
    'MAIL_FROM_ADDRESS', 
    'MAIL_FROM_NAME'
]);

$data = json_decode(file_get_contents("php://input"), true);

// Validate email and CSRF token
if (!isset($data['email'], $data['csrf_token']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
    echo json_encode(["success" => false, "error" => "Invalid email or CSRF token."]);
    exit;
}

if (!hash_equals($_SESSION['csrf_token'], $data['csrf_token'])) {
    echo json_encode(["success" => false, "error" => "Invalid CSRF token."]);
    exit;
}

// Enforce 60-second wait between OTP requests
if (isset($_SESSION['last_otp_time']) && (time() - $_SESSION['last_otp_time'] < 60)) {
    echo json_encode(["success" => false, "error" => "Wait before requesting another OTP."]);
    exit;
}

$email = $data['email'];

// Function to generate a secure OTP
function generateSecureOTP($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!@#$%^&*()';
    $otp = '';
    for ($i = 0; $i < $length; $i++) {
        $otp .= $characters[random_int(0, strlen($characters) - 1)];
    }
    return $otp;
}

$otp = generateSecureOTP(10);
$_SESSION['otp'] = $otp;
$_SESSION['otp_email'] = $email;
$_SESSION['last_otp_time'] = time();

$subject = "Your OTP for TechSavvies.shop";
$htmlMessage = <<<HTML
<html>
<head>
  <style>
    .email-container {
      font-family: Arial, sans-serif;
      background-color: #f9f9f9;
      padding: 20px;
      text-align: center;
    }
    .email-content {
      background-color: #ffffff;
      border-radius: 8px;
      padding: 20px;
      box-shadow: 0 2px 5px rgba(0,0,0,0.1);
      display: inline-block;
      max-width: 600px;
      width: 100%;
    }
    .logo {
      max-width: 150px;
      margin-bottom: 20px;
    }
    .otp-code {
      font-size: 24px;
      font-weight: bold;
      color: #333333;
    }
    .footer {
      margin-top: 20px;
      font-size: 12px;
      color: #777777;
    }
  </style>
</head>
<body>
  <div class="email-container">
    <div class="email-content">
      <img class="logo" src='https://i.postimg.cc/rDHDSjZw/Logo.png' alt="TechSavvies.shop Logo">
      <h2>OTP for TechSavvies.shop</h2>
      <p>Holla, Your One Time Password is:</p>
      <p class="otp-code">$otp</p>
      <p>If you did not request this OTP, just ignore this email.</p>
      <p class="footer">Thank you,<br>TechSavvies.shop Team</p>
    </div>
  </div>
</body>
</html>
HTML;

// Plain text version for email clients that do not support HTML
$plainMessage = "Hello,\n\nYour OTP is: $otp\nIf you did not request this OTP, just ignore this email.\n\nThank you,\nTechSavvies.shop Team";

$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host       = getenv('SMTP_HOST');
    $mail->SMTPAuth   = true;
    $mail->Username   = getenv('SMTP_USERNAME');
    $mail->Password   = getenv('SMTP_PASSWORD');
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = getenv('SMTP_PORT');

    $mail->setFrom(getenv('MAIL_FROM_ADDRESS'), getenv('MAIL_FROM_NAME'));
    $mail->addAddress($email);

    $mail->isHTML(true);
    $mail->Subject = $subject;
    $mail->Body    = $htmlMessage;
    $mail->AltBody = $plainMessage;

    $mail->send();
    echo json_encode(["success" => true, "message" => "OTP sent to your email."]);

} catch (Exception $e) {
    error_log("SMTP_HOST: " . getenv('SMTP_HOST'));
    error_log("SMTP_USERNAME: " . getenv('SMTP_USERNAME'));
    error_log("SMTP_PASSWORD: " . getenv('SMTP_PASSWORD'));
    error_log("MAIL_FROM_ADDRESS: " . getenv('MAIL_FROM_ADDRESS'));
    error_log("MAIL_FROM_NAME: " . getenv('MAIL_FROM_NAME'));
    error_log("SMTP_PORT: " . getenv('SMTP_PORT'));
    error_log("Mailer Error: " . $mail->ErrorInfo);
    echo json_encode(["success" => false, "error" => "Unable to send email."]);
}
?>