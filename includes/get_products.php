<?php
require_once __DIR__ . '/db.php';

$query = "
    SELECT 
        p.product_id,
        c.category_name AS category,
        p.picture AS image,
        p.product_name AS name,
        p.description,
        p.price,
        p.stock 
    FROM products p
    JOIN categories c ON p.category_id = c.category_id
    WHERE p.stock > 0
    ORDER BY p.product_id ASC
";

$stmt = $pdo->prepare($query);
$stmt->execute();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Rating logic [LATER]
foreach ($products as &$product) {
    $product['rating'] = 4.0; // Default rating [TEMP]
    $product['image'] = '/assets/images/products/' . $product['image'];
}
unset($product);
?>