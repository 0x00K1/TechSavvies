<!DOCTYPE html>
<html lang="en">
<head>
  <title>TechSavvies</title>
  <?php require_once __DIR__ . '/../assets/php/main.php'; ?>
  <link rel="icon" href="/assets/icons/Logo.ico" sizes="64x64" />
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="../assets/css/style.css">
  <link rel="stylesheet" href="../assets/css/main.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@flaticon/flaticon-uicons/css/all/all.css">

</head>
<body>
  <!-- Header Section -->
  <?php require_once __DIR__ . '/../assets/php/header.php'; ?>
  <div class="page_grid">



  <div class="multi-button">
  <a href="categories?type=tshirts" class="buttons"><i class="fi fi-ss-tshirt"></i>T-shirts</a>
  <a href="categories?type=backpacks" class="buttons"><i class="fi fi-ss-backpack"></i>Backpacks</a>
  <a href="categories?type=books" class="buttons"><i class="fi fi-ss-books"></i>Books</a>
  <a href="categories?type=laptops" class="buttons"><i class="fa-solid fa-laptop"></i>Laptops</a>
  <a href="categories?type=stickers" class="buttons"><i class="fa-regular fa-note-sticky"></i>Stickers</a>
  <a href="categories?type=hardware-tools" class="buttons"><i class="fi fi-ss-microchip"></i>Hardware Tools</a>
  <a href="categories?type=software-tools" class="buttons"><i class="fi fi-ss-api"></i>Software Tools</a>
  <a href="categories?type=mugs" class="buttons"><i class="fi fi-ss-mug"></i>Mugs</a>
  <a href="categories?type=phone-cases" class="buttons"><i class="fi fi-ss-mobile-button"></i>Phone Cases</a>
  <a href="categories?type=games" class="buttons"><i class="fi fi-ss-gamepad"></i>Games</a>
</div>




<!-- Add Search Function By Regex-->
<!--  Pagintation for more products max 6 cards per page  -->

<div class="external_grid">
<?php
require_once __DIR__ . '/../assets/php/env_loader.php'; // env_loader for db and smtp

// Database connection Using .env
$host = getenv('DB_HOST');
$dbname = getenv('DB_NAME');
$username = getenv('DB_USERNAME');
$password = getenv('DB_PASSWORD');

try {
    // Create a PDO instance for database connection
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    
    // Set PDO error mode to exception for better error handling
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
} catch (PDOException $e) {
    // Handle any connection error
    echo "Connection failed: " . $e->getMessage();
    exit();
}
?>

<?php
$type_to_category_id = [
  't-shirts' => 1,      // T-shirts
  'backpacks' => 2,     // Backpacks
  'books' => 3,         // Books
  'laptops' => 4,       // Laptops
  'stickers' => 5,      // Stickers
  'hardware-tools' => 6,     // Hardware Tools
  'software-tools' => 7,     // Software Tools
  'mugs' => 8,          // Mugs
  'phone-cases' => 9,   // Phone Cases
  'games' => 10         // Games
];

// Check if the 'type' parameter is set.
$type = $_GET['type'] ?? 't-shirt'; // Default to 't-shirt' if no type is specified

// Get the corresponding category_id, default is 1 't-shirt'.
$category_id = $type_to_category_id[$type] ?? 1;

// Prepare the query
$query = 'SELECT p.*, c.category_name
  FROM products p
  LEFT JOIN categories c ON p.category_id = c.category_id
  WHERE p.category_id = ?
  ORDER BY p.product_id';

// Execute the query for category_id
$stmt = $pdo->prepare($query);
$stmt->execute([$category_id]);

// Fetch the results
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>


<!-- Dynamic Card Display -->
<div class="page_grid">
  <div class="external_grid">
    <?php foreach ($products as $product): ?>
      <div class="card_grid">
        <div class="product">
          <img class="product_img" src="<?php echo '../' . htmlspecialchars($product['picture']); ?>"
               alt="<?php echo htmlspecialchars($product['product_name']) . ' Picture'; ?>">
          
          <h1 class="product_h1"><?php echo htmlspecialchars($product['product_name']); ?></h1>
          
          <h5 class="product_h5"><?php echo nl2br(htmlspecialchars($product['description'])); ?></h5>
          
          <div class="static-rating" 
               style="--rating: <?php echo floatval($product['rating']); ?>;"></div>
          
          <h2 class="product_h2">$<?php echo number_format($product['price'], 2); ?></h2>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</div>

<!-- Hidden checkbox for toggling sidebar -->
<input type="checkbox" id="menu-toggle">
<label for="menu-toggle" class="menu-icon">&#9776;</label>
<!-- Sidebar Navigation -->
<nav class="sidebar" aria-label="Sidebar Navigation">
    <!-- Menu Container -->
    <div>
        <!-- User Menu -->
        <ul class="user-menu">
            <li></li><li></li><li></li><li></li><li></li>
            <li class="sidebar-anchor"><a href="index.php" class="sidebar-anchor" ><i class="fa fa-home"></i> Home</a></li>
            <li class="sidebar-anchor"><a href="index.php#shop"><i class="fa fa-store"></i> Shop</a></li>
            <li class="sidebar-anchor"><a href="index.php#popular"><i class="fa fa-star"></i> Popular Products</a></li>
            <li class="sidebar-anchor"><a href="index.php#contact" ><i class="fa fa-envelope"></i> Contact Us</a></li>
        </ul>
    </div>
</nav>


  <!-- Authentication Modal -->
  <?php require_once __DIR__ . '/../assets/php/auth.php'; ?>
  <script src="/assets/js/main.js"></script>

</body>
</html>


