<?php
require_once __DIR__ . '/../../includes/db.php';


header('Content-Type: application/json');

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $productId = $_POST['product_id'] ?? null;
        $customerId = $_POST['customer_id'] ?? null;
        $rating = $_POST['rating'] ?? null;
        $reviewText = $_POST['review_text'] ?? '';

        if ($productId && $customerId && $rating) {
            $query = "
                INSERT INTO reviews (product_id, customer_id, rating, review_text, created_at)
                VALUES (:product_id, :customer_id, :rating, :review_text, NOW())
            ";
            $stmt = $pdo->prepare($query);

            $stmt->execute([
                'product_id' => $productId,
                'customer_id' => $customerId,
                'rating' => $rating,
                'review_text' => $reviewText,
            ]);

            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Missing fields']);
        }
    }
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'error' => 'Database error: ' . $e->getMessage()
    ]);
}
?>
