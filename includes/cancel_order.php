<?php
require_once __DIR__ . '/secure_session.php';
require_once __DIR__ . '/db.php';

// Get the order ID from request
$orderId = filter_var($_GET['order_id'] ?? 0, FILTER_SANITIZE_NUMBER_INT);

if (!$orderId) {
    http_response_code(400); // Bad Request
    echo 'Order ID is required';
    exit;
}

// Begin transaction
$pdo->beginTransaction();

try {
    // Lock the order row for update
    $stmt = $pdo->prepare("SELECT customer_id, status FROM orders WHERE order_id = :order_id FOR UPDATE");
    $stmt->execute([':order_id' => $orderId]);
    $order = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$order) {
        throw new Exception("Order not found.");
    }
    
    if ($order['status'] !== 'pending') {
        throw new Exception("Only pending orders can be cancelled.");
    }
    
    // Fetch all order items
    $stmtItems = $pdo->prepare("SELECT product_id, quantity FROM order_items WHERE order_id = :order_id");
    $stmtItems->execute([':order_id' => $orderId]);
    $orderItems = $stmtItems->fetchAll(PDO::FETCH_ASSOC);
    
    // Return stock for each order item
    foreach ($orderItems as $item) {
        $stmtUp = $pdo->prepare("UPDATE products SET stock = stock + :qty WHERE product_id = :pid");
        $stmtUp->execute([
            ':qty' => $item['quantity'],
            ':pid' => $item['product_id']
        ]);
    }
    
    // Update the order status to 'cancelled'
    $stmtCancel = $pdo->prepare("UPDATE orders SET status = 'cancelled' WHERE order_id = :order_id");
    $stmtCancel->execute([':order_id' => $orderId]);
    
    $pdo->commit();
    echo "Order {$orderId} has been cancelled successfully and stock has been returned.";
} catch (Exception $e) {
    $pdo->rollBack();
    echo "Failed to cancel order: " . $e->getMessage();
    exit;
}
?>