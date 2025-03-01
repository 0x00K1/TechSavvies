<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php require_once __DIR__ . '/../../assets/php/main.php'; ?>
    <link rel="stylesheet" href="/../../assets/css/main.css">
    <title>Product Page</title>
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
            border: 1px solid#fefbfb;
            border-radius: 10px;
            max-width: 400px; /* Limit width for better layout */
        }
        .product-info h2 {
            flex-direction: row;
            margin: 0 0 10px;
            overflow-x: auto;
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
            width: 100%; /* Full width */
            display: flex;
            justify-content: center; /* Center the button */
            margin-top: 20px; /* Space from product content */
        }
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
            max-height: 250px; /* Limit height to 3 reviews */
            overflow-y: auto; /* Enable vertical scrolling */
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

    </style>
</head>
<body>
<?php require_once __DIR__ . '/../../assets/php/header.php'; ?>
    <div class="product-container">
        <div class="product-content">
            <div class="product-image">
                <img src="../../assets/images/backpacks.png" alt="Product Image">
            </div>
            <div class="product-info">
                <h2>Product name: snsnsnnsnsn ssnjsjsj</h2>
                <p>Description goes here</p>
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
    


    <div class="add-to-cart-container">
        <a href="#" class="add-to-cart">Add to Cart</a>
    </div>
    <div class="reviews-section">
            <h2>User Reviews</h2>
            <div class="reviews-container">
                <div class="review">
                    <strong>Emily R.</strong> ⭐⭐⭐⭐⭐
                    <p>Absolutely love this backpack! It's stylish and fits everything I need. Highly recommend!</p>
                </div>

                <div class="review">
                    <strong>John D.</strong> ⭐⭐⭐⭐☆
                    <p>Great quality and very comfortable to wear. The only downside is that I wish it had more compartments.</p>
                </div>

                <div class="review">
                    <strong>Sophia M.</strong> ⭐⭐⭐⭐⭐
                    <p>This backpack exceeded my expectations! The material is durable and the design is sleek.</p>
                </div>

                <div class="review">
                    <strong>Michael B.</strong> ⭐⭐⭐☆☆
                    <p>Good overall, but the straps could be more padded for extra comfort.</p>
                </div>

                <div class="review">
                    <strong>Alex T.</strong> ⭐⭐⭐⭐⭐
                    <p>Amazing product! I use it daily, and it still looks brand new.</p>
                </div>

                <div class="review">
                    <strong>Linda G.</strong> ⭐⭐⭐⭐☆
                    <p>Very spacious and lightweight. Perfect for traveling.</p>
                </div>
            </div>
        </div> 
    </div>
    <?php require_once __DIR__ . '/../../assets/php/footer.php'; ?>

<!-- Authentication Modal -->
    <?php require_once __DIR__ . '/../../assets/php/auth.php'; ?>

</body>
</html>