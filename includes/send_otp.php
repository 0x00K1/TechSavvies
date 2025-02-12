<?php
session_start();
header("Content-Type: application/json");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../assets/php/Exception.php';
require '../assets/php/PHPMailer.php';
require '../assets/php/SMTP.php';

// Get the POST JSON input
$data = json_decode(file_get_contents("php://input"), true);
if (!isset($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
    echo json_encode(["success" => false, "error" => "Invalid email address."]);
    exit;
}

$email = $data['email'];

// Generate a 6-digit OTP and store it in the session [We'll update it later, "security reasons"]
$otp = rand(100000, 999999);
$_SESSION['otp'] = $otp;
$_SESSION['otp_email'] = $email;

// Prepare email content
$subject = "Your OTP Code for TechSavvies";
$message = "Hello,\n\nYour OTP code is: $otp\n\nPlease use this code to verify your email address.\n\nThank you.";

// Create a new PHPMailer instance
$mail = new PHPMailer(true);

try {
    // Server settings
    $mail->isSMTP();
    $mail->Host       = 'smtp.mailersend.net';
    $mail->SMTPAuth   = true;
    $mail->Username   = '';
    $mail->Password   = '';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // or 'tls'
    $mail->Port       = 587;

    // Recipients
    $mail->setFrom('', 'TechSavvies');
    $mail->addAddress($email);

    // Content
    $mail->isHTML(false); // Set email format to plain text
    $mail->Subject = $subject;
    $mail->Body    = $message;
    $mail->AltBody = $message;

    $mail->send();

    echo json_encode(["success" => true]);
} catch (Exception $e) {
    echo json_encode(["success" => false, "error" => "Mailer Error: " . $mail->ErrorInfo]);
}
?>
