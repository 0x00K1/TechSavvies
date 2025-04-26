<?php
require_once __DIR__ . '/../includes/get_products.php';

$productsById = [];
foreach ($products as $prod) {
    $productsById[$prod['product_id']] = $prod;
}

// Fetch reviews for this product
$reviews = [];
if (!empty($products_to_display)) {
    $product_ids = array_column($products_to_display, 'product_id');
    $placeholders = implode(',', array_fill(0, count($product_ids), '?'));
    
    try {
        $reviewsSql = "
            SELECT r.product_id, r.rating, r.review_text, r.created_at, c.username 
            FROM reviews r
            JOIN customers c ON r.customer_id = c.customer_id
            WHERE r.product_id IN ($placeholders)
            ORDER BY r.created_at DESC
        ";
        $reviewsStmt = $pdo->prepare($reviewsSql);
        $reviewsStmt->execute($product_ids);
        $allReviews = $reviewsStmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Organize reviews by product_id
        foreach ($allReviews as $review) {
            $reviews[$review['product_id']][] = $review;
        }
    } catch (PDOException $e) {
        // Silent fail - we'll just show empty reviews
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>TechSavvies</title>
  <?php require_once __DIR__ . '/../assets/php/main.php'; ?>
  <link rel="stylesheet" href="../assets/css/main.css">
  <link rel="stylesheet" href="../assets/css/categories.css">
  <script src="/assets/js/main.js"></script>
</head>

<body>
  <div class="page-container">
    <!-- Header Section -->
    <?php require_once __DIR__ . '/../assets/php/header.php'; ?>

    <div class="content-container">
      <!-- Category Navigation -->
      <div class="category-navigation">
        <a href="categories?type=tshirts" class="buttons <?php echo ($_GET['type'] ?? '') === 'tshirts' ? 'active' : ''; ?>">
          <img src="/assets/images/tshirts.png" alt="T-shirts" class="category-icon" />
          T-shirts
        </a>
        <a href="categories?type=backpacks" class="buttons <?php echo ($_GET['type'] ?? '') === 'backpacks' ? 'active' : ''; ?>">
          <img src="/assets/images/backpacks.png" alt="Backpacks" class="category-icon" />
          Backpacks
        </a>
        <a href="categories?type=books" class="buttons <?php echo ($_GET['type'] ?? '') === 'books' ? 'active' : ''; ?>">
          <img src="/assets/images/digital-books.png" alt="Books" class="category-icon" />
          Books
        </a>
        <a href="categories?type=laptops" class="buttons <?php echo ($_GET['type'] ?? '') === 'laptops' ? 'active' : ''; ?>">
          <img src="/assets/images/laptops.png" alt="Laptops" class="category-icon" />
          Laptops
        </a>
        <a href="categories?type=stickers" class="buttons <?php echo ($_GET['type'] ?? '') === 'stickers' ? 'active' : ''; ?>">
          <img src="/assets/images/stickers.png" alt="Stickers" class="category-icon" />
          Stickers
        </a>
        <a href="categories?type=hardware-tools" class="buttons <?php echo ($_GET['type'] ?? '') === 'hardware-tools' ? 'active' : ''; ?>">
          <img src="/assets/images/hardware-tools.png" alt="Hardware Tools" class="category-icon" />
          Hardware Tools
        </a>
        <a href="categories?type=software-tools" class="buttons <?php echo ($_GET['type'] ?? '') === 'software-tools' ? 'active' : ''; ?>">
          <img src="/assets/images/software-tools.png" alt="Software Tools" class="category-icon" />
          Software Tools
        </a>
        <a href="categories?type=mugs" class="buttons <?php echo ($_GET['type'] ?? '') === 'mugs' ? 'active' : ''; ?>">
          <img src="/assets/images/mugs.png" alt="Mugs" class="category-icon" />
          Mugs
        </a>
        <a href="categories?type=phone-cases" class="buttons <?php echo ($_GET['type'] ?? '') === 'phone-cases' ? 'active' : ''; ?>">
          <img src="/assets/images/phone-cases.png" alt="Phone Cases" class="category-icon" />
          Phone Cases
        </a>
        <a href="categories?type=games" class="buttons <?php echo ($_GET['type'] ?? '') === 'games' ? 'active' : ''; ?>">
          <img src="/assets/images/games.png" alt="Games" class="category-icon" />
          Games
        </a>
      </div>

      <!-- Search Section -->
      <div class="search-section">
        <?php
        $current_category = isset($_GET['type']) ? ucfirst(str_replace('-', ' ', $_GET['type'])) : 'T-shirts';
        ?>
        <span class="category-label">
          <i class="fa-solid fa-tags"></i> <?php echo htmlspecialchars($current_category); ?>
        </span>
        
        <div class="search-box">
          <form method="GET" action="" id="search-form">
            <input type="hidden" name="type" value="<?php echo htmlspecialchars($_GET['type'] ?? 't-shirts'); ?>">
            <input type="text" class="input-search" id="search-input" name="search" placeholder="Search products..." value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>">
            <button type="submit" class="btn-search" id="search-button"><i class="fas fa-search"></i></button>
          </form>
        </div>
      </div>

      <!-- Products Grid -->
      <div class="products-grid">
      <?php if (!empty($products_to_display)): ?>
        <?php foreach ($products_to_display as $product): ?>
          <a class="product-card"
            href="/categories/products?product_id=<?php echo $product['product_id']; ?>">
            <div class="product-image">
              <img src="<?php echo '../' . htmlspecialchars($product['picture']); ?>"
                  alt="<?php echo htmlspecialchars($product['product_name']); ?>">
            </div>

            <div class="product-details">
              <h3 class="product-name">
                <?php echo htmlspecialchars($product['product_name']); ?>
              </h3>

              <p class="product-description">
                <?php echo nl2br(htmlspecialchars($product['description'])); ?>
              </p>

              <div class="static-rating">
                  <?php 
                  $productReviews = $reviews[$product['product_id']] ?? [];
                  $avgRating = 0;
                  if (!empty($productReviews)) {
                      $avgRating = array_sum(array_column($productReviews, 'rating')) / count($productReviews);
                  }
                  
                  for ($i = 1; $i <= 5; $i++): ?>
                      <?php if ($i <= round($avgRating)): ?>
                          <i class="fas fa-star"></i>
                      <?php else: ?>
                          <i class="far fa-star"></i>
                      <?php endif; ?>
                  <?php endfor; ?>
              </div>

              <div class="product-price">
                $<?php echo number_format($product['price'], 2); ?>
              </div>

            </div>
          </a>
          <?php endforeach; ?>
        <?php else: ?>
          <div class="no-products">
            <h2>No Products Found</h2>
            <p>We couldn't find any products matching your search criteria.</p>
            <p class="suggestion">Try a different search term or browse our categories.</p>
          </div>
        <?php endif; ?>
      </div>

      <!-- Pagination -->
      <?php if ($total_pages > 1): ?>
      <div class="pagination">
        <?php
        // Preserve the 'type' and 'search' parameters in the URL
        $type_param = isset($_GET['type']) ? 'type=' . urlencode($_GET['type']) . '&' : '';
        $search_param = isset($_GET['search']) ? 'search=' . urlencode($_GET['search']) . '&' : '';
        ?>

        <?php if ($page > 1): ?>
          <a href="?<?php echo $type_param . $search_param; ?>page=<?php echo $page - 1; ?>"><i class="fa-solid fa-chevron-left"></i> Prev</a>
        <?php else: ?>
          <span><i class="fa-solid fa-chevron-left"></i> Prev</span>
        <?php endif; ?>

        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
          <?php if ($i == $page): ?>
            <span class="active"><?php echo $i; ?></span>
          <?php else: ?>
            <a href="?<?php echo $type_param . $search_param; ?>page=<?php echo $i; ?>"><?php echo $i; ?></a>
          <?php endif; ?>
        <?php endfor; ?>

        <?php if ($page < $total_pages): ?>
          <a href="?<?php echo $type_param . $search_param; ?>page=<?php echo $page + 1; ?>">Next <i class="fa-solid fa-chevron-right"></i></a>
        <?php else: ?>
          <span>Next <i class="fa-solid fa-chevron-right"></i></span>
        <?php endif; ?>
      </div>
      <?php endif; ?>
    </div>
  </div>

  <script>
    // Search functionality
    const searchForm = document.getElementById('search-form');
    const searchButton = document.getElementById('search-button');
    const searchInput = document.getElementById('search-input');
    
    // Submit form when search button is clicked
    searchButton.addEventListener('click', function(event) {
      if (searchInput.value.trim() !== '') {
        searchForm.submit();
      } else {
        event.preventDefault();
        searchInput.focus();
      }
    });
    
    // Active category highlighting
    document.addEventListener('DOMContentLoaded', function() {
      const urlParams = new URLSearchParams(window.location.search);
      const typeParam = urlParams.get('type');
      
      if (typeParam) {
        const activeLink = document.querySelector(`.buttons[href*="type=${typeParam}"]`);
        if (activeLink) {
          activeLink.classList.add('active');
        }
      }
    });
  </script>
</body>
</html>
