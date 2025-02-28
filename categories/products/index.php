<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Page</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            flex-direction: column;
            align-items: center;
            height: 100vh;
            background-color: #ffffff;
        }
        .product-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 20px;
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
        .add-to-cart {
            display: inline-block;
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
            background: #8119b2;
        }
        .product-description {
            
            width: 45%;
            max-height: 150px;
            overflow-y: auto;
            margin-top: 20px;
            padding: 10px;
            background: #ffffff;
        }
    </style>
</head>
<body>
    <div class="product-container">
        <div class="product-content">
            <div class="product-image">
                <img src="categories\image.jpg" alt="Product Image">
            </div>
            <div class="product-info">
                <h2>Product name: snsnsnnsnsn ssnjsjsj</h2>
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
                <a href="#" class="add-to-cart">Add to Cart</a>
            </div>
        </div>
        <div class="product-description">
            <p>This is a great product with excellent features and high durability. It is designed to meet your needs and expectations, offering top-notch performance and reliability. The sleek design makes it a perfect fit for various occasions. Whether you need it for daily use or special events, this product will not disappoint. Experience comfort and convenience like never before.</p>
        </div>
    </div>
</body>
</html>
