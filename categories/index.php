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

// Check if the 'type' parameter is set in the query string, otherwise default to 't-shirt'
$type = $_GET['type'] ?? 't-shirt'; // Default to 't-shirt' if no type is specified

// Get the corresponding category_id, defaulting to 1 (T-shirts) if the type doesn't exist in the map
$category_id = $type_to_category_id[$type] ?? 1; 

// Prepare the query
$query = 'SELECT p.*, c.category_name
  FROM products p
  LEFT JOIN categories c ON p.category_id = c.category_id
  WHERE p.category_id = ?
  ORDER BY p.product_id';

// Execute the query with the dynamic category_id
$stmt = $pdo->prepare($query);
$stmt->execute([$category_id]);

// Fetch the results
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
            <li class="sidebar-anchor"><a href="index.php#contact" ><i class="fa fa-envelope"></i> Contact Us</a></li>
        </ul>
    </div>
</nav>





<!-- Sidebar Navigation -->
    <nav class="sidebar"> <!-- Need Fix next milestone st each page has its own Filters, Price and Rating for all rest is changed from database-->

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
