<?php
require_once __DIR__ . '/secure_session.php';
require_once __DIR__ . '/db.php';

// Get the order ID from request
$orderId = filter_var($_GET['order_id'] ?? '', FILTER_SANITIZE_NUMBER_INT);

if (!$orderId) {
    http_response_code(400); // Bad Request
    echo json_encode(['error' => 'Order ID is required']);
    exit;
}

// Determine if user is logged in
$isLoggedIn = isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
$customerId = $isLoggedIn ? ($_SESSION['user']['customer_id'] ?? 0) : 0;

try {
    // Begin database query
    if ($isLoggedIn) {
        // For logged in users, check if the order belongs to them
        $stmt = $pdo->prepare("
            SELECT o.order_id, o.customer_id, o.status, o.order_date, o.total_amount,
                   COUNT(oi.product_id) as item_count,
                   p.payment_method
            FROM orders o
            LEFT JOIN order_items oi ON o.order_id = oi.order_id
            LEFT JOIN payments p ON o.order_id = p.order_id
            WHERE o.order_id = :order_id 
            " . ($customerId ? "AND o.customer_id = :customer_id" : "") . "
            GROUP BY o.order_id, o.customer_id, o.status, o.order_date, o.total_amount, p.payment_method
        ");
        
        if ($customerId) {
            $stmt->execute([
                ':order_id' => $orderId,
                ':customer_id' => $customerId
            ]);
        } else {
            $stmt->execute([':order_id' => $orderId]);
        }
    } else {
        // [!] Bug
        // Guests can currently access any order details using only the order_id,
        // which poses a security risk (e.g., enumeration, data exposure).
        // Solution: Secure tracking_token and require it for all guest access.
        // This way, only users with the unique tracking link can view their order.

        // For guest users with correct order ID
        // $stmt = $pdo->prepare("
        //     SELECT o.order_id, o.customer_id, o.status, o.order_date, o.total_amount,
        //            COUNT(oi.product_id) as item_count,
        //            p.payment_method
        //     FROM orders o
        //     LEFT JOIN order_items oi ON o.order_id = oi.order_id
        //     LEFT JOIN payments p ON o.order_id = p.order_id
        //     WHERE o.order_id = :order_id
        //     GROUP BY o.order_id, o.customer_id, o.status, o.order_date, o.total_amount, p.payment_method
        // ");
        // $stmt->execute([':order_id' => $orderId]);

        // Order not found or access denied [TEMP]
        http_response_code(404); // Not Found
        echo json_encode(['error' => 'Order not found or access denied']);
        exit;
    }
    
    $orderData = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$orderData) {
        // Order not found or access denied
        http_response_code(404); // Not Found
        echo json_encode(['error' => 'Order not found or access denied']);
        exit;
    }

    // Get customer shipping address
    $stmt = $pdo->prepare("
        SELECT c.username as name, a.address_line1, a.address_line2, a.city, 
               a.state, a.postal_code as zip, a.country
        FROM customers c
        LEFT JOIN addresses a ON c.customer_id = a.customer_id
        WHERE c.customer_id = :customer_id
        LIMIT 1
    ");
    $stmt->execute([':customer_id' => $orderData['customer_id']]);
    $shippingInfo = $stmt->fetch(PDO::FETCH_ASSOC);

    // Format shipping address
    $shippingAddress = [
        'name' => $shippingInfo['name'] ?? 'Customer',
        'address' => $shippingInfo ? trim($shippingInfo['address_line1'] . ' ' . ($shippingInfo['address_line2'] ?? '')) : 'No address on file',
        'city' => $shippingInfo['city'] ?? '',
        'state' => $shippingInfo['state'] ?? '',
        'zip' => $shippingInfo['zip'] ?? '',
        'country' => $shippingInfo['country'] ?? 'United States',
    ];

    // Create simulated tracking information based on order status
    $tracking = null;
    $estimatedDelivery = null;
    
    if ($orderData['status'] == 'shipped' || $orderData['status'] == 'completed') {
        // If order is shipped or completed, generate fictional tracking info
        $tracking = [
            'carrier' => 'Express Courier',
            'number' => 'TRK' . $orderId . date('Ymd'),
            'url' => 'https://www.example.com/track?num=TRK' . $orderId . date('Ymd')
        ];
        
        // Calculate estimated delivery (5 business days from order date)
        $orderDate = new DateTime($orderData['order_date']);
        $orderDate->modify('+5 weekdays');
        $estimatedDelivery = $orderDate->format('Y-m-d\TH:i:s');
    }

    // Generate status updates based on order status
    $statusUpdates = [];
    
    // Order placed status (always present)
    $statusUpdates[] = [
        'status' => 'Order Received',
        'status_type' => 'order_placed',
        'timestamp' => $orderData['order_date'],
        'description' => 'Your order has been received and is now being processed.'
    ];
    
    // Payment status
    if ($orderData['status'] != 'pending') {
        // Get payment timestamp from database
        $stmt = $pdo->prepare("
            SELECT created_at FROM payments 
            WHERE order_id = :order_id AND payment_status = 'completed'
            LIMIT 1
        ");
        $stmt->execute([':order_id' => $orderId]);
        $paymentData = $stmt->fetch(PDO::FETCH_ASSOC);
        
        $paymentTime = $paymentData ? $paymentData['created_at'] : date('Y-m-d H:i:s', strtotime($orderData['order_date'] . ' +1 hour'));
        
        $statusUpdates[] = [
            'status' => 'Payment Confirmed',
            'status_type' => 'processing',
            'timestamp' => $paymentTime,
            'description' => 'Your payment has been successfully processed.'
        ];
    }
    
    // Add processing status
    if (in_array($orderData['status'], ['paid', 'shipped', 'completed'])) {
        $processingTime = date('Y-m-d H:i:s', strtotime($orderData['order_date'] . ' +1 day'));
        $statusUpdates[] = [
            'status' => 'Processing Order',
            'status_type' => 'processing',
            'timestamp' => $processingTime,
            'description' => 'Your order is being prepared for shipping.'
        ];
    }
    
    // Add shipped status
    if (in_array($orderData['status'], ['shipped', 'completed'])) {
        $shippedTime = date('Y-m-d H:i:s', strtotime($orderData['order_date'] . ' +2 days'));
        $statusUpdates[] = [
            'status' => 'Order Shipped',
            'status_type' => 'shipped',
            'timestamp' => $shippedTime,
            'description' => 'Your order has been shipped and is on its way to you.'
        ];
    }
    
    // Add out for delivery status
    if ($orderData['status'] == 'completed') {
        $outForDeliveryTime = date('Y-m-d H:i:s', strtotime($orderData['order_date'] . ' +4 days'));
        $statusUpdates[] = [
            'status' => 'Out For Delivery',
            'status_type' => 'out_for_delivery',
            'timestamp' => $outForDeliveryTime,
            'description' => 'Your package is out for delivery and will arrive today.'
        ];
        
        // Add delivered status
        $deliveredTime = date('Y-m-d H:i:s', strtotime($orderData['order_date'] . ' +4 days +5 hours'));
        $statusUpdates[] = [
            'status' => 'Delivered',
            'status_type' => 'delivered',
            'timestamp' => $deliveredTime,
            'description' => 'Your package has been delivered.'
        ];
    }
    
    // Add cancelled status if applicable
    if ($orderData['status'] == 'cancelled') {
        $cancelledTime = date('Y-m-d H:i:s', strtotime($orderData['order_date'] . ' +1 day'));
        $statusUpdates[] = [
            'status' => 'Order Cancelled',
            'status_type' => 'cancelled',
            'timestamp' => $cancelledTime,
            'description' => 'Your order has been cancelled.'
        ];
    }

    // Create response object
    $response = [
        'order_id' => $orderData['order_id'],
        'order_date' => $orderData['order_date'],
        'item_count' => (int)$orderData['item_count'],
        'order_total' => (float)$orderData['total_amount'],
        'payment_method' => $orderData['payment_method'] ?? 'Online Payment',
        'status' => $orderData['status'],
        'shipping' => $shippingAddress,
        'tracking' => $tracking,
        'estimated_delivery' => $estimatedDelivery,
        'status_updates' => $statusUpdates
    ];

    // Return JSON response
    header('Content-Type: application/json');
    echo json_encode($response);
    
} catch (Exception $e) {
    // Log the error
    error_log('Order tracking error: ' . $e->getMessage());
    
    // Return error response
    http_response_code(500); // Internal Server Error
    echo json_encode(['error' => 'Failed to retrieve order tracking information. Please try again.']);
}
?>