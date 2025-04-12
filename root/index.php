<!-- devolped by @ananas0dev , @HameedJHD
 notes for improvment
 the popup windows should make the full webpage disabled
 add product and sorting remain
 extra feutures :
 fetch column from diffrent tables make some cells clickable to see more data
 add graphical dashboard piechart etc...
 -->

<!DOCTYPE html>
<html lang="en">

<head>
    <?php require_once __DIR__ . '/../assets/php/main.php'; ?>
    <link rel="stylesheet" href="..\assets\css\main.css">
    <link rel="stylesheet" href="..\assets\css\root.css">
    <script type="module" src="../assets/js/root.js"></script>
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
                <button id="manageProbutton"><img class="b-icon" src="..\assets\icons\Product.svg"><span>Products
                        Management</span></button>
                <button id="manageUserbutton"><img class="b-icon" src="..\assets\icons\User.svg"><span>Users
                        Management</span></button>
                <button id="Ordersbutton"><img class="b-icon"
                        src="..\assets\icons\Orders.svg"><span>Orders</span></button>
                <button id="transactionsbutton"><img class="b-icon"
                        src="..\assets\icons\transaction.svg"><span>Transactions</span></button>
                <button id="Reviewsbutton"><img class="b-icon"
                        src="..\assets\icons\review.svg"><span>Reviews</span></button>
                <button id="Logoutbutton"><svg class="b-icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo-bgCarrier" stroke-width="0"></g><g id="SVGRepo-tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo-iconCarrier"> <path d="M14 4L17.5 4C20.5577 4 20.5 8 20.5 12C20.5 16 20.5577 20 17.5 20H14M3 12L15 12M3 12L7 8M3 12L7 16" stroke="#000000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path> </g></svg>
                <span>Logout</span></button>
            </nav>
        </div>
        <!-- ##################################
            main area Section 
    ####################################-->
        <div class="funcarea">
            <div class="content-container">
                <!-- -----------------------------------------
                    Products page
                    ------------------------------------------>
                <?php include('..\assets\php\root_php\products.php')?>  <!-- our filr:product.php-->
                <!-- -----------------------------------------
                    users page
                    ------------------------------------------>
                <?php include('..\assets\php\root_php\users.php')?>  
                <!-- -----------------------------------------
                    orders page
                    ------------------------------------------>

                <?php include('..\assets\php\root_php\orders.php')?> 

                <!-- -----------------------------------------
                    transactions page
                    ------------------------------------------>
                <?php include('..\assets\php\root_php\transactions.php')?>
                <!-- -----------------------------------------
                    reviews page
                    ------------------------------------------>
                <?php include('..\assets\php\root_php\reviews.php')?>
        <!-- ##################################
            popup-forms Section 
    ####################################-->
        <div id="addProPopupdisplay" class="addProPopup">
            <span class="close" onclick="closeaddProPopup()">&times;</span>
            <form name="add-product-form" id="addProductform" method="post" action="index.php">
                <div id="add-display" class="AddProduct">
                    <div style="display: inline;">
                        <label for="product-name">Product Name:</label>
                        <input type="text" name="product-name" id="productname" placeholder="Enter the product's name"
                            maxlength="255" required>

                        <label for="categoryList">Category:</label>
                        <input id="categoryList" type="text" name="Pro-category" placeholder="Choose a category..."
                            list="Pro-category">
                        <datalist id="Procategory">
                            <option value="test1"></option>
                            <option value="test2"></option>
                        </datalist>

                    <div id="imageContainer">
                        <label for="imageUpload">Upload Image:</label>
                        <input id="imageUpload" type="file" name="image" accept="image/*">

  
                        <label for="ProductDescreption">Product Description:</label>
                        <textarea id="ProductDescreption" placeholder="Product description" naem="ProductDescreption"
                            required></textarea>

                        <label for="product-color">Product Color:</label>
                        <input type="text" name="product-color" id="product-color"
                            placeholder="Enter the product's color" maxlength="255" required>
                    </div>
                    <div style="display: inline;">
                        <label for="product-size">Product Size:</label>
                        <input type="text" name="product-size" id="product-size" placeholder="Enter the product's size"
                            maxlength="50" required>


                        <label for="product-price">Product Price:</label>
                        <input type="number" name="product-price" id="productprice"
                            placeholder="Enter the product's price" step="0.01" required>


                        <label for="product-stock">Product Stock:</label>
                        <input type="number" name="product-stock" id="productstock"
                            placeholder="Enter the product's stock" step="1" required>
                    </div>
                    <input name="submit-add-product" id="submitaddproduct" type="submit" value="Add" />
                </div>
            </form>
        </div>
</body>

</html>