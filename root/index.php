<!-- devolped by @ananas0dev , @HameedJHD
 notes for improvment
 the popup windows should make the full webpage disabled
 fix the edit button and make a textfield for the editable sections
 
 #Rootv0.1 Not stable
 -->

<!DOCTYPE html>
<html lang="en">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <?php require_once __DIR__ . '/../assets/php/main.php'; ?>
    <link rel="stylesheet" href="..\assets\css\main.css">
    <link rel="stylesheet" href="..\assets\css\root.css">
    <title>Root</title>
</head>
<body>
    <!-- ##################################
            Header Section 
    ####################################-->
    <?php include('..\assets\php\header.php'); ?>
    <div class="Boss">
        <!-- ##################################
            toolbar Section 
    ####################################-->
        <div class="toolbar">
            <nav class="nav">
                <button id="managePro_button"><img class="b-icon" src="..\assets\icons\Product.svg"><span>Products
                        Management</span></button>
                <button id="Orders_button"><img class="b-icon"
                        src="..\assets\icons\Orders.svg"><span>Orders</span></button>
                <button id="transactions_button"><img class="b-icon"
                        src="..\assets\icons\transaction.svg"><span>Transactions</span></button>
                <button id="Reviews_button"><img class="b-icon"
                        src="..\assets\icons\review.svg"><span>Reviews</span></button>
                <button id="Logout_button"><img class="b-icon" 
                src="..\assets\images\logout.png"><span>Logout</span></button>
            </nav>
        </div>
        <!-- ##################################
            main area Section 
    ####################################-->
        <div class="funcarea">
            <div class="content-container">
                <!-- _________________________________________
                    Products page
                    ________________________________________-->
                <?php include('..\assets\php\root_php\products.php')?>  <!-- our filr:product.php-->
                <!-- _________________________________________
                    orders page
                    ________________________________________-->

                <?php include('..\assets\php\root_php\orders.php')?> 

                <!-- _________________________________________
                    transactions page
                    ________________________________________-->
                <?php include('..\assets\php\root_php\transactions.php')?>
                <!-- _________________________________________
                    reviews page
                    ________________________________________-->
                <?php include('..\assets\php\root_php\reviews.php')?>
            </div> 
        </div>  

        <!-- ##################################
            popup_forms Section 
        ####################################-->
        <div id="addProPopup_display" class="addProPopup">
            <span class="close" onclick="closeaddProPopup()">&times;</span>
            <form name="add_product_form" id="addProduct_form" method="post" action="index.php">
                <div id="add_display" class="AddProduct">
                    <div class="form-row">
                        <label for="product_name">Product Name:</label>
                        <input type="text" name="product_name" id="product_name" placeholder="Enter the product's name"
                            maxlength="255" required>

                        <label for="categoryList">Category:</label>
                        <input id="categoryList" type="text" name="Pro_category" placeholder="Choose a category..."
                            list="Pro_category">
                        <datalist id="Pro_category">
                            <option value="test1"></option>
                            <option value="test2"></option>
                        </datalist>
                    </div>

                    <div id="imageContainer" class="form-row">
                        <label for="imageUpload">Upload Image:</label>
                        <input id="imageUpload" type="file" name="image" accept="image/*">

                        <label for="ProductDescreption">Product Description:</label>
                        <textarea id="ProductDescreption" placeholder="Product description" name="ProductDescreption"
                            required></textarea>

                        <label for="product_color">Product Color:</label>
                        <input type="text" name="product_color" id="product_color"
                            placeholder="Enter the product's color" maxlength="255" required>
                    </div>
                    
                    <div class="form-row">
                        <label for="product_size">Product Size:</label>
                        <input type="text" name="product_size" id="product_size" placeholder="Enter the product's size"
                            maxlength="50" required>

                        <label for="product_price">Product Price:</label>
                        <input type="number" name="product_price" id="product_price"
                            placeholder="Enter the product's price" step="0.01" required>

                        <label for="product_stock">Product Stock:</label>
                        <input type="number" name="product_stock" id="product_stock"
                            placeholder="Enter the product's stock" step="1" required>
                    </div>
                    <input name="submit_add_product" id="submit_add_product" type="submit" value="Add" />
                </div>
            </form>
        </div>
    </div>  

    <!-- ##################################
        script Section 
    ####################################-->
    <script src="../assets/js/root.js"></script>
</body>
</html>
