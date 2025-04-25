<?php
require_once __DIR__ . '/../includes/get_products.php';

// Get the category filter from the query parameter (e.g., ?type=tshirts)
$type = isset($_GET['type']) ? strtolower($_GET['type']) : '';
$filteredProducts = [];

if ($type) {
    foreach ($products as $index => $product) {
        if (strtolower($product['category']) === $type) {
            // Save both index and product details for linking
            $filteredProducts[$index] = $product;
        }
    }
} else {
    $filteredProducts = $products;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Categories</title>
  <?php require_once __DIR__ . '/../assets/php/main.php'; ?>
  <link rel="icon" href="/assets/icons/Logo.ico" sizes="64x64" />
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="/assets/css/main.css" />
</head>
<body>
  <!-- Header Section -->
  <?php require_once __DIR__ . '/../assets/php/header.php'; ?>
  
  <!-- Authentication Modal -->
  <?php require_once __DIR__ . '/../assets/php/auth.php'; ?>
  <script src="/assets/js/main.js"></script>

</body>
</html>


