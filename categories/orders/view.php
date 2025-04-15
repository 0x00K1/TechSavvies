<?php
require_once __DIR__ . '/../../assets/php/main.php';
require_once __DIR__ . '/../../includes/db.php';

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: /");
    exit;
}

// Get user data from session
$user   = $_SESSION['user'];
$isRoot = $_SESSION['is_root'] ?? false;

// Get order ID from URL parameter
$order_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($order_id <= 0) {
    $_SESSION['error_message'] = "Invalid order ID.";
    header("Location: /categories/orders/");
    exit;
}

// Variable to store order data
$order = null;
$order_items = [];
$customer_info = null;
$payment_info = null;
$error = null;

try {
    // Check if user has permission to view this order
    if ($isRoot) {
        // Root users can view any order
        $stmt = $pdo->prepare("
            SELECT o.*, c.username as customer_name, c.email as customer_email
            FROM orders o
            LEFT JOIN customers c ON o.customer_id = c.customer_id
            WHERE o.order_id = ?
        ");
        $stmt->execute([$order_id]);
    } else {
        // Regular users can only view their own orders
        $stmt = $pdo->prepare("
            SELECT o.*
            FROM orders o
            WHERE o.order_id = ? AND o.customer_id = ?
        ");
        $stmt->execute([$order_id, $user['customer_id']]);
    }
    
    $order = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$order) {
        $_SESSION['error_message'] = "Order not found or you don't have permission to view it.";
        header("Location: /categories/orders/");
        exit;
    }
    
    // Get order items
    $stmt = $pdo->prepare("
        SELECT oi.*, p.product_name, p.picture
        FROM order_items oi
        LEFT JOIN products p ON oi.product_id = p.product_id
        WHERE oi.order_id = ?
    ");
    $stmt->execute([$order_id]);
    $order_items = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Get payment information
    $stmt = $pdo->prepare("
        SELECT * FROM payments
        WHERE order_id = ?
    ");
    $stmt->execute([$order_id]);
    $payment_info = $stmt->fetch(PDO::FETCH_ASSOC);
    
} catch (PDOException $e) {
    error_log("Database error: " . $e->getMessage());
    $error = "An error occurred while retrieving order details.";
}

// Process action requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = isset($_POST['action']) ? $_POST['action'] : '';
    
    if ($action === 'cancel_order' && !$isRoot) {
        // Only allow cancellation of pending orders
        if ($order['status'] === 'pending') {
            try {
                $stmt = $pdo->prepare("
                    UPDATE orders
                    SET status = 'cancelled'
                    WHERE order_id = ? AND customer_id = ?
                ");
                $stmt->execute([$order_id, $user['customer_id']]);
                
                $_SESSION['success_message'] = "Order has been cancelled successfully.";
                header("Location: /categories/orders/view.php?id=" . $order_id);
                exit;
            } catch (PDOException $e) {
                error_log("Error cancelling order: " . $e->getMessage());
                $error = "Failed to cancel order. Please try again.";
            }
        } else {
            $error = "Only pending orders can be cancelled.";
        }
    } else if ($action === 'update_status' && $isRoot) {
        // Allow root users to update order status
        $new_status = isset($_POST['status']) ? $_POST['status'] : '';
        
        if (in_array($new_status, ['pending', 'paid', 'shipped', 'completed', 'cancelled'])) {
            try {
                $stmt = $pdo->prepare("
                    UPDATE orders
                    SET status = ?
                    WHERE order_id = ?
                ");
                $stmt->execute([$new_status, $order_id]);
                
                $_SESSION['success_message'] = "Order status updated successfully.";
                header("Location: /categories/orders/view.php?id=" . $order_id);
                exit;
            } catch (PDOException $e) {
                error_log("Error updating order status: " . $e->getMessage());
                $error = "Failed to update order status. Please try again.";
            }
        } else {
            $error = "Invalid status value.";
        }
    }
}

// Check for success/error messages from session
$success = isset($_SESSION['success_message']) ? $_SESSION['success_message'] : null;
if (isset($_SESSION['success_message'])) {
    unset($_SESSION['success_message']);
}
if (isset($_SESSION['error_message'])) {
    $error = $_SESSION['error_message'];
    unset($_SESSION['error_message']);
}

// Format payment method for display
$payment_method_display = '';
if (isset($payment_info['payment_method'])) {
    switch ($payment_info['payment_method']) {
        case 'credit_card':
            $payment_method_display = 'Credit Card';
            break;
        case 'paypal':
            $payment_method_display = 'PayPal';
            break;
        case 'cash_on_delivery':
            $payment_method_display = 'Cash on Delivery';
            break;
        default:
            $payment_method_display = ucfirst($payment_info['payment_method']);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Order Details #<?php echo $order_id; ?></title>
    <link rel="stylesheet" href="/assets/css/main.css" />
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .order-container {
            max-width: 1000px;
            margin: 0 auto;
            padding: 20px;
        }
        .order-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 15px;
        }
        .order-title {
            font-size: 24px;
            font-weight: bold;
        }
        .order-status {
            display: flex;
            align-items: center;
        }
        .status-badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 20px;
            color: white;
            font-size: 14px;
            text-align: center;
            margin-right: 10px;
        }
        .status-pending {
            background-color: #FFC107;
        }
        .status-paid {
            background-color: #2196F3;
        }
        .status-shipped {
            background-color: #9C27B0;
        }
        .status-completed {
            background-color: #4CAF50;
        }
        .status-cancelled {
            background-color: #F44336;
        }
        .order-summary {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            margin-bottom: 30px;
        }
        .summary-section {
            flex: 1;
            min-width: 250px;
            background-color: #f9f9f9;
            border-radius: 8px;
            padding: 15px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .section-title {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 10px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
        }
        .summary-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
        }
        .summary-label {
            font-weight: bold;
            color: #555;
        }
        .order-items {
            margin-top: 30px;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
        }
        .items-table th, .items-table td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }
        .items-table th {
            background-color: #f5f5f5;
        }
        .items-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .product-image {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 4px;
        }
        .order-total {
            margin-top: 20px;
            text-align: right;
            font-size: 18px;
            font-weight: bold;
        }
        .action-buttons {
            display: flex;
            justify-content: space-between;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
        }
        .btn {
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            text-align: center;
            text-decoration: none;
        }
        .btn-primary {
            background-color: #2196F3;
            color: white;
        }
        .btn-danger {
            background-color: #F44336;
            color: white;
        }
        .btn-secondary {
            background-color: #9C27B0;
            color: white;
        }
        .btn:hover {
            opacity: 0.9;
        }
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
        }
        .alert-success {
            background-color: #dff0d8;
            color: #3c763d;
            border: 1px solid #d6e9c6;
        }
        .alert-danger {
            background-color: #f2dede;
            color: #a94442;
            border: 1px solid #ebccd1;
        }
        .status-form {
            display: inline-block;
        }
        .status-form select {
            padding: 8px;
            border-radius: 4px;
            border: 1px solid #ddd;
            margin-right: 10px;
        }
    </style>
</head>
<body>
    <!-- Header Section -->
    <?php require_once __DIR__ . '/../../assets/php/header.php'; ?>

    <!-- Main Content -->
    <div class="main-content">
        <div class="order-container">
            <?php if (isset($success)): ?>
                <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
            <?php endif; ?>
            
            <?php if (isset($error)): ?>
                <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            
            <div class="order-header">
                <div class="order-title">
                    Order #<?php echo htmlspecialchars($order['order_id']); ?>
                </div>
                <div class="order-status">
                    <span class="status-badge status-<?php echo strtolower($order['status']); ?>">
                        <?php echo ucfirst($order['status']); ?>
                    </span>
                    
                    <?php if ($isRoot): ?>
                        <form class="status-form" method="POST">
                            <input type="hidden" name="action" value="update_status">
                            <select name="status">
                                <option value="pending" <?php echo $order['status'] === 'pending' ? 'selected' : ''; ?>>Pending</option>
                                <option value="paid" <?php echo $order['status'] === 'paid' ? 'selected' : ''; ?>>Paid</option>
                                <option value="shipped" <?php echo $order['status'] === 'shipped' ? 'selected' : ''; ?>>Shipped</option>
                                <option value="completed" <?php echo $order['status'] === 'completed' ? 'selected' : ''; ?>>Completed</option>
                                <option value="cancelled" <?php echo $order['status'] === 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                            </select>
                            <button type="submit" class="btn btn-primary">Update</button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="order-summary">
                <div class="summary-section">
                    <div class="section-title">Order Information</div>
                    <div class="summary-item">
                        <span class="summary-label">Order Date:</span>
                        <span><?php echo date('M j, Y, g:i A', strtotime($order['order_date'])); ?></span>
                    </div>
                    <div class="summary-item">
                        <span class="summary-label">Status:</span>
                        <span><?php echo ucfirst($order['status']); ?></span>
                    </div>
                    <div class="summary-item">
                        <span class="summary-label">Total Amount:</span>
                        <span>$<?php echo number_format($order['total_amount'], 2); ?></span>
                    </div>
                    <div class="summary-item">
                        <span class="summary-label">Items:</span>
                        <span><?php echo count($order_items); ?></span>
                    </div>
                </div>
                
                <div class="summary-section">
                    <div class="section-title">Customer Information</div>
                    <?php if ($isRoot): ?>
                        <div class="summary-item">
                            <span class="summary-label">Name:</span>
                            <span><?php echo htmlspecialchars($order['customer_name'] ?? 'N/A'); ?></span>
                        </div>
                        <div class="summary-item">
                            <span class="summary-label">Email:</span>
                            <span><?php echo htmlspecialchars($order['customer_email'] ?? 'N/A'); ?></span>
                        </div>
                        <div class="summary-item">
                            <span class="summary-label">Customer ID:</span>
                            <span><?php echo htmlspecialchars($order['customer_id']); ?></span>
                        </div>
                    <?php else: ?>
                        <div class="summary-item">
                            <span class="summary-label">Name:</span>
                            <span><?php echo htmlspecialchars($user['username']); ?></span>
                        </div>
                        <div class="summary-item">
                            <span class="summary-label">Email:</span>
                            <span><?php echo htmlspecialchars($user['email']); ?></span>
                        </div>
                    <?php endif; ?>
                </div>
                
                <div class="summary-section">
                    <div class="section-title">Payment Information</div>
                    <div class="summary-item">
                        <span class="summary-label">Payment Method:</span>
                        <span><?php echo htmlspecialchars($payment_method_display ?? 'N/A'); ?></span>
                    </div>
                    <div class="summary-item">
                        <span class="summary-label">Payment Status:</span>
                        <span><?php echo isset($payment_info['payment_status']) ? ucfirst($payment_info['payment_status']) : 'N/A'; ?></span>
                    </div>
                    <?php if (isset($payment_info['transaction_id']) && !empty($payment_info['transaction_id'])): ?>
                        <div class="summary-item">
                            <span class="summary-label">Transaction ID:</span>
                            <span><?php echo htmlspecialchars($payment_info['transaction_id']); ?></span>
                        </div>
                    <?php endif; ?>
                    <div class="summary-item">
                        <span class="summary-label">Date:</span>
                        <span><?php echo isset($payment_info['created_at']) ? date('M j, Y', strtotime($payment_info['created_at'])) : 'N/A'; ?></span>
                    </div>
                </div>
            </div>
            
            <div class="order-items">
                <div class="section-title">Order Items</div>
                <table class="items-table">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Image</th>
                            <th>Quantity</th>
                            <th>Price</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($order_items as $item): ?>
                            <tr>
                                <td>
                                    <a href="/categories/products/?product_id=<?php echo $item['product_id']; ?>">
                                        <?php echo htmlspecialchars($item['product_name']); ?>
                                    </a>
                                </td>
                                <td>
                                    <?php if (!empty($item['picture'])): ?>
                                        <img class="product-image" src="/assets/images/products/<?php echo htmlspecialchars($item['picture']); ?>" alt="<?php echo htmlspecialchars($item['product_name']); ?>">
                                    <?php else: ?>
                                        <div class="no-image">No Image</div>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo $item['quantity']; ?></td>
                                <td>$<?php echo number_format($item['price_per_unit'], 2); ?></td>
                                <td>$<?php echo number_format($item['price_per_unit'] * $item['quantity'], 2); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                
                <div class="order-total">
                    Total: $<?php echo number_format($order['total_amount'], 2); ?>
                </div>
            </div>
            
            <div class="action-buttons">
                <div>
                    <a href="/categories/orders/" class="btn btn-primary">Back to Orders</a>
                    
                    <?php if ($order['status'] === 'paid' || $order['status'] === 'shipped'): ?>
                        <a href="/categories/orders/track/?id=<?php echo $order['order_id']; ?>" class="btn btn-secondary">Track Order</a>
                    <?php endif; ?>
                </div>
                
                <?php if (!$isRoot && $order['status'] === 'pending'): ?>
                    <form method="POST" onsubmit="return confirm('Are you sure you want to cancel this order?');">
                        <input type="hidden" name="action" value="cancel_order">
                        <button type="submit" class="btn btn-danger">Cancel Order</button>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="/assets/js/main.js"></script>
</body>
</html>