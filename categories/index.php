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
</head>
<body>
  <!-- Header Section -->
  <?php require_once __DIR__ . '/../assets/php/header.php'; ?>
  <div class="page_grid">

<!-- Connection to DB done just change USERNAME and PASSWORD.  -->
<!--  Need fix coloumns and rows when adding product now it all become in same column. -->
<!--  Add more products into DataBase. Also neeed seperation of products T-shirts should not appear in other categories, but it does. -->
<!-- Make every entry into a seperate DIV -->


<div class="external_grid">
<?php
// Database connection parameters
$host = 'localhost'; // or the IP address of your database server
$dbname = 'techsavvies';
$username = 'root';  // or your database username
$password = '1234567';      // or your database password

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
// Assuming the database connection $pdo has already been established

// Query to get all products
$query = 'SELECT * FROM products';
$stmt = $pdo->query($query);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>



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
<nav class="sidebar">
    <!-- Menu Container -->
    <div>
        <!-- User Menu -->
        <ul class="user-menu">
            <li></li><li></li><li></li><li></li><li></li>
            <li class="sidebar-anchor"><a href="index.php" class="sidebar-anchor" ><i class="fa fa-home"></i> Home</a></li>
            <li class="sidebar-anchor"><a href="index.php#shop"><i class="fa fa-store"></i> Shop</a></li>
            <li class="sidebar-anchor"><a href="index.php#popular"><i class="fa fa-star"></i> Popular Products</a></li>
            <li class="sidebar-anchor"><a href="#"><i class="fa fa-cart-shopping"></i> Cart</a></li>
            <li class="sidebar-anchor"><a href="index.php#contact" ><i class="fa fa-envelope"></i> Contact Us</a></li>
        </ul>
    </div>
</nav>





<!-- Sidebar Navigation -->
    <nav class="sidebar">

         <div>
        <!-- Right Sidebar (Filters Form) -->
        <aside class="filter-sidebar">
            <h2>Filters</h2>
            <form action="#" method="GET">

                <details class="filter-group">
                    <summary>Size</summary>
                    <label><input type="checkbox" name="size" value="S"> S</label>
                    <label><input type="checkbox" name="size" value="M"> M</label>
                    <label><input type="checkbox" name="size" value="L"> L</label>
                    <label><input type="checkbox" name="size" value="XL"> XL</label>
                    <label><input type="checkbox" name="size" value="XXL"> XXL</label>
                </details>

                <!-- Color Filter -->
                <details class="filter-group">
                    <summary>Color</summary>
                    <div class="swatches">
                        <label>
                            <input type="radio" name="color" value="black">
                            <div class="swatch" style="background-color: black;"></div>
                        </label>
                        <label>
                            <input type="radio" name="color" value="white">
                            <div class="swatch" style="background-color: white; border: 1px solid #ccc;"></div>
                        </label>
                        <label>
                            <input type="radio" name="color" value="Mauve">
                            <div class="swatch" style="background-color: #642d3d;"></div>
                        </label>
                        <label>
                            <input type="radio" name="color" value="Warm Gray">
                            <div class="swatch" style="background-color: #C9C6BE;"></div>
                        </label>
                        <label>
                            <input type="radio" name="color" value="Dark Gray">
                            <div class="swatch" style="background-color: #575551;"></div>
                        </label>
                    </div>
                </details>

                <!-- Price Range Filter -->
                <details class="filter-group">
                    <summary>Price Range (in $)</summary>
                    <div class="price-range">
                        <label>
                            Min:
                            <input type="number" id="minPrice" name="minPrice" value="1" min="1" step="1">
                        </label>
                        <label>
                            Max:
                            <input type="number" id="maxPrice" name="maxPrice" value="100" min="1" step="1">
                        </label>
                    </div>
                </details>

                <details class="filter-group">
                    <summary>Rating</summary>
                    <div class="rating">
                        <input value="5" name="rating" id="star5" type="radio">
                        <label for="star5"></label>
                        <input value="4" name="rating" id="star4" type="radio">
                        <label for="star4"></label>
                        <input value="3" name="rating" id="star3" type="radio">
                        <label for="star3"></label>
                        <input value="2" name="rating" id="star2" type="radio">
                        <label for="star2"></label>
                        <input value="1" name="rating" id="star1" type="radio">
                        <label for="star1"></label>
                    </div>
                </details>

                <div class="button-container">
                    <button class="button_top"><span>Apply Filters</span></button>
                    <button class="clear-filters"><span>Clear Filters</span></button>
                </div>
            </form>
        </aside>
    </div>

  <!-- Authentication Modal -->
  <?php require_once __DIR__ . '/../assets/php/auth.php'; ?>

  <script src="/assets/js/main.js"></script>
</body>
</html>
