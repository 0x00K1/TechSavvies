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
    <?php require_once __DIR__ . '\..\assets\php\main.php'; ?>
    <link rel="stylesheet" href="..\assets\css\main.css">
    <link rel="stylesheet" href="..\assets\css\root.css">
    <script type="module" src="..\assets\js\root.js"></script>
    <title>Root</title>
</head>

<body>
    <!-- Header section -->
    <?php include('..\assets\php\header.php'); ?>
    <div class="Boss">
        <!-- toolbar Section -->
        <div class="toolbar">
            <nav class="nav">
                <button id="manageProbutton"><img class="b-icon" src="..\assets\icons\Product.svg"><span>Products Management</span></button>
                <button id="manageUserbutton"><img class="b-icon" src="..\assets\icons\User.svg"><span>Users Management</span></button>
                <button id="Ordersbutton"><img class="b-icon" src="..\assets\icons\Orders.svg"><span>Orders</span></button>
                <button id="transactionsbutton"><img class="b-icon" src="..\assets\icons\transaction.svg"><span>Transactions</span></button>
                <button id="Reviewsbutton"><img class="b-icon" src="..\assets\icons\review.svg"><span>Reviews</span></button>
                <button id="Logoutbutton">
                    <svg class="b-icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g id="SVGRepo-bgCarrier" stroke-width="0"></g>
                        <g id="SVGRepo-tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                        <g id="SVGRepo-iconCarrier">
                            <path
                                d="M14 4L17.5 4C20.5577 4 20.5 8 20.5 12C20.5 16 20.5577 20 17.5 20H14M3 12L15 12M3 12L7 8M3 12L7 16"
                                stroke="#000000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                            </path>
                        </g>
                    </svg>
                    <span>Logout</span></button>
            </nav>
        </div>
        <!-- main area Section -->
        <div class="funcarea">
            <div class="content-container">
                <!-- Products page -->
                <div id="EditProduct" class="content">
                    <form name="Product-queries" id="Product-queries" method="post" action="index.php">
                        <button id="addProPopup-button" class="addProPopup_button_style" type="button"
                            onclick="addProPopup()">
                            <i class="fas fa-plus"></i> Add Product
                        </button>
                        <div class="top-controls">
                            <div class="search_div">
                                <input class="search-field_style" name="product-search-field" id="users-search-field" type="text" placeholder="Search... attribute:key ex(name:ft7y)" />
                                <button class="search-button_style" name="search-button" id="users-search-button" type="submit">Search</button>
                                <span>Rows:</span>
                                <select id="products-rows-per-page" class="filter_value_style">
                                    <option>Rows to display</option>
                                    <option value="4">4</option>
                                    <option value="25">25</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                    <option value="250">250</option>
                                </select>
                            </div>
                        </div>

                        <div class="table_container">
                            
                        </div>

                        <div class="pagination">
                            <div class="pagination-info">
                                Showing <span id="showing-start">1</span> to <span id="showing-end">10</span> of <span
                                    id="total-items">100</span> items
                            </div>
                            <div class="pagination-controls">
                                <button type="button" id="prev-page" class="pagination-button" disabled>&laquo;
                                    Previous</button>
                                <button type="button" id="next-page" class="pagination-button">Next &raquo;</button>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Confirmation Modal -->
                <div id="confirmation-modal" class="modal">
                    <div class="modal-content">
                        <div class="modal-title">Confirm Deletion</div>
                        <p>Are you sure you want to delete this product? This action cannot be undone.</p>
                        <div class="modal-actions">
                            <button type="button" class="modal-button modal-button-cancel"
                                onclick="closeConfirmationModal()">Cancel</button>
                            <button type="button" class="modal-button modal-button-confirm"
                                onclick="confirmDelete()">Delete</button>
                        </div>
                    </div>
                </div>


                <!-- -----------------------------------------
                    users page
                    ------------------------------------------>
                <div id="usersdisplay" class="content">
                    <div class="top-controls">
                        <div class="search_div">
                            <input class="search-field_style" name="product-search-field" id="users-search-field"
                                type="text" placeholder="Search... attribute:key ex(name:ft7y)" />
                            <button class="search-button_style" name="search-button" id="users-search-button"
                                type="submit">
                                Search
                            </button>
                            <span class="rows-label">Rows:</span>

                            <select id="users-rows-per-page" class="filter_value_style">
                                <option>Rows to display</option>
                                <option value="4">4</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                                <option value="250">250</option>
                            </select>
                        </div>
                    </div>
                    <div class="table_container">
                        <!-- TABLE AREA -->
                    </div>

                    <div class="users-pagination" id="users-pagination">
                        <div class="pagination-info" id="users-pagination-info">
                            Showing <span id="users-showing-start">1</span> to <span id="users-showing-end">10</span> of
                            <span id="users-total-items">100</span> items
                        </div>
                        <div class="pagination-controls" id="pagination-controls">
                            <button type="button" id="users-prev-page" class="pagination-button" disabled>&laquo;
                                Previous</button>
                            <span id="users-current-page">1</span>
                            <button type="button" id="users-next-page" class="pagination-button">Next &raquo;</button>
                        </div>
                    </div>
                </div>
                <!-- -----------------------------------------
                    orders page
                    ------------------------------------------>

                <div id="ordersdisplay" class="content">
                    <div class="top-controls">
                        <div class="search_div">
                            <input class="search-field_style" name="orders-search-field" id="orders-search-field"
                                type="text" placeholder="Search... attribute:key ex(name:ft7y)" />
                            <button class="search-button_style" name="search-button" id="orders-search-button"
                                type="submit">
                                Search
                            </button>
                            <span>Rows:</span>
                            <select id="orders-rows-per-page" class="filter_value_style">
                                <option>Rows to display</option>
                                <option value="4">4</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                                <option value="250">250</option>
                            </select>
                        </div>
                    </div>
                    <div class="table_container">
                        <!-- TABLE AREA -->
                    </div>

                    <div class="pagination">
                        <!-- keep here or refrence as a single php file?-->
                        <div class="orders-pagination-info" id="orders-pagination-info">
                            Showing <span id="showing-start">1</span> to <span id="showing-end">10</span> of <span
                                id="total-items">100</span> items
                        </div>
                        <div class="pagination-controls">
                            <button type="button" id="orders-prev-page" class="pagination-button" disabled>&laquo;
                                Previous</button>
                            <span id="orders-current-page">1</span>
                            <button type="button" id="orders-next-page" class="pagination-button">Next &raquo;</button>
                        </div>
                    </div>
                </div>

                <!-- -----------------------------------------
                    transactions page
                    ------------------------------------------>
                <div class="content" id="Transactionsdisplay">
                    <!-- <?php include("search-rows.php") ?> -->
                    
                    
                    <div class="table_container">
                        <!-- TABLE AREA -->
                    </div>

                    <div class="pagination"> <!-- keep here or refrence as a single php file?-->
                        <div class="pagination-info"> Showing <span id="showing-start">1</span> to <span id="showing-end">10</span> of <span id="total-items">100</span> items
                        </div>
                        <div class="pagination-controls">
                            <button type="button" id="prev-page" class="pagination-button" disabled>&laquo;Previous</button>
                            <button type="button" id="next-page" class="pagination-button">Next &raquo;</button>
                        </div>
                    </div>
                </div>


                <!-- -----------------------------------------
                    reviews page
                    ------------------------------------------>
                <div class="content" id="Reviewsdisplay">
                    <!-- <?php include("search-rows.php") ?> -->
                    
                    <div class="table_container">
                        <!-- TABLE AREA -->
                    </div>

                    <div class="pagination">
                        <!-- keep here or refrence as a single php file?-->
                        <div class="pagination-info">
                            Showing <span id="showing-start">1</span> to <span id="showing-end">10</span> of <span
                                id="total-items">100</span> items
                        </div>
                        <div class="pagination-controls">
                            <button type="button" id="prev-page" class="pagination-button" disabled>&laquo;
                                Previous</button>
                            <button type="button" id="next-page" class="pagination-button">Next &raquo;</button>
                        </div>
                    </div>
                </div>
                <!-- ##################################
            popup-forms Section 
    ####################################-->
                <div id="addProPopupdisplay" class="addProPopup">
                    <span class="close" onclick="closeaddProPopup()">&times;</span>
                    <form name="add-product-form" id="addProductform" method="post" action="index.php">
                        <div id="add-display" class="AddProduct">
                            <div style="display: inline;">
                                <label for="product-name">Product Name:</label>
                                <input type="text" name="product-name" id="productname"
                                    placeholder="Enter the product's name" maxlength="255" required>

                                <label for="categoryList">Category:</label>
                                <input id="categoryList" type="text" name="Pro-category"
                                    placeholder="Choose a category..." list="Pro-category">
                                <datalist id="Procategory">
                                    <option value="test1"></option>
                                    <option value="test2"></option>
                                </datalist>

                                <div id="imageContainer">
                                    <label for="imageUpload">Upload Image:</label>
                                    <input id="imageUpload" type="file" name="image" accept="image/*">


                                    <label for="ProductDescreption">Product Description:</label>
                                    <textarea id="ProductDescreption" placeholder="Product description"
                                        naem="ProductDescreption" required></textarea>

                                    <label for="product-color">Product Color:</label>
                                    <input type="text" name="product-color" id="product-color"
                                        placeholder="Enter the product's color" maxlength="255" required>
                                </div>
                                <div style="display: inline;">
                                    <label for="product-size">Product Size:</label>
                                    <input type="text" name="product-size" id="product-size"
                                        placeholder="Enter the product's size" maxlength="50" required>


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
            </div>
        </div>
</body>

</html>