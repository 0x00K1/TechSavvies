<?php
// Enforce secure session
require_once __DIR__ . '/secure_session.php';
require_once __DIR__ . '/db.php';

// Verify user is logged in
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

// Get POST data and validate
$inputData = json_decode(file_get_contents('php://input'), true);

// Validate required fields
$requiredFields = ['order_id', 'payment_method', 'amount', 'card_details'];
foreach ($requiredFields as $field) {
    if (empty($inputData[$field]) && $field !== 'card_details') {
        http_response_code(400); // Bad Request
        echo json_encode(['error' => "Missing required field: $field"]);
        exit;
    }
}

// Sanitize inputs
$orderId = filter_var($inputData['order_id'], FILTER_SANITIZE_NUMBER_INT);
$paymentMethod = filter_var($inputData['payment_method'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$amount = filter_var($inputData['amount'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
$customerId = $_SESSION['user']['customer_id'] ?? 0;

// Validate that the order exists and belongs to this customer
$stmt = $pdo->prepare("
    SELECT o.order_id, o.status, o.total_amount 
    FROM orders o 
    WHERE o.order_id = :order_id 
    AND o.customer_id = :customer_id
    AND o.status = 'pending'
");
$stmt->execute([
    ':order_id' => $orderId,
    ':customer_id' => $customerId
]);
$order = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$order) {
    http_response_code(404); // Not Found
    echo json_encode(['error' => 'Order not found or not available for payment']);
    exit;
}

// Validate payment amount matches order total
if (abs($amount - $order['total_amount']) > 0.01) { // Allow small rounding differences
    http_response_code(400); // Bad Request
    echo json_encode(['error' => 'Payment amount does not match order total']);
    exit;
}

// Validate payment method
$validPaymentMethods = ['credit_card', 'paypal', 'cash_on_delivery'];
if (!in_array($paymentMethod, $validPaymentMethods)) {
    http_response_code(400); // Bad Request
    echo json_encode(['error' => 'Invalid payment method']);
    exit;
}

// Begin transaction
$pdo->beginTransaction();

try {
    // Generate a transaction ID
    $transactionId = 'TXN' . time() . rand(1000, 9999);
    
    // In a real payments.. we would integrate with a payment gateway here

    // Insert payment record
    $stmt = $pdo->prepare("
        INSERT INTO payments (
            order_id, customer_id, payment_method, payment_status, 
            transaction_id, amount, created_at
        ) VALUES (
            :order_id, :customer_id, :payment_method, 'completed', 
            :transaction_id, :amount, NOW()
        )
    ");
    $stmt->execute([
        ':order_id' => $orderId,
        ':customer_id' => $customerId,
        ':payment_method' => $paymentMethod,
        ':transaction_id' => $transactionId,
        ':amount' => $amount
    ]);
    
    // Update order status to 'paid'
    $stmt = $pdo->prepare("
        UPDATE orders SET status = 'paid' WHERE order_id = :order_id
    ");
    $stmt->execute([':order_id' => $orderId]);
    
    // Commit transaction
    $pdo->commit();
    
    // Return success response
    echo json_encode([
        'success' => true,
        'message' => 'Payment processed successfully',
        'transaction_id' => $transactionId,
        'redirect_url' => "/categories/confirmation?order_id=$orderId"
    ]);
    
} catch (Exception $e) {
    // Rollback transaction on error
    $pdo->rollBack();

    error_log('Payment processing error: ' . $e->getMessage());
    
    // Return error response
    http_response_code(500); // Internal Server Error
    echo json_encode(['error' => 'Payment processing failed. Please try again.']);
}
?>