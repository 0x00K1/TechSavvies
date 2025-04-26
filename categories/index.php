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
  <link rel="stylesheet" href="../assets/css/main.css">
  <link rel="stylesheet" href="../assets/css/products.css">
</head>
<body>
  <!-- Header Section -->
  <?php require_once __DIR__ . '/../assets/php/header.php'; ?>

  <!-- Products Grid -->
  <div class="external_grid">
    <?php if (empty($filteredProducts)): ?>
        <div class="no-products">
          <h2>No Products Found!</h2>
          <p>We couldn't find any products in this category.</p>
        </div>
    <?php else: ?>
        <?php foreach ($filteredProducts as $id => $product): ?>
        <!-- Wrap the product card with a link to the product details page -->
        <a href="/categories/products/?product_id=<?php echo htmlspecialchars(urlencode($product['product_id'])); ?>" class="card_grid">
          <div class="product">
            <img src="<?php echo $product['image']; ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
            <h1><?php echo htmlspecialchars($product['name']); ?></h1>
            <h5><?php echo htmlspecialchars($product['description']); ?></h5>
            <div class="static-rating" style="--rating: <?php echo htmlspecialchars($product['rating']); ?>;"></div>
            <h2>$<?php echo $product['price']; ?></h2>
          </div>
        </a>
        <?php endforeach; ?>
    <?php endif; ?>
  </div>
          
  <!-- Authentication Modal -->
  <?php require_once __DIR__ . '/../assets/php/auth.php'; ?>

  <script src="/assets/js/main.js"></script>
</body>
</html>