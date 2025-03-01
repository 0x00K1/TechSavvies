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
    </div>

    <div class="add-to-cart-container">
        <a href="#" class="add-to-cart">Add to Cart</a>
    </div>
        
    <?php require_once __DIR__ . '/../../assets/php/footer.php'; ?>

<!-- Authentication Modal -->
    <?php require_once __DIR__ . '/../../assets/php/auth.php'; ?>

</body>
</html>