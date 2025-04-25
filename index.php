<?php
require_once __DIR__ . '/includes/get_popular_products.php';

// Fetch popular products
$popularProducts = getPopularProducts(10);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>TechSavvies</title>
  <?php require_once __DIR__ . '/assets/php/main.php'; ?>
  <link rel="stylesheet" href="assets/css/main.css">
</head>
<body>
  <!-- Header Section -->
  <?php require_once __DIR__ . '/assets/php/header.php'; ?>

  <!-- Hero Section -->
  <section class="hero">
    <div class="hero-content">
      <h1>Welcome to <span class="glitch-text">TechSavvies</span> Shop</h1>
      <p>Bridging innovation and everyday life with cutting-edge tech and style.</p>
      <a href="#shop"><span>Shop Now</span></a>
    </div>
  </section>

  <!-- Main Content -->
  <div class="main-content">
    <!-- Categories Section -->
    <section class="categories" id="shop">
      <h2 class="section-title">Shop by Category</h2>
      <div class="category-grid">
        <div class="category-box" onclick="location.href='categories?type=tshirts'">
          <img src="assets/images/tshirts.png" alt="T-shirts" />
          <h3>T-shirts</h3>
          <p>Cool tech-inspired apparel.</p>
        </div>
        <div class="category-box" onclick="location.href='categories?type=backpacks'">
          <img src="assets/images/backpacks.png" alt="Backpacks" />
          <h3>Backpacks</h3>
          <p>Stylish and functional bags.</p>
        </div>
        <div class="category-box" onclick="location.href='categories?type=books'">
          <img src="assets/images/digital-books.png" alt="Digital Books" />
          <h3>Books</h3>
          <p>Learn and master tech skills.</p>
        </div>
        <div class="category-box" onclick="location.href='categories?type=laptops'">
          <img src="assets/images/laptops.png" alt="Laptops" />
          <h3>Laptops</h3>
          <p>High performance laptops for every need.</p>
        </div>
        <div class="category-box" onclick="location.href='categories?type=stickers'">
          <img src="assets/images/stickers.png" alt="Stickers" />
          <h3>Stickers</h3>
          <p>Show off your passion with our stickers.</p>
        </div>
        <div class="category-box" onclick="location.href='categories?type=hardware-tools'">
          <img src="assets/images/hardware-tools.png" alt="Hardware Tools" />
          <h3>Hardware Tools</h3>
          <p>Essential tools for every tech enthusiast.</p>
        </div>
        <div class="category-box" onclick="location.href='categories?type=software-tools'">
          <img src="assets/images/software-tools.png" alt="Software Tools" />
          <h3>Software Tools</h3>
          <p>Tools to boost your productivity.</p>
        </div>
        <div class="category-box" onclick="location.href='categories?type=mugs'">
          <img src="assets/images/mugs.png" alt="Mugs" />
          <h3>Mugs</h3>
          <p>Perfect for your daily caffeine fix.</p>
        </div>
        <div class="category-box" onclick="location.href='categories?type=phone-cases'">
          <img src="assets/images/phone-cases.png" alt="Phone Cases" />
          <h3>Phone Cases</h3>
          <p>Protect your device with style.</p>
        </div>
        <div class="category-box" onclick="location.href='categories?type=games'">
          <img src="assets/images/games.png" alt="Games" />
          <h3>Games</h3>
          <p>Explore the latest tech games.</p>
        </div>
      </div>
    </section>

    <!-- Popular Products Slider -->
    <section class="popular-products">
        <h2 class="section-title">Popular Products</h2>
        <div class="slider">
            <div class="slider-container">
                <button class="prev">❮</button>
                <div class="slider-wrapper">
                    <?php foreach ($popularProducts as $product): ?>
                        <div class="slide">
                            <a href="categories/products/?product_id=<?= htmlspecialchars(urlencode($product['product_id']));?>" class="product-card">
                                <img src="assets/images/Products/<?= htmlspecialchars($product['picture']) ?>" alt="<?= htmlspecialchars($product['product_name']) ?>">
                                <div class="product-info">
                                    <h3><?= htmlspecialchars($product['product_name']) ?></h3>
                                </div>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
                <button class="next">❯</button>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="testimonials">
      <h2 class="section-title">What Our Customers Say</h2>
      <div class="testimonial-slider">
        <div class="testimonial-track">
          <!-- Simulated testimonials -->
          <div class="testimonial">
            <div class="testimonial-content">
              <p class="testimonial-text">"Amazing quality and fast delivery!"</p>
              <div class="testimonial-author">فتحي</div>
            </div>
          </div>
          <div class="testimonial">
            <div class="testimonial-content">
              <p class="testimonial-text">"The best tech products I've found online."</p>
              <div class="testimonial-author">عبدالصمد ثلاجة</div>
            </div>
          </div>
          <div class="testimonial">
            <div class="testimonial-content">
              <p class="testimonial-text">"Superb customer service and great prices!"</p>
              <div class="testimonial-author">John Smith</div>
            </div>
          </div>
          <div class="testimonial">
            <div class="testimonial-content">
              <p class="testimonial-text">"I love the variety of products available!"</p>
              <div class="testimonial-author">Sarah Johnson</div>
            </div>
          </div>
          <div class="testimonial">
            <div class="testimonial-content">
              <p class="testimonial-text">"Fast shipping and well-packaged items."</p>
              <div class="testimonial-author">Michael Brown</div>
            </div>
          </div>
          <div class="testimonial">
            <div class="testimonial-content">
              <p class="testimonial-text">"Great deals and discounts, highly recommended!"</p>
              <div class="testimonial-author">Emily Davis</div>
            </div>
    </section>

    <!-- Contact Section -->
    <section id="contact">
      <h2>Contact Us</h2>
      <div class="contact-cta">
        <p>Have questions or feedback? We'd love to hear from you!</p>
        <div class="contact-options">
          <a href="contact.php" class="contact-button">Send Us a Message</a>
          <p class="contact-info"><strong>01 TechSavvies, Tech City</strong></p>
        </div>
      </div>
    </section>
  </div>

  <!-- Footer Section -->
  <?php require_once __DIR__ . '/assets/php/footer.php'; ?>

  <script src="/assets/js/home1.js"></script>
  <script src="/assets/js/main.js"></script>
</body>
</html>
