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
            border-radius: 5px;
            font-size: 16px;
            text-align: center;
        }
        .add-to-cart:hover {
            background: linear-gradient(135deg, #0117ff, #8d07cc, #d42d2d);
            -webkit-text-fill-color: white !important;
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
    </style>
</head>
<body>
    <!-- Include header -->
    <?php require_once __DIR__ . '/../../assets/php/header.php'; ?>

    <!-- Product container -->
    <div class="product-container">
        <div class="product-content">
            <div class="product-image">
                <img src="<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
            </div>
            <div class="product-info">
                <h2><?php echo htmlspecialchars($product['name']); ?></h2>
                <p><?php echo htmlspecialchars($product['description']); ?></p>
                <label>Average rating:
                    <div class="static-rating" style="--rating: <?php echo htmlspecialchars($product['rating']); ?>"></div>
                </label>
                <!-- Example select options; you might adjust them based on product type -->
                <p><strong>Color:</strong></p>
                <select>
                    <option>Black</option>
                    <option>Red</option>
                    <option>Blue</option>
                    <option>White</option>
                </select>
                <p><strong>Size:</strong></p>
                <select>
                    <option>Small</option>
                    <option>Medium</option>
                    <option>Large</option>
                    <option>XL</option>
                </select>
                <p><strong>Price:</strong> $<?php echo htmlspecialchars($product['price']); ?></p>
            </div>
        </div>
    
        <!-- Add to cart button -->
        <div class="add-to-cart-container">
            <a href="#" class="add-to-cart">Add to Cart</a>
        </div>

        <!-- Reviews section -->
        <div class="reviews-section">
            <h2>User Reviews</h2>
            <div class="reviews-container">
                <!-- Static reviews for demonstration -->
                <div class="review">
                    <strong>Emily R.</strong> <div class="static-rating" style="--rating: 5.0"></div>
                    <p>Absolutely love this product! Highly recommend!</p>
                </div>
                <div class="review">
                    <strong>John D.</strong> <div class="static-rating" style="--rating: 4.0"></div>
                    <p>Great quality and very comfortable to use.</p>
                </div>
                <div class="review">
                    <strong>Sophia M.</strong> <div class="static-rating" style="--rating: 5.0"></div>
                    <p>This backpack exceeded my expectations! The material is durable and the design is sleek.</p>
                </div>
                <div class="review">
                    <strong>Michael B.</strong> <div class="static-rating" style="--rating: 3.0"></div>
                    <p>Good overall, but the straps could be more padded for extra comfort.</p>
                </div>
            </div>
        </div> 
    </div>

    <!-- Include footer -->
    <?php require_once __DIR__ . '/../../assets/php/footer.php'; ?>

    <!-- Authentication Modal -->
    <?php require_once __DIR__ . '/../../assets/php/auth.php'; ?>
    
    <script src="/assets/js/main.js"></script>
</body>
</html>