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



<div class="external_grid">
    <!-- First card -->
    <div class="card_grid">
        <div class="product"><img src="..\assets\images\Products\Brand\T-shirt\Front-1.png" alt="Whtie T-shirt Picture">
            <h1>White T-shirt with Logo</h1>
            <h5>Made of pure cutton 100%,
                YOU SHOULD NOT THINK THIS
                IS REAL!
            </h5>
            <div class="static-rating" style="--rating: 3.5;"></div>
            <h2>$100</h2>
        </div>

    </div>
    <!-- Second card -->
    <div class="card_grid">
        <div class="product"><img src="..\assets\images\Products\Brand\T-shirt\Front.png" alt="Black T-shirt Picture">
            <h1>Black T-shirt with Logo</h1>
            <h5>Made of pure cutton 100%,
                YOU SHOULD NOT THINK THIS
                IS REAL!
            </h5>
            <div class="static-rating" style="--rating: 5;"></div>
            <h2>$100</h2>
        </div>
    </div>

    <!-- Third card -->
    <div class="card_grid">
        <div class="product"><img src="..\assets\images\Products\Non-Brand\T-shirts\mockup-7f7164bd_720x.png" alt="I need a &lt;br/&gt; T-shirt Picture">
            <h1>I need a &lt;br/&gt; T-shirt</h1>
            <h5>Made of pure cutton 100%,
                YOU SHOULD NOT THINK THIS
                IS REAL!
            </h5>
            <div class="static-rating" style="--rating: 4.2;"></div>
            <h2>$75</h2>
        </div>
    </div>
    <!-- Fourth card -->
    <div class="card_grid">
        <div class="product"><img src="..\assets\images\Products\Non-Brand\T-shirts\il_794xN.5281097748_2ufp.png" alt="I are Programmer T-shirt">
            <h1>I are Programmer T-shirt</h1>
            <h5>Made of pure cutton 100%,
                YOU SHOULD NOT THINK THIS
                IS REAL!
            </h5>
            <div class="static-rating" style="--rating: 2.75;"></div>
            <h2>$75</h2>
        </div>
    </div>
    <!-- Fifth card -->
    <div class="card_grid">
        <div class="product"><img src="..\assets\images\Products\Non-Brand\T-shirts\unisex-staple-t-shirt-black-front-66eaef158d4ea_720x.png" alt="Choose Your Weapon T-shirt">
            <h1>Choose Your Weapon T-shirt</h1>
            <h5>Made of pure cutton 100%,
                YOU SHOULD NOT THINK THIS
                IS REAL!
            </h5>
            <div class="static-rating" style="--rating: 4.0;"></div>
            <h2>$75</h2>
        </div>

    </div>
    <!-- Sixth card -->
    <div class="card_grid">
        <div class="product"><img src="..\assets\images\Products\Non-Brand\T-shirts\il_794xN.5785303495_59t7.png" alt="CTRL + PURR T-shirt">
            <h1>CTRL + PURR T-shirt</h1>
            <h5>Made of pure cutton 100%,
                YOU SHOULD NOT THINK THIS
                IS REAL!
            </h5>
            <div class="static-rating" style="--rating: 4.5;"></div>
            <h2>$75</h2>
        </div>
        </div>
    <!-- Seventh card -->
        <div class="card_grid">
            <div class="product"><img src="..\assets\images\Products\Non-Brand\T-shirts\il_794xN.4294904508_t966.png" alt="Just Put a ticket in T-shirt Dark Gray">
                <h1>Just Put a ticket in T-shirt</h1>
                <h5>Made of pure cutton 100%,
                    YOU SHOULD NOT THINK THIS
                    IS REAL!
                </h5>
                <div class="static-rating" style="--rating: 4.1;"></div>
                <h2>$75</h2>
            </div>
        </div>
        <!-- Eighth card -->
        <div class="card_grid">
            <div class="product"><img src="..\assets\images\Products\Non-Brand\T-shirts\il_794xN.4342291955_pirs.png" alt="Just Put a ticket in T-shirt Light Gray">
                <h1>Just Put a ticket in T-shirt light gray t shirt</h1>
                <h5>Made of pure cutton 100%,
                    YOU SHOULD NOT THINK THIS
                    IS REAL!
                </h5>
                <div class="static-rating" style="--rating: 3.9;"></div>
                <h2>$75</h2>
            </div>
        </div>
         <!-- ninth card -->
         <div class="card_grid">
            <div class="product"><img src="..\assets\images\Products\Non-Brand\T-shirts\il_794xN.5394430315_qarj.png" alt="Call me tech wizard T-shirt">
                <h1>Call me tech wizard T-shirt</h1>
                <h5>Made of pure cutton 100%,
                    YOU SHOULD NOT THINK THIS
                    IS REAL!
                </h5>
                <div class="static-rating" style="--rating: 3.3;"></div>
                <h2>$75</h2>
            </div>
        </div>
    </div>
    <!-- Animation for BODY section -->

</div>

<!-- Hidden checkbox for toggling sidebar -->
<input type="checkbox" id="menu-toggle">
<label for="menu-toggle" class="menu-icon">&#9776;</label>

<!-- Sidebar Navigation -->
<nav class="sidebar">
    <!-- Menu Container -->
    <div class="menu-container">
        <!-- User Menu -->
        <ul class="user-menu">
            <li></li><li></li><li></li><li></li><li></li>
            <li><a href="index.php" style="color: #ffff;"><i class="fa fa-home"></i> Home</a></li>
            <li><a href="index.php#shop" style="color: #ffff;"><i class="fa fa-store"></i> Shop</a></li>
            <li><a href="index.php#popular" style="color: #ffff;"><i class="fa fa-star"></i> Popular Products</a></li>
            <li><a href="#" style="color: #ffff;"><i class="fa fa-cart-shopping"></i> Cart</a></li>
            <li><a href="index.php#contact" style="color: #ffff;"><i class="fa fa-envelope"></i> Contact Us</a></li>
        </ul>
    </div>
    <div class="menu-container" hidden>
        <ul class="admin-menu">
        <li></li><li></li><li></li>
            <li><a href="#" style="color: #ffff;"><i class="fa fa-tachometer-alt"></i> Dashboard</a></li>
            <li><a href="#" style="color: #ffff;"><i class="fa fa-users"></i> Manage Users</a></li>
            <li><a href="#" style="color: #ffff;"><i class="fa fa-box"></i> Manage Products</a></li>
            <li><a href="#" style="color: #ffff;"><i class="fa fa-shopping-cart"></i> Orders</a></li>
            <li><a href="#" style="color: #ffff;"><i class="fa fa-chart-line"></i> Reports</a></li>
            <li><a href="#" style="color: #ffff;"><i class="fa fa-cogs"></i> Settings</a></li>
        </ul>
    </div>
</nav>





<!-- Sidebar Navigation -->
<nav class="sidebar">


    <div class="container">
        <!-- Right Sidebar (Filters Form) -->
        <aside class="filter-sidebar">
            <h2>Filters</h2>
            <form action="#" method="GET">

                <!-- Category Filter (Collapsible) -->
                <details class="filter-group">
                    <summary>Category</summary>
                    <label><input type="radio" name="categories" value="Tshirts" checked> T-shirts</label><br>
                    <label><input type="radio" name="categories" value="BackPacks"> BackPacks</label><br>
                    <label><input type="radio" name="categories" value="Books"> Books</label><br>
                    <label><input type="radio" name="categories" value="Laptops"> Laptops</label><br>
                    <label><input type="radio" name="categories" value="Stickers"> Stickers</label><br>
                    <label><input type="radio" name="categories" value="SoftwareTools"> Software Tools</label><br>
                    <label><input type="radio" name="categories" value="HardwareTools"> Hardware Tools</label><br>
                    <label><input type="radio" name="categories" value="Mugs"> Mugs</label><br>
                    <label><input type="radio" name="categories" value="PhoneCases"> Phone Cases</label><br>
                    <label><input type="radio" name="categories" value="Games"> Games</label>
                </details>

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
                            <input type="number" id="maxPrice" name="maxPrice" value="10000" min="1" step="1">
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

            <!---->

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