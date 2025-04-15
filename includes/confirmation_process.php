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

// Get the order ID from request
$orderId = filter_var($_GET['order_id'] ?? 0, FILTER_SANITIZE_NUMBER_INT);
$customerId = $_SESSION['user']['customer_id'] ?? 0;

if (!$orderId) {
    http_response_code(400); // Bad Request
    echo json_encode(['error' => 'Order ID is required']);
    exit;
}

// Begin transaction
$pdo->beginTransaction();

try {
    // Check if the order is already paid and belongs to this customer
    $stmt = $pdo->prepare("
        SELECT o.order_id, o.status, o.total_amount, p.payment_id, p.payment_status
        FROM orders o
        LEFT JOIN payments p ON o.order_id = p.order_id
        WHERE o.order_id = :order_id 
          AND o.customer_id = :customer_id
    ");
    $stmt->execute([
        ':order_id' => $orderId,
        ':customer_id' => $customerId
    ]);
    $orderData = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$orderData) {
        // Order not found or doesn't belong to this customer
        $pdo->rollBack();
        http_response_code(404); // Not Found
        echo json_encode(['error' => 'Order not found']);
        exit;
    }

    // Check payment status
    if ($orderData['payment_status'] !== 'completed') {
        // Payment not completed
        $pdo->rollBack();
        http_response_code(400); // Bad Request
        echo json_encode(['error' => 'Payment has not been completed for this order']);
        exit;
    }

    // If order status is still 'pending', update it to 'paid' if needed
    if ($orderData['status'] === 'pending') {
        $stmt = $pdo->prepare("UPDATE orders SET status = 'paid' WHERE order_id = :order_id");
        $stmt->execute([':order_id' => $orderId]);
    }

    // Get order items to prepare the response
    $stmt = $pdo->prepare("
        SELECT oi.product_id, oi.quantity, oi.price_per_unit, 
               p.product_name, p.picture, p.color, p.size
        FROM order_items oi
        JOIN products p ON oi.product_id = p.product_id
        WHERE oi.order_id = :order_id
    ");
    $stmt->execute([':order_id' => $orderId]);
    $orderItems = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Get customer information and the primary address
    $stmt = $pdo->prepare("
        SELECT c.email, c.username, a.address_line1, a.address_line2, a.city, 
               a.state, a.postal_code, a.country
        FROM customers c
        LEFT JOIN addresses a ON c.customer_id = a.customer_id AND a.is_primary = 1
        WHERE c.customer_id = :customer_id
        LIMIT 1
    ");
    $stmt->execute([':customer_id' => $customerId]);
    $customerInfo = $stmt->fetch(PDO::FETCH_ASSOC);

    // Get payment information
    $stmt = $pdo->prepare("
        SELECT payment_method, transaction_id, amount, created_at
        FROM payments
        WHERE order_id = :order_id AND payment_status = 'completed'
        LIMIT 1
    ");
    $stmt->execute([':order_id' => $orderId]);
    $paymentInfo = $stmt->fetch(PDO::FETCH_ASSOC);

    // Commit transaction
    $pdo->commit();

    // Format the order data for display
    $formattedItems = [];
    $subtotal = 0;
    
    foreach ($orderItems as $item) {
        $itemTotal = $item['quantity'] * $item['price_per_unit'];
        $subtotal += $itemTotal;
        
        $formattedItems[] = [
            'name' => $item['product_name'],
            'price' => (float)$item['price_per_unit'],
            'quantity' => (int)$item['quantity'],
            'color' => $item['color'],
            'size' => $item['size'],
            'image' => '/assets/images/products/' . ($item['picture'] ?: 'placeholder.png')
        ];
    }
    
    $shipping = 10.00; // Fixed shipping cost
    $tax = $subtotal * 0.08; // 8% tax
    $total = $subtotal + $shipping + $tax;
    
    // Create order object to return to frontend
    $orderDetails = [
        'success' => true,
        'orderId' => $orderId,
        'orderDate' => date('Y-m-d H:i:s'),
        'items' => $formattedItems,
        'customer' => [
            'name' => $customerInfo['username'],
            'email' => $customerInfo['email'],
            'phone' => '', // Add phone if you store it
            'address' => $customerInfo['address_line1'] . ($customerInfo['address_line2'] ? ', ' . $customerInfo['address_line2'] : ''),
            'city' => $customerInfo['city'],
            'state' => $customerInfo['state'],
            'zip' => $customerInfo['postal_code'],
            'country' => $customerInfo['country']
        ],
        'payment' => [
            'method' => $paymentInfo['payment_method'],
            'transactionId' => $paymentInfo['transaction_id'],
            'date' => $paymentInfo['created_at']
        ],
        'totals' => [
            'subtotal' => number_format($subtotal, 2, '.', ''),
            'shipping' => number_format($shipping, 2, '.', ''),
            'tax' => number_format($tax, 2, '.', ''),
            'total' => number_format($total, 2, '.', '')
        ]
    ];

    // Store order details in session for use in confirmation page
    $_SESSION['confirmed_order'] = $orderDetails;
    
    // Return success response
    echo json_encode($orderDetails);
    
} catch (Exception $e) {
    // Rollback transaction on error
    $pdo->rollBack();
    
    // Log the error
    error_log('Confirmation processing error: ' . $e->getMessage());
    
    // Return error response
    http_response_code(500); // Internal Server Error
    echo json_encode(['error' => 'Failed to process order confirmation. Please try again.']);
}
?>