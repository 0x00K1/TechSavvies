<?php
require_once __DIR__ . '/../../includes/db.php'; // adjust path to your db.php (PDO connection)

function getReviewsByProductId($productId) {
    global $pdo;

    $query = "
        SELECT r.rating, r.review_text, c.username
        FROM reviews r
        JOIN customers c ON r.customer_id = c.customer_id
        WHERE r.product_id = :product_id
        ORDER BY r.created_at DESC
    ";

    $stmt = $pdo->prepare($query);
    $stmt->execute(['product_id' => $productId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>
