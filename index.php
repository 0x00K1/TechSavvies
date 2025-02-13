<?php
require_once __DIR__ . '/includes/secure_session.php';
?>

<head>
  <title>TechSavvies - Home</title>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/main.css">
</head>
<body>
<?php require_once __DIR__ . '/assets/php/main.php'; ?>

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
        <div class="category-box" onclick="location.href='categories?name=tshirts'">
          <img src="assets/images/tshirts.png" alt="T-shirts" />
          <h3>T-shirts</h3>
          <p>Cool tech-inspired apparel.</p>
        </div>
        <div class="category-box" onclick="location.href='categories?name=backpacks'">
          <img src="assets/images/backpacks.png" alt="Backpacks" />
          <h3>Backpacks</h3>
          <p>Stylish and functional bags.</p>
        </div>
        <div class="category-box" onclick="location.href='categories?name=books'">
          <img src="assets/images/digital-books.png" alt="Digital Books" />
          <h3>Books</h3>
          <p>Learn and master tech skills.</p>
        </div>
        <div class="category-box" onclick="location.href='categories?name=laptops'">
          <img src="assets/images/laptops.png" alt="Laptops" />
          <h3>Laptops</h3>
          <p>High performance laptops for every need.</p>
        </div>
        <div class="category-box" onclick="location.href='categories?name=stickers'">
          <img src="assets/images/stickers.png" alt="Stickers" />
          <h3>Stickers</h3>
          <p>Show off your passion with our stickers.</p>
        </div>
        <div class="category-box" onclick="location.href='categories?name=hardware-tools'">
          <img src="assets/images/hardware-tools.png" alt="Hardware Tools" />
          <h3>Hardware Tools</h3>
          <p>Essential tools for every tech enthusiast.</p>
        </div>
        <div class="category-box" onclick="location.href='categories?name=software-tools'">
          <img src="assets/images/software-tools.png" alt="Software Tools" />
          <h3>Software Tools</h3>
          <p>Tools to boost your productivity.</p>
        </div>
        <div class="category-box" onclick="location.href='categories?name=mugs'">
          <img src="assets/images/mugs.png" alt="Mugs" />
          <h3>Mugs</h3>
          <p>Perfect for your daily caffeine fix.</p>
        </div>
        <div class="category-box" onclick="location.href='categories?name=phone-cases'">
          <img src="assets/images/phone-cases.png" alt="Phone Cases" />
          <h3>Phone Cases</h3>
          <p>Protect your device with style.</p>
        </div>
        <div class="category-box" onclick="location.href='categories?name=games'">
          <img src="assets/images/games.png" alt="Games" />
          <h3>Games</h3>
          <p>Explore the latest tech games.</p>
        </div>
      </div>
    </section>

    <!-- Popular Products Slider (homepage-specific) -->
    <section class="popular-products">
      <h2 class="section-title">Popular Products</h2>
      <div class="slider">
        <div class="slider-wrapper" id="sliderWrapper">
          <div class="slide">
            <img src="assets/images/popular-tshirt.png" alt="Popular T-shirt" />
            <p>Tech T-shirt</p>
          </div>
          <div class="slide">
            <img src="assets/images/popular-backpack.png" alt="Popular Backpack" />
            <p>Stylish Backpack</p>
          </div>
          <div class="slide">
            <img src="assets/images/popular-mug.png" alt="Popular Mug" />
            <p>Coffee Mug</p>
          </div>
        </div>
      </div>
    </section>

    <!-- Testimonials Section -->
    <section class="testimonials">
      <h2 class="section-title">What Our Customers Say</h2>
      <div class="testimonial-container">
        <div class="testimonial">
          <p>"Amazing quality and fast delivery!" - فتحي</p>
        </div>
        <div class="testimonial">
          <p>"The best tech products I've found online." - kun</p>
        </div>
      </div>
    </section>

    <!-- Contact Section -->
    <section id="contact">
      <h2>Contact Us</h2>
      <form id="contactForm">
        <input type="text" id="name" name="name" placeholder="Your Name" required />
        <input type="email" id="email" name="email" placeholder="Your Email" required />
        <textarea id="message" name="message" rows="5" placeholder="Your Message" required></textarea>
        <button type="submit">Send Message</button>
      </form>
      <div id="map">
        <p>Address: 01 TechSavvies, Tech City</p>
      </div>
    </section>
  </div>

  <!-- Footer Section -->
  <footer>
    <div class="footer-links">
      <a href="about.php">About Us</a>
      <a href="privacy.php">Privacy Policy</a>
      <a href="terms.php">Terms of Service</a>
    </div>
    <div class="footer-contact">
      <p><a href="mailto:support@techsavvies.shop">support@techsavvies.shop</a></p>
    </div>
  </footer>

  <script src="assets/js/home1.js"></script>
  <script src="assets/js/main.js"></script>
</body>
</html>