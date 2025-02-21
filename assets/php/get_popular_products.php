<?php
require_once __DIR__ . '/../../includes/db.php';

function getPopularProducts($limit = 9) {
    global $pdo;
    
    try {
        // Query focusing only on order counts since ratings table doesn't exist
        $query = "
            SELECT 
                p.product_id,
                p.product_name,
                p.picture,
                p.price,
                p.description,
                COUNT(DISTINCT oi.order_id) as order_count
            FROM products p
            LEFT JOIN order_items oi ON p.product_id = oi.product_id
            GROUP BY p.product_id, p.product_name, p.picture, p.price, p.description
            ORDER BY order_count DESC, p.product_id ASC
            LIMIT :limit
        ";
        
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch(PDOException $e) {
        error_log("Error fetching popular products: " . $e->getMessage());
        return [];
    }
}
