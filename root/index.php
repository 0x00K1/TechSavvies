<!-- devolped by @ananas0dev , @HameedJHD
 notes for improvment
 the popup windows should make the full webpage disabled
 fix the edit button and make a textfield for the editable sections-->

<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="icon" href="..\assets\icons\Logo.ico">
    <link rel="stylesheet" href="..\assets\css\main.css">
    <link rel="stylesheet" href="..\assets\css\root.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
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
                            <input class="search-field_style" name="product_search_field" id="search_field" type="text"
                                placeholder="Search.. attribute:key ex(name:mustafa)" />
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
                                        <tr>
                                            <td>ID</td>
                                            <td>Name</td>
                                            <td>Price</td>
                                            <td>Stock</td>
                                            <td>Category</td>
                                            <td>Updated Date</td>
                                            <td>
                                                <div id="buttons_table_display" class="buttons_table_style"
                                                    style="display:block;">
                                                    <button name="remove_product_button" id="remove_product_button"
                                                        class="remove_product_button_style" type="button"
                                                        onclick="confirmationPopup()">Remove</button>
                                                    <button name="edit_product_button" id="edit_product_button"
                                                        class="edit_product_button_style" type="button"
                                                        onclick="product_edit_button()">Edit</button>
                                                </div>
                                                <div id="product_edit_display" class="product_edit_display_style"
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
                <!-- _________________________________________
                    orders page
                    ________________________________________-->

                <div id="orders_display" class="content">
                    <div class="search_div">
                        <input class="search-field_style" name="product_search_field" id="search_field" type="text"
                            placeholder="Search.. attribute:key ex(name:mustafa)" />
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
                            <thead>
                                <tr>
                                    <th>Order ID</th>
                                    <th>Order Date</th>
                                    <th>Product ID</th>
                                    <th>Product Name</th>
                                    <th>Quantity</th>
                                    <th>Price per Unit</th>
                                    <th>Total Price</th>
                                </tr>
                            </thead>
                            <tbody>

                                <!-- 
                COMMENT FOR DATABSE FILL (REMOVE FROM LINE 17 AND LINE 36 WHEN READY FOR IMPLEMENTING)
            <?php if ($orders): ?>
                    <?php foreach ($orders as $order): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($order['order_id']); ?></td>
                            <td><?php echo htmlspecialchars($order['order_date']); ?></td>
                            <td><?php echo htmlspecialchars($order['product_id']); ?></td>
                            <td><?php echo htmlspecialchars($order['product_name']); ?></td>
                            <td><?php echo htmlspecialchars($order['quantity']); ?></td>
                            <td><?php echo htmlspecialchars(number_format($order['price_per_unit'], 2)); ?></td>
                            <td><?php echo htmlspecialchars(number_format($order['quantity'] * $order['price_per_unit'], 2)); ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7">No recent orders found.</td>
                    </tr>
                <?php endif; ?> -->
                            </tbody>
                        </table>
                    </div>
                </div>
                <!--ADD PHP CODE FOR DATABASE FETCH-->


                <!-- _________________________________________
                    transactions page
                    ________________________________________-->
                <div class="content" id="Transactions_display">
                    <div class="search_div">
                        <input class="search-field_style" name="product_search_field" id="search_field" type="text"
                            placeholder="Search.. attribute:key ex(name:mustafa)" />
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
                            <thead>
                                <tr>
                                    <th>Payment ID</th>
                                    <th>Order ID</th>
                                    <th>User ID</th>
                                    <th>Username</th>
                                    <th>Payment Method</th>
                                    <th>Payment Status</th>
                                    <th>Transaction ID</th>
                                    <th>Amount</th>
                                    <th>Created At</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- COMMENT FOR DATABSE FILL (REMOVE FROM LINE 17 AND LINE 36 WHEN READY FOR IMPLEMENTING)
            <?php if ($transactions): ?>
                <?php foreach ($transactions as $transaction): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($transaction['payment_id']); ?></td>
                        <td><?php echo htmlspecialchars($transaction['order_id']); ?></td>
                        <td><?php echo htmlspecialchars($transaction['user_id']); ?></td>
                        <td><?php echo htmlspecialchars($transaction['username']); ?></td>
                        <td><?php echo htmlspecialchars($transaction['payment_method']); ?></td>
                        <td><?php echo htmlspecialchars($transaction['payment_status']); ?></td>
                        <td><?php echo htmlspecialchars($transaction['transaction_id']); ?></td>
                        <td><?php echo htmlspecialchars(number_format($transaction['amount'], 2)); ?></td>
                        <td><?php echo htmlspecialchars($transaction['created_at']); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="9">No recent transactions found.</td>
                </tr>
            <?php endif; ?> -->
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- _________________________________________
                    reviews page
                    ________________________________________-->
                <div class="div" id="Reviews_Display">
                    <div class="content">
                    <div class="search_div">
                        <input class="search-field_style" name="product_search_field" id="search_field" type="text"
                            placeholder="Search.. attribute:key ex(name:mustafa)" />
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
                        <table>
                            <thead>
                                <tr>
                                    <th>Review ID</th>
                                    <th>User ID</th>
                                    <th>Username</th>
                                    <th>Product ID</th>
                                    <th>Product Name</th>
                                    <th>Rating</th>
                                    <th>Review Text</th>
                                    <th>Created At</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- COMMENT FOR DATABSE FILL (REMOVE FROM LINE 17 AND LINE 36 WHEN READY FOR IMPLEMENTING)
                <?php if ($reviews): ?>
                    <?php foreach ($reviews as $review): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($review['review_id']); ?></td>
                            <td><?php echo htmlspecialchars($review['user_id']); ?></td>
                            <td><?php echo htmlspecialchars($review['username']); ?></td>
                            <td><?php echo htmlspecialchars($review['product_id']); ?></td>
                            <td><?php echo htmlspecialchars($review['product_name']); ?></td>
                            <td><?php echo htmlspecialchars($review['rating']); ?></td>
                            <td><?php echo htmlspecialchars($review['review_text']); ?></td>
                            <td><?php echo htmlspecialchars($review['created_at']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8">No recent reviews found.</td>
                    </tr>
                <?php endif; ?>
                -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
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