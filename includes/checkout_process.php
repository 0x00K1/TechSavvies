<?php
require_once __DIR__ . '/secure_session.php';
require_once __DIR__ . '/db.php';

// 1) Check if user is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    // Not logged in
    header('Location: /');
    exit;
}

$userId = $_SESSION['user']['customer_id'] ?? null;
if (!$userId) {
    // Something's wrong with the session user data.
    echo "Cannot identify user. Please log in again.";
    exit;
}

// 2) Read cart cookie
$cartJson = $_COOKIE['cartItems'] ?? '[]';
$cartItems = json_decode($cartJson, true);
if (!is_array($cartItems) || count($cartItems) === 0) {
    // No items in cart
    echo "Your cart is empty. Cannot proceed with checkout.";
    exit;
}

$pdo->beginTransaction();

try {
    // 3) Validate each cart item: check stock, get up-to-date price
    $orderItems  = [];
    $subtotal    = 0.0;

    foreach ($cartItems as $ci) {
        $productId = (int)($ci['productId'] ?? 0);
        $quantity  = (int)($ci['quantity']   ?? 0);

        // Skip invalid or zero quantity items
        if ($productId <= 0 || $quantity <= 0) {
            continue;
        }

        // Look up product
        $stmt = $pdo->prepare("SELECT price, stock FROM products WHERE product_id = :pid");
        $stmt->execute([':pid' => $productId]);
        $productRow = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$productRow) {
            throw new Exception("Product ID {$productId} not found in DB.");
        }

        // Check stock
        $stockAvailable = (int)$productRow['stock'];
        if ($quantity > $stockAvailable) {
            throw new Exception("Not enough stock for Product ID {$productId}. Only {$stockAvailable} left.");
        }

        // Build line item
        $priceDB   = (float)$productRow['price'];
        $lineTotal = $priceDB * $quantity;
        $subtotal += $lineTotal;

        $orderItems[] = [
            'product_id' => $productId,
            'quantity'   => $quantity,
            'price'      => $priceDB
        ];
    }

    // If no valid items, fail
    if (count($orderItems) === 0) {
        throw new Exception("No valid items in cart.");
    }

    // 4) Insert new order row with a default total of 0 for now
    $stmt = $pdo->prepare("
        INSERT INTO orders (customer_id, status, total_amount)
        VALUES (:cust, 'pending', 0)
    ");
    $stmt->execute([':cust' => $userId]);
    $orderId = $pdo->lastInsertId();

    // 5) Insert each order item, decrement product stock
    foreach ($orderItems as $item) {
        // order_items
        $stmt = $pdo->prepare("
            INSERT INTO order_items (order_id, product_id, quantity, price_per_unit)
            VALUES (:oid, :pid, :qty, :ppu)
        ");
        $stmt->execute([
            ':oid' => $orderId,
            ':pid' => $item['product_id'],
            ':qty' => $item['quantity'],
            ':ppu' => $item['price']
        ]);

        // Decrement stock
        $stmtUp = $pdo->prepare("
            UPDATE products
            SET stock = stock - :qty
            WHERE product_id = :pid
        ");
        $stmtUp->execute([
            ':qty' => $item['quantity'],
            ':pid' => $item['product_id']
        ]);
    }

    // 6) Now compute shipping & tax, then final total
    $shippingFee = 10.00;
    $taxAmount   = $subtotal * 0.08; // 8% tax
    $finalTotal  = $subtotal + $shippingFee + $taxAmount;

    // 7) Update total_amount in orders
    $stmt = $pdo->prepare("
        UPDATE orders
        SET total_amount = :ta
        WHERE order_id = :oid
    ");
    $stmt->execute([
        ':ta'  => $finalTotal,
        ':oid' => $orderId
    ]);

    // 8) commit transaction
    $pdo->commit();

    // ?) setcookie('cartItems', '', time() - 3600, '/');

    // 10) redirect to the Payment/Checkout page
    header("Location: /categories/checkouts?order_id={$orderId}");
    exit;

} catch (Exception $e) {
    $pdo->rollBack();
    echo "Checkout failed: " . $e->getMessage();
    exit;
}