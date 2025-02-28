<!-- devolped by @ananas0dev , @HameedJHD
 notes for improvment
 the popup windows should make the full webpage disabled-->

<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="icon" href="..\assets\icons\Logo.ico">
    <link rel="stylesheet" href="..\assets\css\main.css">
    <link rel="stylesheet" href="..\assets\css\root.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <title>root</title>
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
                <!-- <button id="manageUser_button"><img class="b-icon" src="..\assets\icons\User.svg"><span>Users
                        Management</span></button> -->
                <button id="Orders_button"><img class="b-icon"
                        src="..\assets\icons\Orders.svg"><span>Orders</span></button>
                <button id="transactions_button"><img class="b-icon"
                        src="..\assets\icons\transaction.svg"><span>Transactions</span></button>
                <button id="Reviews_button"><img class="b-icon"
                        src="..\assets\icons\review.svg"><span>Reviews</span></button>
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
                <div id="EditProduct" class="content">
                    <form name="Product_queries" id="Product_queries" method="post" action="index.php">
                        <button id="addProPopup_button" class="addProPopup_button_style" type="button"
                            onclick="addProPopup()">Add</button>
                        <div class="search_div">
                            <input class="search-field_style" name="search_field" id="search_field" type="text"
                                placeholder="Search" />
                            <input class="search-button_style" name="search_button" id="search_button" type="submit"
                                value="Search" />
                            <span>Filter:</span>
                            <select name="filter_value" id="filter_value" class="filter_value_style"
                                placeholder="Chose a value">
                                <option value="">None</option>
                                <option name="f_category" value="category">Category</option>
                                <option name="f_price" value="price">Price</option>
                                <option name="f_stock" value="stock">Stock</option>
                                <option name="f_size" value="size">Size</option>
                                <option name="f_update_date" value="update_date">update Date</option>
                            </select>
                            <button name="order_toggler" id="order_toggler">^</button>
                            <!-- some crazy js for ico change-->
                        </div>
                        <div class="div">
                            <table>
                                <thead">
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Price</th>
                                        <th>Stock</th>
                                        <th>Category</th>
                                        <th>Updated Date</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>ID</td>
                                            <td>Name</td>
                                            <td>Price</td>
                                            <td>Stock</td>
                                            <td>Category</td>
                                            <td>Updated Date</td>
                                            <td>
                                                <div id="buttons_table_display" class="buttons_table"
                                                    style="display:block;">
                                                    <button name="remove_product_button" id="remove_product_button"
                                                        class="remove_product_button_style" type="button"
                                                        onclick="confirmationPopup()">Remove</button>
                                                    <button name="edit_product_button" id="edit_product_button"
                                                        class="edit_product_button_style" type="button"
                                                        onclick="product_edit_button()">Edit</button>
                                                </div>
                                                <div id="product_edit_display" class="product_edit_display"
                                                    style="display:none;">
                                                    <button id="product_cancel_edit" onclick="product_cancel_edit()"
                                                        class="product_cancel_edit_style" type="button">Cancel</button>
                                                    <input id="product_confirm_edit" class="product_confirm_edit_style"
                                                        type="submit" value="Confirm" />
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                            </table>
                        </div>
                        <?php include('..\assets\php\root_php\confirmation.php');?>
                        <!-- <- submit button-->
                    </form>
                </div>

                <?php include('..\assets\php\root_php\orders.php');?>
                <?php include('..\assets\php\root_php\transactions.php');?>
                <?php include('..\assets\php\root_php\reviews.php');?>
            </div>
        </div>
        <!-- ##################################
            popup_forms Section 
    ####################################-->
        <div id="addProPopup_display" class="addProPopup">
            <span class="close" onclick="closeaddProPopup()">&times;</span>
            <form name="add_product_form" id="addProduct_form" method="post" action="index.php">
                <div id="add_display" class="AddProduct">
                    <p>
                        <label for="product_name">Product Name:</label>
                        <input type="text" name="product_name" id="product_name" placeholder="Enter the product's name"
                            maxlength="255" required>
                    </p>
                    <p>
                        <label for="categoryList">Category:</label>
                        <input id="categoryList" type="text" name="Pro_category" placeholder="Choose a category..."
                            list="Pro_category">
                        <datalist id="Pro_category">
                            <option value="test1"></option>
                            <option value="test2"></option>
                        </datalist>
                    </p>
                    <div id="imageContainer">
                        <label for="imageUpload">Upload Image:</label>
                        <input id="imageUpload" type="file" name="image" accept="image/*">
                    </div>
                    <p>
                        <label for="ProductDescreption">Product Description:</label>
                        <textarea id="ProductDescreption" placeholder="Product description" naem="ProductDescreption"
                            required></textarea>
                    </p>
                    <p>
                        <label for="product_color">Product Color:</label>
                        <input type="text" name="product_color" id="product_color"
                            placeholder="Enter the product's color" maxlength="255" required>
                    </p>
                    <p>
                        <label for="product_size">Product Size:</label>
                        <input type="text" name="product_size" id="product_size" placeholder="Enter the product's size"
                            maxlength="50" required>
                    </p>
                    <p>
                        <label for="product_price">Product Price:</label>
                        <input type="number" name="product_price" id="product_price"
                            placeholder="Enter the product's price" step="0.01" required>
                    </p>
                    <p>
                        <label for="product_stock">Product Stock:</label>
                        <input type="number" name="product_stock" id="product_stock"
                            placeholder="Enter the product's stock" step="1" required>
                    </p>
                    <input name="submit_add_product" id="submit_add_product" type="submit" value="Add" />
                </div>
            </form>
        </div>

        <!-- ##################################
            script Section 
    ####################################-->
        <script>
        // Define these functions in the global scope
        window.product_edit_button = function() {
            document.getElementById('product_edit_display').style.display = "block";
            document.getElementById('buttons_table_display').style.display = "none";
        };

        window.product_cancel_edit = function() {
            document.getElementById('product_edit_display').style.display = "none";
            document.getElementById('buttons_table_display').style.display = "block";
        };

        // Make sure the buttons have the correct onclick handlers after the page loads
        document.addEventListener('DOMContentLoaded', function() {
            if (document.getElementById('edit_product_button')) {
                document.getElementById('edit_product_button').onclick = window.product_edit_button;
            }

            if (document.getElementById('product_cancel_edit')) {
                document.getElementById('product_cancel_edit').onclick = window.product_cancel_edit;
            }
        });
        </script>
        <script src="../assets/js/root.js"></script> <!-- keep last in body so all html elemnts are loaded-->
</body>

</html>