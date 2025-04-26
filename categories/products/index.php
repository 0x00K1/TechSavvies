<?php
require_once __DIR__ . '/../../includes/get_products.php';

$productsById = [];
foreach ($products as $prod) {
    $productsById[$prod['product_id']] = $prod;
}

if (isset($_GET['product_id']) && array_key_exists($_GET['product_id'], $productsById)) {
    $product = $productsById[$_GET['product_id']];
} else {
    echo "Product not found.";
    exit;
}
?>
<html lang="en">
<head>
    <title>Product</title>
    <?php require_once __DIR__ . '/../../assets/php/main.php'; ?>
    <link rel="stylesheet" href="/../../assets/css/main.css">
    <style>
        .product-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding-top: 100px;
            border-radius: 10px;
        }
        .product-content {
            display: flex;
        }
        .product-image img {
            width: 300px;
            height: auto;
            border-radius: 10px;
        }
        .product-info {
            margin-left: 20px;
            padding: 20px;
            border: 1px solid #fefbfb;
            border-radius: 10px;
            max-width: 400px;
        }
        .product-info h2 {
            margin: 0 0 10px;
            word-wrap: break-word;
        }
        .product-info p {
            margin: 5px 0;
        }
        select {
            padding: 5px;
            margin-top: 5px;
            border-radius: 5px;
            border: 1px solid #ddd;
        }
        .add-to-cart-container {
            width: 100%;
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }
        .add-to-cart {
            display: inline-block;
            width: 200px;
            padding: 10px 20px;
            margin-top: 10px;
            background: linear-gradient(135deg, #0117ff, #8d07cc, #d42d2d);
            color: white;
            text-decoration: none;
            cursor: pointer;
            border-radius: 5px;
            font-size: 16px;
            text-align: center;
            transition: all 0.2s ease; /* smoother hover transition */
        }

        .add-to-cart:hover {
            filter: brightness(1.1);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }
        .reviews-section {
            width: 80%;
            max-width: 600px;
            margin-top: 50px;
            padding: 20px;
            border-radius: 10px;
            background: #f9f9f9;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .reviews-section h2 {
            text-align: center;
            margin-bottom: 15px;
        }
        .reviews-container {
            max-height: 250px;
            overflow-y: auto;
            padding-right: 10px;
        }
        .review {
            background: white;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 10px;
            border-left: 5px solid #8d07cc;
        }
        .review strong {
            color: #8d07cc;
        }
        .static-rating {
            display: inline-block;
            position: relative;
            font-size: 18px;
            color: #FFD700;
        }
        .static-rating::before {
            content: '\2605\2605\2605\2605\2605';
            letter-spacing: 3px;
            color: #ddd;
        }
        .static-rating::after {
            content: '\2605\2605\2605\2605\2605';
            letter-spacing: 3px;
            color: #FFD700;
            position: absolute;
            top: 0;
            left: 0;
            width: calc(var(--rating, 0) / 5 * 100%);
            overflow: hidden;
            white-space: nowrap;
        }
        .toast-container {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 1000;
            display: none;
        }
        
        .toast {
            background-color: #333;
            color: white;
            padding: 15px 20px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
            min-width: 280px;
            max-width: 400px;
        }
        
        .toast.success {
            background-color: #0ca71c;
        }
        
        .toast.error {
            background-color: #d42d2d;
        }
        
        .toast-content {
            flex-grow: 1;
            margin-right: 10px;
        }
        
        .toast-close {
            background: none;
            border: none;
            color: white;
            font-size: 20px;
            cursor: pointer;
            padding: 0 5px;
        }
    </style>
    <script src="/assets/js/main.js"></script>
</head>
<body>
    <!-- Include header -->
    <?php require_once __DIR__ . '/../../assets/php/header.php'; ?>

    <div class="product-container">
        <div class="product-content">
            <div class="product-image">
                <img id="productImage" src="<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
            </div>
            <div class="product-info">
                <h2 id="productName"><?php echo htmlspecialchars($product['name']); ?></h2>
                <p><?php echo htmlspecialchars($product['description']); ?></p>
                <label>Rate:
                    <div class="static-rating" style="--rating: <?php echo htmlspecialchars($product['rating']); ?>"></div>
                </label>

                <!-- Example: only show color/size if category is "tshirts" -->
                <?php
                    $categoryLower = strtolower($product['category']);
                    if ($categoryLower === 'tshirts'): 
                ?>
                    <p><strong>Color:</strong></p>
                    <select id="colorSelect">
                        <option>Black</option>
                        <option>Red</option>
                        <option>Blue</option>
                        <option>White</option>
                    </select>

                    <p><strong>Size:</strong></p>
                    <select id="sizeSelect">
                        <option>Small</option>
                        <option>Medium</option>
                        <option>Large</option>
                        <option>XL</option>
                    </select>
                <?php endif; ?>

                <p><strong>Price:</strong> $<span id="productPrice"><?php echo htmlspecialchars($product['price']); ?></span></p>

                <!-- Hidden fields or data attributes to store product ID, etc. -->
                <input type="hidden" id="productId" value="<?php echo htmlspecialchars($product['product_id']); ?>">
                <input type="hidden" id="quantityInput" value="1">
                <input type="hidden" id="productStock" value="<?php echo htmlspecialchars($product['stock']); ?>">
            </div>
        </div>

        <!-- Add to Cart button -->
        <div class="add-to-cart-container">
            <!-- We'll attach a click event in JS -->
            <button type="button" class="add-to-cart">Add to Cart</button>
        </div>

        <!-- Reviews section -->
<div class="reviews-section">
    <h2>Customer Reviews</h2>

    <!-- Review Submission Form -->
    <div class="submit-review">
        <h3>Leave a Review</h3>
        <form id="reviewForm">
            <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($product['product_id']); ?>">
            <input type="hidden" name="customer_id" value="1"> <!-- TEMPORARY: Replace with actual logged-in user ID -->

            <label for="rating">Rating (1-5):</label>
            <select name="rating" id="rating" required>
                <option value="">Select</option>
                <option value="5">5 - Excellent</option>
                <option value="4">4 - Good</option>
                <option value="3">3 - Average</option>
                <option value="2">2 - Poor</option>
                <option value="1">1 - Terrible</option>
            </select>

            <label for="review_text">Your Review:</label>
            <textarea name="review_text" id="review_text" rows="4" required></textarea>

            <button type="submit">Submit Review</button>
        </form>
        <div id="reviewMessage"></div>
    </div>

    <!-- Existing Reviews -->
    <div class="reviews-container">
        <?php
        require_once __DIR__ . '/get_reviews.php'; // ✅ correct relative path!

        $reviews = getReviewsByProductId($product['product_id']); // fetch reviews

        if (!empty($reviews)) {
            foreach ($reviews as $review) {
                echo '<div class="review">';
                echo '<strong>' . htmlspecialchars($review['username']) . '</strong>';
                echo '<div class="static-rating" style="--rating: ' . htmlspecialchars($review['rating']) . '"></div>';
                echo '<p>' . htmlspecialchars($review['review_text']) . '</p>';
                echo '</div>';
            }
        } else {
            echo '<p>No reviews yet. Be the first to review!</p>';
        }
        ?>
    </div>
</div>


</div>

    </div>

    <!-- Loading Overlay -->
    <div class="loading-overlay" id="loadingOverlay">
        <div class="loading-spinner"></div>
    </div>

    <!-- Success/Error Message Toast -->
    <div class="toast-container" id="toastContainer">
        <div class="toast" id="toast">
            <div class="toast-content" id="toastContent"></div>
            <button class="toast-close" id="toastClose">&times;</button>
        </div>
    </div>
    
    <!-- Include footer -->
    <?php require_once __DIR__ . '/../../assets/php/footer.php'; ?>
    
    <script src="/assets/js/addtocart.js"></script>
</body>
</html>
