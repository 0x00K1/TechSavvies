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
// Database connection
$host = 'localhost';
$dbname = 'techsavvies';
$username = 'root'; // Replace with your actual username
$password = '1234567'; // Replace with your actual password

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





<!-- Sidebar Navigation -->
    <nav class="sidebar" aria-label="Filter Sidebar Navigation">

         <div>
        <!-- Right Sidebar (Filters Form) -->
        <aside class="filter-sidebar">
            <h2>Filters</h2>
            <form action="#" method="GET">
                <!-- T-shirts Sizes Filter -->
            <details class="filter-group" id="t-shirts-sizes" hidden>
                <summary>Size</summary>
                <label class="cb1" for="size-S">
                    <input type="checkbox" name="size" value="S" id="size-S">
                    <span class="checkmark"></span>S
                </label>
                <label class="cb1" for="size-M">
                    <input type="checkbox" name="size" value="M" id="size-M">
                    <span class="checkmark"></span>M
                </label>
                <label class="cb1" for="size-L">
                    <input type="checkbox" name="size" value="L" id="size-L">
                    <span class="checkmark"></span>L
                </label>
                <label class="cb1" for="size-XL">
                    <input type="checkbox" name="size" value="XL" id="size-XL">
                    <span class="checkmark"></span>XL
                </label>
                <label class="cb1" for="size-XXL">
                    <input type="checkbox" name="size" value="XXL" id="size-XXL">
                    <span class="checkmark"></span>XXL
                </label>
            </details>

                <!-- T-shirts Colors Filter -->
                <details class="filter-group" id="t-shirts-colors" hidden>
                    <summary>Color</summary>
                    <div class="swatches">
                        <label>
                            <input type="radio" name="color" value="black" id="black-tshirts">
                            <div class="swatch" style="background-color: black;"></div>
                        </label>
                        <label>
                            <input type="radio" name="color" value="white" id="white-tshirts">
                            <div class="swatch" style="background-color: white; border: 1px solid #ccc;"></div>
                        </label>
                        <label>
                            <input type="radio" name="color" value="Mauve" id="mauve-tshirts">
                            <div class="swatch" style="background-color: #642d3d;"></div>
                        </label>
                        <label>
                            <input type="radio" name="color" value="Warm Gray" id="warm-gray-tshirts">
                            <div class="swatch" style="background-color: #C9C6BE;"></div>
                        </label>
                        <label>
                            <input type="radio" name="color" value="Dark Gray" id="dark-gray-tshirts">
                            <div class="swatch" style="background-color: #575551;"></div>
                        </label>
                    </div>
                </details>

            <!-- Backpacks Material Filter -->
            <details class="filter-group" id="backpacks-material" hidden>
                <summary>Material</summary>
                <label class="cb1" for="cotton">
                <input type="checkbox" name="material" value="cotton" id="cotton">
                <span class="checkmark"></span>Cotton</label>
                <label class="cb1" for="nylon">
                <input type="checkbox" name="material" value="nylon" id="nylon">
                <span class="checkmark"></span>Nylon</label>
                <label class="cb1" for="Fabric">
                <input type="checkbox" name="material" value="Fabric" id="Fabric">
                <span class="checkmark"></span>Fabric</label>
                <label class="cb1" for="leather">
                <input type="checkbox" name="material" value="leather" id="leather">
                <span class="checkmark"></span>Leather</label>
                <label class="cb1" for="polyester">
                <input type="checkbox" name="material" value="polyester" id="polyester">
                <span class="checkmark"></span>Polyester</label>
            </details>

                <!-- Backpacks Colors Filter -->
                <details class="filter-group" id="backpacks-colors" hidden>
                    <summary>Color</summary>
                    <div class="swatches">
                        <label>
                            <input type="radio" name="color" value="black" id="black-backpack">
                            <div class="swatch" style="background-color: black;"></div>
                        </label>
                        <label>
                            <input type="radio" name="color" value="white" id="white-backpack">
                            <div class="swatch" style="background-color: white; border: 1px solid #ccc;"></div>
                        </label>
                        <label>
                            <input type="radio" name="color" value="Dark blue" id="dark-blue-backpack">
                            <div class="swatch" style="background-color: #21384c;"></div>
                        </label>
                        <label>
                            <input type="radio" name="color" value="Dark Gray" id="dark-gray-backpack">
                            <div class="swatch" style="background-color: #575551;"></div>
                        </label>
                    </div>
                </details>

            <!-- Books Release Year Filter -->
            <details class="filter-group" id="release-year" hidden>
                <summary>Release Year</summary>
                <label class="rb1" for="now-2020">
                    <input type="radio" name="release-year" value="now-2020" id="now-2020">
                    <span class="checkmark"></span>Now - 2020
                </label>
                <label class="rb1" for="2019-2010">
                    <input type="radio" name="release-year" value="2019-2010" id="2019-2010">
                    <span class="checkmark"></span>2019 - 2010
                </label>
                <label class="rb1" for="2009-2000">
                    <input type="radio" name="release-year" value="2009-2000" id="2009-2000">
                    <span class="checkmark"></span>2009 - 2000
                </label>
                <label class="rb1" for="before-2000">
                    <input type="radio" name="release-year" value="before-2000" id="before-2000">
                    <span class="checkmark"></span>Before 2000
                </label>
            </details>


            <!-- Mugs Colors Filter -->
            <details class="filter-group" id="mugs-colors" hidden>
                    <summary>Color</summary>
                    <div class="swatches">
                        <label>
                            <input type="radio" name="color" value="black" id="black-mugs">
                            <div class="swatch" style="background-color: black;"></div>
                        </label>
                        <label>
                            <input type="radio" name="color" value="white" id="white-mugs">
                            <div class="swatch" style="background-color: white; border: 1px solid #ccc;"></div>
                        </label>
                    </div>
                </details>

                <!-- Phone Cases Colors Filter -->
            <details class="filter-group" id="phone-cases-colors" hidden>
                    <summary>Color</summary>
                    <div class="swatches">
                        <label>
                            <input type="radio" name="color" value="black" id="black-phonecase">
                            <div class="swatch" style="background-color: black;"></div>
                        </label>
                        <label>
                            <input type="radio" name="color" value="white" id="white-phonecase">
                            <div class="swatch" style="background-color: white; border: 1px solid #ccc;"></div>
                        </label>
                        <label>
                            <input type="radio" name="color" value="Yellow" id="yellow-phonecase">
                            <div class="swatch" style="background-color: yellow;"></div>
                        </label>
                        <label>
                            <input type="radio" name="color" value="Gray" id="gray-phonecase">
                            <div class="swatch" style="background-color: gray;"></div>
                        </label>
                    </div>
                </details>


                <!-- Price Range Filter For all Categories -->
<details class="filter-group" id="price-range">
    <summary>Price Range (in $)</summary>

    
        <div class="price-input-container">
            <div class="price-input">
                <div class="price-field">
                    <span>Minimum Price</span>
                    <input type="number" class="min-input" value="0">
                </div>
                <div class="price-field">
                    <span>Maximum Price</span>
                    <input type="number" class="max-input" value="1500">
                </div>
            </div>
            <div class="slider">
                <div class="price-slider progress"></div>
            </div>
            <!-- Slider -->
            <div class="range-input">
            <input type="range" class="min-range left-thumb" min="0" max="1500" value="0" step="1">
            <input type="range" class="max-range right-thumb" min="0" max="1500" value="1500" step="1">
            </div>
            <button id="reset-slider" style="width: 75%; height:40px; text-align: center;font-size: 12px; font-weight: bold; margin-top: 20px; margin-left: 24px;"><span>Reset Slider</span></button>
        </div>

        
    
</details>

                <!-- Rating Filter For all Categories -->
                <details class="filter-group" id="rating">
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

  <script>
document.addEventListener("DOMContentLoaded", () => {
    const rangevalue = document.querySelector(".slider .price-slider");
    const rangeInputvalue = document.querySelectorAll(".range-input input");
    const priceInputvalue = document.querySelectorAll(".price-input input");
    const resetButton = document.getElementById("reset-slider");

    // Set the minimum price gap
    let priceGap = 15; // Adjusted price gap

    // Function to update the slider progress bar
    const updateSlider = (minVal, maxVal, maxRange) => {
        rangevalue.style.left = `${(minVal / maxRange) * 100}%`;
        rangevalue.style.right = `${100 - (maxVal / maxRange) * 100}%`;
    };

    // Initialize the slider progress bar on page load
    const initializeSlider = () => {
        const minVal = parseInt(rangeInputvalue[0].value);
        const maxVal = parseInt(rangeInputvalue[1].value);
        const maxRange = parseInt(rangeInputvalue[0].max);
        updateSlider(minVal, maxVal, maxRange);
    };

    // Event listener for price input fields
    priceInputvalue.forEach((input, index) => {
        input.addEventListener("input", () => {
            let minp = parseInt(priceInputvalue[0].value);
            let maxp = parseInt(priceInputvalue[1].value);

            // Validate minimum and maximum values
            if (minp < 0) {
                priceInputvalue[0].value = 0;
                minp = 0;
            }

            if (maxp > 1500) { // Adjusted max value
                priceInputvalue[1].value = 1500;
                maxp = 1500;
            }

            // Ensure the thumbs do not collide
            if (minp > maxp - priceGap) {
                minp = maxp - priceGap;
                priceInputvalue[0].value = minp;
            }

            if (maxp - minp >= priceGap) {
                rangeInputvalue[0].value = minp;
                rangeInputvalue[1].value = maxp;

                const maxRange = parseInt(rangeInputvalue[0].max);
                updateSlider(minp, maxp, maxRange);
            }
        });
    });

    // Event listener for range input sliders
    rangeInputvalue.forEach((input) => {
        input.addEventListener("input", (e) => {
            let minVal = parseInt(rangeInputvalue[0].value);
            let maxVal = parseInt(rangeInputvalue[1].value);

            // Ensure the thumbs do not collide
            if (maxVal - minVal < priceGap) {
                if (e.target.classList.contains("min-range")) {
                    minVal = maxVal - priceGap;
                    rangeInputvalue[0].value = minVal;
                } else {
                    maxVal = minVal + priceGap;
                    rangeInputvalue[1].value = maxVal;
                }
            }

            priceInputvalue[0].value = minVal;
            priceInputvalue[1].value = maxVal;

            const maxRange = parseInt(rangeInputvalue[0].max);
            updateSlider(minVal, maxVal, maxRange);
        });
    });

    // Reset Slider Button Functionality
    resetButton.addEventListener("click", (event) => {
        event.preventDefault(); // Prevent the default form submission behavior

        const maxRange = parseInt(rangeInputvalue[0].max);

        // Reset the slider values
        rangeInputvalue[0].value = 0;
        rangeInputvalue[1].value = maxRange;

        // Reset the price input fields
        priceInputvalue[0].value = 0;
        priceInputvalue[1].value = maxRange;

        // Update the slider progress bar
        updateSlider(0, maxRange, maxRange);
    });

    // Call the initialize function on page load
    initializeSlider();
});
</script>


</body>
</html>


