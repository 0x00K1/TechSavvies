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

  <!-- Redirecting Buttons to move between Categories -->
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
require_once __DIR__ . '/../assets/php/env_loader.php'; // env_loader for DB connection
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
  'hardware-tools' => 6,// Hardware Tools
  'software-tools' => 7,// Software Tools
  'mugs' => 8,          // Mugs
  'phone-cases' => 9,   // Phone Cases
  'games' => 10         // Games
];

// Check if the 'type' parameter is set.
$type = $_GET['type'] ?? 't-shirt'; // Default to 't-shirt' if no type is specified
$search_term = isset($_GET['search']) ? '%' . $_GET['search'] . '%' : '%';

// Get the corresponding category_id, default is 1 't-shirt'.
$category_id = $type_to_category_id[$type] ?? 1;

// Brings Products from the database based on category_id and search field
$query = 'SELECT p.*, c.category_name
  FROM products p
  LEFT JOIN categories c ON p.category_id = c.category_id
  WHERE p.category_id = ? AND p.product_name LIKE ?
  ORDER BY p.product_id';

// Execute the query for category_id
$stmt = $pdo->prepare($query);
$stmt->execute([$category_id , $search_term]);

// Fetch the results
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);?>


<?php
// Pagination logic
$items_per_page = 6; // Number of cards per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Current page, default is 1
$total_items = count($products); // Total number of products
$total_pages = ceil($total_items / $items_per_page); // Total number of pages

// Calculate the offset and slice the products array
$offset = ($page - 1) * $items_per_page;
$products_to_display = array_slice($products, $offset, $items_per_page);
?>

      <!-- Dynamic Card Display -->
      <div class="page_grid">
        <!-- Search Button And Field -->
      <div class="search-box">
     <form method="GET" action="" id="search-form">
    <input type="hidden" name="type" value="<?php echo htmlspecialchars($_GET['type'] ?? 't-shirts'); ?>">
    <input type="text" class="input-search" id="search-input" name="search" placeholder="Type to Search...">
    <button type="button" class="btn-search" id="search-button"><i class="fas fa-search"></i></button>
     </form>
      </div>
      <!-- Card Grids -->
        <div class="external_grid">
          <!-- Create A card for each product in Database -->
          <?php foreach ($products_to_display as $product): ?>
            <div class="card_grid">
              <div class="product">
                <!-- Product image -->
                <img class="product_img" src="<?php echo '../' . htmlspecialchars($product['picture']); ?>"
                    alt="<?php echo htmlspecialchars($product['product_name']) . ' Picture'; ?>">
                <!-- Product Name -->
                <h1 class="product_h1"><?php echo htmlspecialchars($product['product_name']); ?></h1>
                <!-- Product Description -->
                <h5 class="product_h5"><?php echo nl2br(htmlspecialchars($product['description'])); ?></h5>
                <!-- Product Rating -->
                <div class="static-rating" 
                    style="--rating: <?php echo floatval($product['rating']); ?>;"></div>
                <!-- Product Price -->
                <h2 class="product_h2">$<?php echo number_format($product['price'], 2); ?></h2>
              </div>
            </div>
          <?php endforeach; ?>
        </div>

        <!-- Pagination Links -->
        <div class="pagination">
          <?php $type_param = isset($_GET['type']) ? 'type=' . urlencode($_GET['type']) . '&' : '';?>
          <?php if ($page > 1): ?>
          <a href="?<?php echo $type_param; ?>page=<?php echo $page - 1; ?>">&laquo; Prev</a>
          <?php else: ?>
          <span style="opacity:0.6;">&laquo; Prev</span>
          <?php endif; ?>
          <?php for ($i = 1; $i <= $total_pages; $i++): ?>
          <?php if ($i == $page): ?>
          <span class="active"><?php echo $i; ?></span>
          <?php else: ?>
          <a href="?<?php echo $type_param; ?>page=<?php echo $i; ?>"><?php echo $i; ?></a>
          <?php endif; ?>
          <?php endfor; ?>
          <?php if ($page < $total_pages): ?>
          <a href="?<?php echo $type_param; ?>page=<?php echo $page + 1; ?>">Next &raquo;</a>
          <?php else: ?>
          <span style="opacity:0.6;">Next &raquo;</span>
          <?php endif; ?>
        </div>
      </div>
      <!-- End of Cards -->

      <!-- Script For Search -->
      <script>
  const searchButton = document.getElementById('search-button');
  const searchInput = document.getElementById('search-input');
  const searchForm = document.getElementById('search-form');

  // Prevent form submission when the button is clicked because the button when clicked will open the input field
  searchButton.addEventListener('click', (event) => {
    searchInput.focus(); // Focus on the input field
    event.preventDefault(); // Prevent form submission with click only by pressing enter
  });

  // Allow form submission only when "Enter" is pressed in the input field
  searchInput.addEventListener('keydown', (event) => {
    if (event.key === 'Enter') {
      searchForm.submit(); // Submit the form
    }
  });
</script>


<!-- Hambruger Checkbox for toggling sidebar -->
<input type="checkbox" id="menu-toggle">
<label for="menu-toggle" class="menu-icon">&#9776;</label>
<!-- Sidebar Navigation -->
<nav class="sidebar" aria-label="Sidebar Navigation">
    <!-- Menu Container -->
    <div>
        <!-- User Menu -->
        <ul class="user-menu">
          <!-- Dont remove any <li> Better for Spacing -->
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
  <!-- Dont know how to fix script issue for the button that will make you go up -->
  <script src="/assets/js/main.js"></script>

</body>
</html>


