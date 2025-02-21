<?php
require_once __DIR__ . '/../includes/db.php';

try {
    // First, get all existing product IDs
    $query = "SELECT product_id FROM products";
    $stmt = $pdo->query($query);
    $products = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    if (empty($products)) {
        die("No products found in the database.\n");
    }
    
    // Create some test users if they don't exist
    $pdo->exec("INSERT IGNORE INTO users (email, password, username, role) VALUES 
        ('test1@example.com', 'password123', 'testuser1', 'user'),
        ('test2@example.com', 'password123', 'testuser2', 'user'),
        ('test3@example.com', 'password123', 'testuser3', 'user')");
    
    // Get test user IDs
    $query = "SELECT user_id FROM users WHERE email LIKE 'test%@example.com'";
    $stmt = $pdo->query($query);
    $users = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    // Generate random orders
    $orderCount = 50; // Number of orders to generate
    
    // Start transaction
    $pdo->beginTransaction();
    
    // Clear existing test data
    $pdo->exec("DELETE FROM order_items WHERE order_id IN (SELECT order_id FROM orders WHERE user_id IN (" . implode(',', $users) . "))");
    $pdo->exec("DELETE FROM orders WHERE user_id IN (" . implode(',', $users) . ")");
    
    for ($i = 0; $i < $orderCount; $i++) {
        // Create order
        $userId = $users[array_rand($users)];
        $stmt = $pdo->prepare("INSERT INTO orders (user_id, status, total_amount) VALUES (?, 'completed', 0)");
        $stmt->execute([$userId]);
        $orderId = $pdo->lastInsertId();
        
        // Add 1-5 items to each order
        $itemCount = rand(1, 5);
        $totalAmount = 0;
        
        // Create weighted array to make some products more popular
        $weightedProducts = [];
        foreach ($products as $productId) {
            // Add product multiple times to increase its chance of being selected
            $weight = rand(1, 5); // Some products will appear up to 5 times more often
            for ($w = 0; $w < $weight; $w++) {
                $weightedProducts[] = $productId;
            }
        }
        
        $orderProducts = [];
        for ($j = 0; $j < $itemCount; $j++) {
            // Select random product from weighted array
            $productId = $weightedProducts[array_rand($weightedProducts)];
            
            // Skip if product already in order
            if (in_array($productId, $orderProducts)) {
                continue;
            }
            $orderProducts[] = $productId;
            
            // Get product price
            $stmt = $pdo->prepare("SELECT price FROM products WHERE product_id = ?");
            $stmt->execute([$productId]);
            $price = $stmt->fetchColumn();
            
            // Add order item
            $quantity = rand(1, 3);
            $stmt = $pdo->prepare("INSERT INTO order_items (order_id, product_id, quantity, price_per_unit) VALUES (?, ?, ?, ?)");
            $stmt->execute([$orderId, $productId, $quantity, $price]);
            
            $totalAmount += $price * $quantity;
        }
        
        // Update order total
        $stmt = $pdo->prepare("UPDATE orders SET total_amount = ? WHERE order_id = ?");
        $stmt->execute([$totalAmount, $orderId]);
    }
    
    // Commit transaction
    $pdo->commit();
    
    echo "Successfully generated test data with $orderCount orders.\n";
    echo "You can now test the popular products feature!\n";
    
} catch (PDOException $e) {
    // Rollback transaction on error
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    die("Error generating test data: " . $e->getMessage() . "\n");
}