<!-- devolped by @ananas0dev , @HameedJHD
 notes for improvment
 the popup windows should make the full webpage disabled
 fix the edit button and make a textfield for the editable sections
 
 #Rootv0.1 Not stable
 -->

<!DOCTYPE html>
<html lang="en">

<head>
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
                <button id="manageUser_button"><img class="b-icon" src="..\assets\icons\User.svg"><span>Users
                        Management</span></button>
                <button id="Orders_button"><img class="b-icon"
                        src="..\assets\icons\Orders.svg"><span>Orders</span></button>
                <button id="transactions_button"><img class="b-icon"
                        src="..\assets\icons\transaction.svg"><span>Transactions</span></button>
                <button id="Reviews_button"><img class="b-icon"
                        src="..\assets\icons\review.svg"><span>Reviews</span></button>
                <button id="Logout_button"><svg class="b-icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M14 4L17.5 4C20.5577 4 20.5 8 20.5 12C20.5 16 20.5577 20 17.5 20H14M3 12L15 12M3 12L7 8M3 12L7 16" stroke="#000000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path> </g></svg>
                <span>Logout</span></button>
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
                    users page
                    ________________________________________-->
                <?php include('..\assets\php\root_php\users.php')?>  
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
        <!-- ##################################
            popup_forms Section 
    ####################################-->
        <div id="addProPopup_display" class="addProPopup">
            <span class="close" onclick="closeaddProPopup()">&times;</span>
            <form name="add_product_form" id="addProduct_form" method="post" action="index.php">
                <div id="add_display" class="AddProduct">
                    <div style="display: inline;">
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

                    <div id="imageContainer">
                        <label for="imageUpload">Upload Image:</label>
                        <input id="imageUpload" type="file" name="image" accept="image/*">

  
                        <label for="ProductDescreption">Product Description:</label>
                        <textarea id="ProductDescreption" placeholder="Product description" naem="ProductDescreption"
                            required></textarea>

                        <label for="product_color">Product Color:</label>
                        <input type="text" name="product_color" id="product_color"
                            placeholder="Enter the product's color" maxlength="255" required>
                    </div>
                    <div style="display: inline;">
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

        <!-- ##################################
            script Section 
    ####################################-->
        <script src="../assets/js/root.js"></script> <!-- keep last in body so all html elemnts are loaded-->
</body>

</html>