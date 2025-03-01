<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php require_once __DIR__ . '/../../assets/php/main.php'; ?>
    <link rel="stylesheet" href="/../../assets/css/main.css">
    <title>Product Page</title>
    <style>

        .product-container {
            /* Container for the entire product section */
            display: flex;
            flex-direction: column;
            align-items: center;
            padding-top: 100px;
            border-radius: 10px;
        }

        /* Layout for product details */
        .product-content {
            display: flex;
        }

        /* Product image styling */
        .product-image img {
            width: 300px;
            height: auto;
            border-radius: 10px;
        }

        /* Styling for product information */
        .product-info {
            margin-left: 20px;
            padding: 20px;
            border: 1px solid#fefbfb;
            border-radius: 10px;
            max-width: 400px; /* Limit width for better layout */
        }

        /* Product title styling */
        .product-info h2 {
            flex-direction: row;
            margin: 0 0 10px;
            overflow-x: auto;
            word-wrap: break-word;
        }
        .product-info p {
            margin: 5px 0;
        }

        /* Dropdown select styling */
        select {
            
            padding: 5px;
            margin-top: 5px;
            border-radius: 5px;
            border: 1px solid #ddd;
        }

        /* Add to cart button container */
        .add-to-cart-container {
            width: 100%; /* Full width */
            display: flex;
            justify-content: center; /* Center the button */
            margin-top: 20px; /* Space from product content */
        }

        /* Add to cart button styling */
        .add-to-cart {
            display: inline-block;
            width: 200px; /* Fixed button width */
            padding: 10px 20px;
            margin-top: 10px;
            background: #8d07cc;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-size: 16px;
            text-align: center;
        }
        .add-to-cart:hover {
            -webkit-text-fill-color: white !important;
            background: #8119b2;
            color: white !important;
        }

        /* Reviews section styling */
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

        /* Reviews container - limits visible height */
        .reviews-container {
            max-height: 250px; /* Limit height to 3 reviews */
            overflow-y: auto; /* Enable vertical scrolling */
            padding-right: 10px;
        }

        /* Individual review styling */
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
        

        /* Static rating star styling */
        .static-rating {
            display: inline-block;
            position: relative;
            font-size: 18px;
            color: #FFD700; /* Gold color for stars */
        }

        .static-rating::before {
            content: '\2605\2605\2605\2605\2605'; /* Default 5 stars */
            letter-spacing: 3px;
            color: #ddd; /* Gray background stars */
        }

        .static-rating::after {
            content: '\2605\2605\2605\2605\2605'; /* Filled stars */
            letter-spacing: 3px;
            color: #FFD700; /* Gold stars */
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
                <img src="../../assets/images/backpacks.png" alt="Product Image">
            </div>
            <div class="product-info">
                <h2>High quality Backpack</h2>
                <p>A Backpack made with the finest of fabrics, perfect for Tech-Savvies</p>
                <label>Average rating:<div class="static-rating" style="--rating: 4.25"></div></label>
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
                <p><strong>Price:</strong> $49.99</p>
        
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
                <div class="review">
                    <strong>Emily R.</strong> <div class="static-rating" style="--rating: 5.0"></div>
                    <p>Absolutely love this backpack! It's stylish and fits everything I need. Highly recommend!</p>
                </div>

                <div class="review">
                    <strong>John D.</strong> <div class="static-rating" style="--rating: 4.0"></div>
                    <p>Great quality and very comfortable to wear. The only downside is that I wish it had more compartments.</p>
                </div>

                <div class="review">
                    <strong>Sophia M.</strong> <div class="static-rating" style="--rating: 5.0"></div>
                    <p>This backpack exceeded my expectations! The material is durable and the design is sleek.</p>
                </div>

                <div class="review">
                    <strong>Michael B.</strong> <div class="static-rating" style="--rating: 3.0"></div>
                    <p>Good overall, but the straps could be more padded for extra comfort.</p>
                </div>

                <div class="review">
                    <strong>Alex T.</strong> <div class="static-rating" style="--rating: 5.0"></div>
                    <p>Amazing product! I use it daily, and it still looks brand new.</p>
                </div>

                <div class="review">
                    <strong>Linda G.</strong> <div class="static-rating" style="--rating: 4.0"></div>
                    <p>Very spacious and lightweight. Perfect for traveling.</p>
                </div>
            </div>
        </div> 
    </div>

    <!-- Include footer -->
    <?php require_once __DIR__ . '/../../assets/php/footer.php'; ?>

    <!-- Authentication Modal -->
    <?php require_once __DIR__ . '/../../assets/php/auth.php'; ?>

</body>
</html>