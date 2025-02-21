<?php
require_once __DIR__ . '/assets/php/get_popular_products.php';

// Fetch popular products
$popularProducts = getPopularProducts(10);

// Display the raw data
echo '<pre>';
var_dump($popularProducts);
echo '</pre>';

// Display formatted data
if (!empty($popularProducts)) {
    echo '<h2>Popular Products</h2>';
    foreach ($popularProducts as $product) {
        echo '<div style="margin-bottom: 20px; border: 1px solid #ccc; padding: 10px;">';
        echo '<p>Product ID: ' . htmlspecialchars($product['product_id']) . '</p>';
        echo '<p>Name: ' . htmlspecialchars($product['product_name']) . '</p>';
        echo '<p>Price: $' . number_format($product['price'], 2) . '</p>';
        echo '<p>Picture: ' . htmlspecialchars($product['picture']) . '</p>';
        echo '<p>Rating: ' . htmlspecialchars($product['avg_rating']) . '</p>';
        echo '<p>Order Count: ' . htmlspecialchars($product['order_count']) . '</p>';
        echo '</div>';
    }
} else {
    echo '<p>No products found</p>';
}