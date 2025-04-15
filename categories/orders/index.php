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
$isRoot = $_SESSION['is_root'];

// Handle pagination
$limit = 20;  // number of orders per page
$page  = max(1, (int)($_GET['page'] ?? 1));
$offset = ($page - 1) * $limit;

// Set default sort order
$sort_by    = isset($_GET['sort'])  ? $_GET['sort']  : 'order_date';
$sort_order = isset($_GET['order']) ? $_GET['order'] : 'desc';

// Validate sort options to prevent SQL injection
$allowed_sort_fields = ['order_id', 'order_date', 'status', 'total_amount'];
if (!in_array($sort_by, $allowed_sort_fields)) {
    $sort_by = 'order_date';
}
$allowed_sort_orders = ['asc', 'desc'];
if (!in_array($sort_order, $allowed_sort_orders)) {
    $sort_order = 'desc';
}

// We'll need the total count for pagination
$total_count = 0;
$orders      = [];
$error       = null;
$success     = null;

try {
    if ($isRoot) {
        // Count total orders
        $countStmt = $pdo->prepare("
            SELECT COUNT(*) as cnt 
            FROM orders
        ");
        $countStmt->execute();
        $total_row = $countStmt->fetch(PDO::FETCH_ASSOC);
        $total_count = $total_row ? (int)$total_row['cnt'] : 0;

        // For root users, get all orders (paginated)
        $stmt = $pdo->prepare("
            SELECT o.*, 
                   c.username as customer_name,
                   COUNT(oi.product_id) as total_items,
                   p.payment_status
            FROM orders o
            LEFT JOIN customers c ON o.customer_id = c.customer_id
            LEFT JOIN order_items oi ON o.order_id = oi.order_id
            LEFT JOIN payments p ON o.order_id = p.order_id
            GROUP BY o.order_id
            ORDER BY {$sort_by} {$sort_order}
            LIMIT :limit OFFSET :offset
        ");
        $stmt->bindValue(':limit',  (int)$limit,  PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        $stmt->execute();
    } else {
        // Count total orders for this user
        $countStmt = $pdo->prepare("
            SELECT COUNT(*) as cnt
            FROM orders 
            WHERE customer_id = ?
        ");
        $countStmt->execute([$user['customer_id']]);
        $total_row   = $countStmt->fetch(PDO::FETCH_ASSOC);
        $total_count = $total_row ? (int)$total_row['cnt'] : 0;

        // For regular customers, only get their orders (paginated).
        // Also retrieve one product_id from each order for potential rating link
        $stmt = $pdo->prepare("
            SELECT o.*, 
                   COUNT(oi.product_id) as total_items,
                   p.payment_status,
                   (SELECT oi2.product_id
                    FROM order_items oi2
                    WHERE oi2.order_id = o.order_id
                    LIMIT 1
                   ) as product_id
            FROM orders o
            LEFT JOIN order_items oi ON o.order_id = oi.order_id
            LEFT JOIN payments p ON o.order_id = p.order_id
            WHERE o.customer_id = :cust_id
            GROUP BY o.order_id
            ORDER BY {$sort_by} {$sort_order}
            LIMIT :limit OFFSET :offset
        ");
        $stmt->bindValue(':cust_id', (int)$user['customer_id'], PDO::PARAM_INT);
        $stmt->bindValue(':limit',   (int)$limit,               PDO::PARAM_INT);
        $stmt->bindValue(':offset',  (int)$offset,              PDO::PARAM_INT);
        $stmt->execute();
    }

    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    error_log("Database error fetching orders: " . $e->getMessage());
    $error = "Failed to load orders.";
}

// Calculate total pages
$total_pages = (int)ceil($total_count / $limit);

// Process tracking request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'track_order') {
    $order_id = intval($_POST['order_id'] ?? 0);

    if ($order_id <= 0) {
        $error = "Invalid order ID.";
    } else {
        // Verify the user has permission to track this order
        $canTrack = false;
        
        if ($isRoot) {
            $canTrack = true; // Root users can track any order
        } else {
            $stmt = $pdo->prepare("SELECT order_id FROM orders WHERE order_id = ? AND customer_id = ?");
            $stmt->execute([$order_id, $user['customer_id']]);
            if ($stmt->fetch(PDO::FETCH_ASSOC)) {
                $canTrack = true;
            }
        }
        
        if (!$canTrack) {
            $error = "You don't have permission to track this order.";
        } else {
            header("Location: /categories/orders/track/?id={$order_id}");
            exit;
        }
    }
    
    if (isset($error)) {
        $_SESSION['error_message'] = $error;
    }
    
    // redirect back
    header("Location: /categories/orders/?sort={$sort_by}&order={$sort_order}&page={$page}");
    exit;
}

if (isset($_SESSION['success_message'])) {
    $success = $_SESSION['success_message'];
    unset($_SESSION['success_message']);
}
if (isset($_SESSION['error_message'])) {
    $error = $_SESSION['error_message'];
    unset($_SESSION['error_message']);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title><?php echo $isRoot ? 'Manage Orders' : 'Your Orders'; ?></title>
    <link rel="stylesheet" href="/assets/css/main.css" />
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .orders-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        .orders-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        .orders-title {
            font-size: 24px;
            font-weight: bold;
        }
        .sort-options {
            display: flex;
            align-items: center;
        }
        .sort-options label {
            margin-right: 10px;
        }
        .orders-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .orders-table th, .orders-table td {
            border: 1px solid #ddd;
            padding: 12px;
        }
        .orders-table th {
            background-color: #f5f5f5;
            text-align: left;
        }
        .orders-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .orders-table tr:hover {
            background-color: #f1f1f1;
        }
        .status-badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 20px;
            color: white;
            font-size: 12px;
            text-align: center;
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
        .action-buttons {
            display: flex;
            gap: 5px;
        }
        .view-btn, .track-btn, .rate-btn {
            padding: 6px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 12px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
        }
        .view-btn {
            background-color: #2196F3;
            color: white;
        }
        .track-btn {
            background-color: #9C27B0;
            color: white;
        }
        .rate-btn {
            background-color: #4CAF50;
            color: white;
        }
        .view-btn:hover, .track-btn:hover, .rate-btn:hover {
            opacity: 0.9;
        }
        .no-orders {
            text-align: center;
            padding: 30px;
            color: #666;
            font-style: italic;
        }
        .sort-link {
            color: #333;
            text-decoration: none;
            display: flex;
            align-items: center;
        }
        .sort-link:hover {
            text-decoration: underline;
        }
        .sort-indicator {
            margin-left: 5px;
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
        /* Pagination links */
        .pagination {
            margin-top: 20px;
            text-align: center;
        }
        .pagination a,
        .pagination span {
            display: inline-block;
            padding: 8px 12px;
            margin: 0 3px;
            border: 1px solid #ddd;
            border-radius: 4px;
            text-decoration: none;
            color: #333;
        }
        .pagination a:hover {
            background-color: #f1f1f1;
        }
        .pagination .active {
            background-color: #2196F3;
            color: white !important;
            border-color: #2196F3;
        }
    </style>
</head>
<body>
    <!-- Header Section -->
    <?php require_once __DIR__ . '/../../assets/php/header.php'; ?>

    <!-- Main Content -->
    <div class="main-content">
        <div class="orders-container">
            <div class="orders-header">
                <div class="orders-title"><?php echo $isRoot ? 'Manage Orders' : 'Your Orders'; ?></div>
                <div class="sort-options">
                    <label for="sort-select">Sort by:</label>
                    <select id="sort-select" onchange="sortOrders(this.value)">
                        <option value="order_date-desc"   <?php echo ($sort_by == 'order_date'   && $sort_order == 'desc') ? 'selected' : ''; ?>>Newest First</option>
                        <option value="order_date-asc"    <?php echo ($sort_by == 'order_date'   && $sort_order == 'asc')  ? 'selected' : ''; ?>>Oldest First</option>
                        <option value="total_amount-desc" <?php echo ($sort_by == 'total_amount' && $sort_order == 'desc') ? 'selected' : ''; ?>>Price: High to Low</option>
                        <option value="total_amount-asc"  <?php echo ($sort_by == 'total_amount' && $sort_order == 'asc')  ? 'selected' : ''; ?>>Price: Low to High</option>
                    </select>
                </div>
            </div>
            
            <?php if (isset($success)): ?>
                <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
            <?php endif; ?>
            
            <?php if (isset($error)): ?>
                <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            
            <?php if (empty($orders)): ?>
                <div class="no-orders">No orders found.</div>
            <?php else: ?>
                <table class="orders-table">
                    <thead>
                        <tr>
                            <th>
                                <a href="?sort=order_id&order=<?php echo $sort_by == 'order_id' && $sort_order == 'asc' ? 'desc' : 'asc'; ?>&page=<?php echo $page; ?>" class="sort-link">
                                    Order #
                                    <?php if ($sort_by == 'order_id'): ?>
                                        <span class="sort-indicator"><?php echo $sort_order == 'asc' ? '▲' : '▼'; ?></span>
                                    <?php endif; ?>
                                </a>
                            </th>
                            <th>
                                <a href="?sort=order_date&order=<?php echo $sort_by == 'order_date' && $sort_order == 'asc' ? 'desc' : 'asc'; ?>&page=<?php echo $page; ?>" class="sort-link">
                                    Date
                                    <?php if ($sort_by == 'order_date'): ?>
                                        <span class="sort-indicator"><?php echo $sort_order == 'asc' ? '▲' : '▼'; ?></span>
                                    <?php endif; ?>
                                </a>
                            </th>
                            <?php if ($isRoot): ?>
                                <th>Customer</th>
                            <?php endif; ?>
                            <th>Items</th>
                            <th>
                                <a href="?sort=total_amount&order=<?php echo $sort_by == 'total_amount' && $sort_order == 'asc' ? 'desc' : 'asc'; ?>&page=<?php echo $page; ?>" class="sort-link">
                                    Total
                                    <?php if ($sort_by == 'total_amount'): ?>
                                        <span class="sort-indicator"><?php echo $sort_order == 'asc' ? '▲' : '▼'; ?></span>
                                    <?php endif; ?>
                                </a>
                            </th>
                            <th>
                                <a href="?sort=status&order=<?php echo $sort_by == 'status' && $sort_order == 'asc' ? 'desc' : 'asc'; ?>&page=<?php echo $page; ?>" class="sort-link">
                                    Status
                                    <?php if ($sort_by == 'status'): ?>
                                        <span class="sort-indicator"><?php echo $sort_order == 'asc' ? '▲' : '▼'; ?></span>
                                    <?php endif; ?>
                                </a>
                            </th>
                            <th>Payment</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($orders as $order): ?>
                            <tr>
                                <td>#<?php echo htmlspecialchars($order['order_id']); ?></td>
                                <td><?php echo date('M j, Y', strtotime($order['order_date'])); ?></td>
                                <?php if ($isRoot): ?>
                                    <td><?php echo htmlspecialchars($order['customer_name'] ?? 'Unknown'); ?></td>
                                <?php endif; ?>
                                <td><?php echo (int)$order['total_items']; ?></td>
                                <td>$<?php echo number_format($order['total_amount'], 2); ?></td>
                                <td>
                                    <span class="status-badge status-<?php echo strtolower($order['status']); ?>">
                                        <?php echo ucfirst($order['status']); ?>
                                    </span>
                                </td>
                                <td><?php echo ucfirst($order['payment_status'] ?? 'Pending'); ?></td>
                                <td class="action-buttons">
                                    <a href="/categories/orders/view.php?id=<?php echo $order['order_id']; ?>" class="view-btn">View</a>
                                    <?php if (!$isRoot && in_array(strtolower($order['status']), ['paid','shipped'])): ?>
                                        <form method="POST" action="?sort=<?php echo urlencode($sort_by); ?>&order=<?php echo urlencode($sort_order); ?>&page=<?php echo $page; ?>" style="display: inline;">
                                            <input type="hidden" name="action" value="track_order">
                                            <input type="hidden" name="order_id" value="<?php echo $order['order_id']; ?>">
                                            <button type="submit" class="track-btn">Track</button>
                                        </form>
                                    <?php endif; ?>
                                    <?php if (!$isRoot && strtolower($order['status']) === 'completed'): ?>
                                        <a href="/categories/products/?product_id=<?php echo htmlspecialchars($order['product_id']); ?>#reviews" class="rate-btn">Rate</a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <!-- Pagination -->
                <?php if ($total_pages > 1): ?>
                    <div class="pagination">
                        <!-- Previous Page -->
                        <?php if ($page > 1): ?>
                            <a href="?sort=<?php echo $sort_by; ?>&order=<?php echo $sort_order; ?>&page=<?php echo $page - 1; ?>">&laquo; Prev</a>
                        <?php else: ?>
                            <span style="opacity:0.6;">&laquo; Prev</span>
                        <?php endif; ?>

                        <!-- Page Numbers -->
                        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                            <?php if ($i == $page): ?>
                                <span class="active"><?php echo $i; ?></span>
                            <?php else: ?>
                                <a href="?sort=<?php echo $sort_by; ?>&order=<?php echo $sort_order; ?>&page=<?php echo $i; ?>"><?php echo $i; ?></a>
                            <?php endif; ?>
                        <?php endfor; ?>

                        <!-- Next Page -->
                        <?php if ($page < $total_pages): ?>
                            <a href="?sort=<?php echo $sort_by; ?>&order=<?php echo $sort_order; ?>&page=<?php echo $page + 1; ?>">Next &raquo;</a>
                        <?php else: ?>
                            <span style="opacity:0.6;">Next &raquo;</span>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>

    <script>
        function sortOrders(value) {
            const [sortBy, sortOrder] = value.split('-');
            // Keep current page in the URL so you don't lose pagination
            window.location.href = `?sort=${sortBy}&order=${sortOrder}&page=1`;
        }
    </script>
    <script src="/assets/js/main.js"></script>
</body>
</html>