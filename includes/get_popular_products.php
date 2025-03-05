<?php
require_once __DIR__ . '/db.php';

function getPopularProducts($limit = 9) {
    global $pdo;
    
    try {
        // We use a LEFT JOIN to also include products that may not appear in order_items at all.
        // Counting DISTINCT oi.order_id ensures we only count each order once, 
        // even if the product appears multiple times in that order.
        $query = "
            SELECT 
                p.product_id,
                p.product_name,
                p.picture,
                p.price,
                p.description,
                COUNT(DISTINCT oi.order_id) AS order_count
            FROM products p
            LEFT JOIN order_items oi ON p.product_id = oi.product_id
            GROUP BY p.product_id, p.product_name, p.picture, p.price, p.description
            ORDER BY order_count DESC, p.product_id ASC
            LIMIT :limit
        ";
        
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        
        // Return an array of products sorted by popularity
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error fetching popular products: " . $e->getMessage());
        return [];
    }
}