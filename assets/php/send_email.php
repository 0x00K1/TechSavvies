<?php
require_once __DIR__ . '/../../includes/secure_session.php';
require_once __DIR__ . '/env_loader.php';
require 'Exception.php';
require 'PHPMailer.php';
require 'SMTP.php';

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
    'MAIL_FROM_NAME',
    'MAIL_TO_ADDRESS',
    'MAIL_TO_NAME'
]);

try {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $name = $_POST['name'] ?? '';
        $email = $_POST['email'] ?? '';
        $message = $_POST['message'] ?? '';

        if (empty($name) || empty($email) || empty($message)) {
            echo json_encode([
                'success' => false,
                'message' => 'Please fill in all fields'
            ]);
            exit;
        }

        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host       = getenv('SMTP_HOST');
            $mail->SMTPAuth   = true;
            $mail->Username   = getenv('SMTP_USERNAME');
            $mail->Password   = getenv('SMTP_PASSWORD');
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = getenv('SMTP_PORT');
            $mail->Timeout    = 10;

            // Set the sender details from env vars
            $mail->setFrom(getenv('MAIL_FROM_ADDRESS'), getenv('MAIL_FROM_NAME'));
            // Send the feedback message to your email address, not the user's.
            $mail->addAddress(getenv('MAIL_TO_ADDRESS'), getenv('MAIL_TO_NAME'));
            // Add the user’s email as the reply-to address
            $mail->addReplyTo($email, $name);

            $mail->isHTML(true);
            $mail->Subject = 'New Support Message';
            $mail->Body = "
                <h3>New Support Message</h3>
                <p><strong>Name:</strong> " . htmlspecialchars($name) . "</p>
                <p><strong>Email:</strong> " . htmlspecialchars($email) . "</p>
                <p><strong>Message:</strong><br>" . nl2br(htmlspecialchars($message)) . "</p>
            ";

            $mail->send();
            echo json_encode([
                'success' => true,
                'message' => 'Message sent successfully!'
            ]);
        } catch (Exception $e) {
            error_log('Mailer Error: ' . $mail->ErrorInfo);
            echo json_encode([
                'success' => false,
                'message' => 'Message could not be sent. Please try again later.'
            ]);
        }
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Invalid request method'
        ]);
    }
} catch (Exception $e) {
    error_log('General Error: ' . $e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => 'An error occurred. Please try again later.'
    ]);
}
?>