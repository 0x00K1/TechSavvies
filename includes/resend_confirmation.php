<?php
require_once __DIR__ . '/secure_session.php';
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/../assets/php/env_loader.php';

require_once __DIR__ . '/../assets/php/Exception.php';
require_once __DIR__ . '/../assets/php/PHPMailer.php';
require_once __DIR__ . '/../assets/php/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Return JSON
header("Content-Type: application/json");
header("X-Content-Type-Options: nosniff");
header("X-Frame-Options: DENY");

// Verify that user is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    http_response_code(403); // Forbidden
    echo json_encode(['error' => 'Unauthorized access']);
    exit;
}

// Verify that the request is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405); // Method Not Allowed
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

// Check for required SMTP environment variables
checkRequiredEnv([
    'SMTP_HOST',
    'SMTP_PORT',
    'SMTP_USERNAME',
    'SMTP_PASSWORD',
    'MAIL_FROM_ADDRESS',
    'MAIL_FROM_NAME'
]);

// Read JSON input
$input = json_decode(file_get_contents('php://input'), true);
$orderId   = filter_var($input['order_id'] ?? '', FILTER_SANITIZE_NUMBER_INT);
$email     = filter_var($input['email'] ?? '', FILTER_SANITIZE_EMAIL);
$customerId = $_SESSION['user']['customer_id'] ?? 0;

// Validate inputs
if (empty($orderId) || empty($email)) {
    http_response_code(400);
    echo json_encode(['error' => 'Order ID and email are required']);
    exit;
}

// Check if the order belongs to the logged-in user
$stmt = $pdo->prepare("
    SELECT o.order_id, o.status, o.total_amount
    FROM orders o
    WHERE o.order_id = :order_id
      AND o.customer_id = :customer_id
");
$stmt->execute([
    ':order_id' => $orderId,
    ':customer_id' => $customerId
]);
$order = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$order) {
    http_response_code(404);
    echo json_encode(['error' => 'Order not found or access denied']);
    exit;
}

// --- Enforce a maximum of three email sends per order ---
if (!isset($_SESSION['resend_count'])) {
    $_SESSION['resend_count'] = [];
}
if (!isset($_SESSION['resend_count'][$orderId])) {
    $_SESSION['resend_count'][$orderId] = 0;
}
if ($_SESSION['resend_count'][$orderId] >= 3) {
    http_response_code(429); // Too Many Requests
    echo json_encode([
        'error' => 'Resend email limit reached. You cannot request more than three emails.'
    ]);
    exit;
}

// Retrieve customer's name from session (if available)
$customerName = $_SESSION['user']['username'] ?? 'Valued Customer';

// Build enhanced confirmation email content
$subject     = "Your Order Confirmation #{$order['order_id']} from TechSavvies";
$orderStatus = $order['status'];
$orderTotal  = number_format($order['total_amount'], 2);

// Enhanced HTML email
$htmlMessage = <<<HTML
<html>
<head>
  <meta charset="UTF-8">
  <title>Order Confirmation</title>
  <style>
    body {
      font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
      background-color: #f4f4f4;
      margin: 0;
      padding: 0;
    }
    .header {
      background: linear-gradient(135deg, #0117ff, #8d07cc, #d42d2d);
      padding: 20px;
      text-align: center;
      color: #fff;
    }
    .header h1 {
      margin: 0;
      font-size: 28px;
    }
    .container {
      max-width: 600px;
      margin: 20px auto;
      background-color: #ffffff;
      border-radius: 8px;
      overflow: hidden;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    .content {
      padding: 20px;
      color: #555;
      line-height: 1.6;
    }
    .content h2 {
      color: #333;
    }
    .order-details {
      margin: 20px 0;
      border: 1px solid #ddd;
      border-radius: 4px;
      padding: 15px;
      background: #f9f9f9;
    }
    .order-details p {
      margin: 8px 0;
    }
    .cta {
      text-align: center;
      margin: 20px 0;
    }
    .cta a {
      background-color: #0117ff;
      color: #fff;
      padding: 12px 20px;
      text-decoration: none;
      border-radius: 4px;
      font-weight: bold;
    }
    .footer {
      text-align: center;
      font-size: 12px;
      color: #777;
      padding: 15px;
      background: #f4f4f4;
    }
  </style>
</head>
<body>
  <div class="header">
    <h1>TechSavvies Order Confirmation</h1>
  </div>
  <div class="container">
    <div class="content">
      <h2>Hi {$customerName},</h2>
      <p>Thank you for shopping with <strong>TechSavvies</strong>. We are excited to let you know that your order <strong>#{$order['order_id']}</strong> has been received.</p>
      <div class="order-details">
        <p><strong>Order Status:</strong> {$orderStatus}</p>
        <p><strong>Order Total:</strong> \${$orderTotal}</p>
      </div>
      <p>We are now processing your order and will update you as soon as your items are on their way. You can track your order or view more details by clicking the button below.</p>
      <div class="cta">
        <a href="http://localhost:3000/categories/orders/track?id={$order['order_id']}">Track Your Order</a>
      </div>
      <p>If you have any questions or need further assistance, simply reply to this email. We're here to help!</p>
      <p>Thank you for choosing TechSavvies.</p>
      <p>Best regards,<br>The TechSavvies Team</p>
    </div>
  </div>
  <div class="footer">
    &copy; 2025 TechSavvies, All Rights Reserved.
  </div>
</body>
</html>
HTML;

// Enhanced plain text email
$plainMessage = "Hi {$customerName},\n\n" .
  "Thank you for shopping with TechSavvies. Your order #{$order['order_id']} has been received.\n\n" .
  "Order Details:\n" .
  "   Status: {$orderStatus}\n" .
  "   Order Total: \${$orderTotal}\n\n" .
  "We are processing your order and will update you once your items are on their way. " .
  "You can track your order at: http://localhost:3000/categories/orders/track?id={$order['order_id']}\n\n" .
  "If you have any questions, please reply to this email.\n\n" .
  "Thank you for choosing TechSavvies.\n" .
  "Best regards,\n" .
  "The TechSavvies Team\n";

// Send with PHPMailer
$mail = new PHPMailer(true);

try {
    // Use the environment variables
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

    // Increase the counter after a successful send
    $_SESSION['resend_count'][$orderId]++;

    echo json_encode([
        'success' => true,
        'message' => 'Confirmation email resent successfully',
    ]);
} catch (Exception $e) {
    // Log error details
    error_log("Mailer Error: " . $mail->ErrorInfo);
    http_response_code(500);
    echo json_encode([
        'error' => 'Failed to send confirmation email. Please try again.'
    ]);
}
?>