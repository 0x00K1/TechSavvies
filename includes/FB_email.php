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

            // Set sender and recipient details from env vars
            $mail->setFrom(getenv('MAIL_FROM_ADDRESS'), getenv('MAIL_FROM_NAME'));
            $mail->addAddress(getenv('MAIL_TO_ADDRESS'), getenv('MAIL_TO_NAME'));
            // Add the user's email as the reply-to address
            $mail->addReplyTo($email, $name);

            // Set email format to HTML and define an enhanced HTML body design with logo
            $mail->isHTML(true);
            $mail->Subject = 'New Support Message Received! (TechSavvies.shop)';

            $htmlBody = "
                <html>
                    <head>
                        <meta charset='UTF-8'>
                        <style>
                            body {
                                font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
                                background-color: #eef2f7;
                                margin: 0;
                                padding: 20px;
                            }
                            .container {
                                background-color: #ffffff;
                                max-width: 600px;
                                margin: 0 auto;
                                padding: 30px;
                                border-radius: 10px;
                                box-shadow: 0 4px 8px rgba(0,0,0,0.1);
                            }
                            .header {
                                text-align: center;
                                padding-bottom: 20px;
                                border-bottom: 1px solid #dddddd;
                            }
                            .header img.logo {
                                max-width: 150px;
                                margin-bottom: 10px;
                            }
                            .header h2 {
                                margin: 0;
                                color: #333333;
                            }
                            .content {
                                margin-top: 20px;
                                font-size: 15px;
                                color: #555555;
                                line-height: 1.5;
                            }
                            .content p {
                                margin: 10px 0;
                            }
                            .credit-box {
                                margin-top: 30px;
                                padding: 15px;
                                border-top: 1px solid #dddddd;
                                text-align: center;
                                font-size: 13px;
                                color: #777777;
                            }
                        </style>
                    </head>
                    <body>
                        <div class='container'>
                            <div class='header'>
                                <img class='logo' src='https://i.postimg.cc/rDHDSjZw/Logo.png' alt='TechSavvies.shop Logo'>
                                <h2>New Support Message!</h2>
                            </div>
                            <div class='content'>
                                <p><strong>Name:</strong> " . htmlspecialchars($name) . "</p>
                                <p><strong>Email:</strong> " . htmlspecialchars($email) . "</p>
                                <p><strong>Message:</strong><br>" . nl2br(htmlspecialchars($message)) . "</p>
                            </div>
                            <div class='credit-box'>
                                <p>Sent from <strong>TechSavvies.shop</strong> contact section.</p>
                            </div>
                        </div>
                    </body>
                </html>
            ";

            // A plain-text alternative for email clients that do not support HTML
            $plainTextBody = "New Support Message!\n\n"
                . "Name: " . htmlspecialchars($name) . "\n"
                . "Email: " . htmlspecialchars($email) . "\n"
                . "Message:\n" . htmlspecialchars($message) . "\n\n"
                . "Sent from TechSavvies.shop contact section.";

            $mail->Body    = $htmlBody;
            $mail->AltBody = $plainTextBody;

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