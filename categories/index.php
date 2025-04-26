<!DOCTYPE html>
<html lang="en">

<head>
  <title>TechSavvies</title>
  <?php require_once __DIR__ . '/../assets/php/main.php'; ?>
  <link rel="icon" href="/assets/icons/Logo.ico" sizes="64x64" />
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="../assets/css/products.css">
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
<?php require_once __DIR__ . '/../includes/get_products.php';?>

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
        <?php if (!empty($products_to_display)): ?>
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
          <?php else: ?>
        <p>No products found for this category or search term.</p>
    <?php endif; ?>
        </div>

        <!-- Pagination Links -->
        <div class="pagination">
          <?php
          // Preserve the 'type' and 'search' parameters in the URL
          $type_param = isset($_GET['type']) ? 'type=' . urlencode($_GET['type']) . '&' : '';
          $search_param = isset($_GET['search']) ? 'search=' . urlencode($_GET['search']) . '&' : '';
          ?>

          <?php if ($page > 1): ?>
            <a href="?<?php echo $type_param . $search_param; ?>page=<?php echo $page - 1; ?>">&laquo; Prev</a>
          <?php else: ?>
            <span style="opacity:0.6;">&laquo; Prev</span>
          <?php endif; ?>

          <?php for ($i = 1; $i <= $total_pages; $i++): ?>
            <?php if ($i == $page): ?>
              <span class="active"><?php echo $i; ?></span>
            <?php else: ?>
              <a href="?<?php echo $type_param . $search_param; ?>page=<?php echo $i; ?>"><?php echo $i; ?></a>
            <?php endif; ?>
          <?php endfor; ?>

          <?php if ($page < $total_pages): ?>
            <a href="?<?php echo $type_param . $search_param; ?>page=<?php echo $page + 1; ?>">Next &raquo;</a>
          <?php else: ?>
            <span style="opacity:0.6;">Next &raquo;</span>
          <?php endif; ?>
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


