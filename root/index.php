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
    <?php require_once __DIR__ . '\..\includes\db.php'; ?>
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
                <button id="manageProButton"><img class="b-icon" src="..\assets\icons\Product.svg"><span>Products
                        Management</span></button>
                <button id="manageUserButton"><img class="b-icon" src="..\assets\icons\User.svg"><span>Users
                        Management</span></button>
                <button id="OrdersButton"><img class="b-icon"
                        src="..\assets\icons\Orders.svg"><span>Orders</span></button>
                <button id="transactionsButton"><img class="b-icon"
                        src="..\assets\icons\transaction.svg"><span>Transactions</span></button>
                <button id="ReviewsButton"><img class="b-icon"
                        src="..\assets\icons\review.svg"><span>Reviews</span></button>
                <button id="LogoutButton">
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
                <div id="editProduct" class="content">
                    <button id="addProPopupButton" class="addProPopup_button_style" type="button">
                        <i class="fas fa-plus"></i> Add Product
                    </button>
                    <div class="top-controls">
                        <div class="search_div">
                            <input class="search-field_style" name="productsSearchFieldName" id="productsSearchField"
                                type="text" placeholder="Search... attribute:key ex(name:ft7y)" />
                            <button class="search-button_style" name="productsSearchButtonName"
                                id="productsSearchButton">Search</button>
                            <span>Rows:</span>
                            <select id="productsRowsPerPage" class="filter_value_style">
                                <option value="10">10</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                                <option value="250">250</option>
                            </select>
                        </div>
                    </div>

                    <div class="table_container" id="productsTableDisplay">
                        <!-- table generated from js renderTable() -->
                    </div>
                    <div class="pagination">
                        <div class="pagination-info" id="productsPaginationInfo">
                            <!-- added from js updatePaginationControl()-->
                        </div>
                        <div class="pagination-controls">
                            <button type="button" id="productsPrevPage" class="pagination-button"
                                disabled>&laquo;Previous</button>
                            <span id="productsCurrentPage">1</span>
                            <button type="button" id="productsNextPage" class="pagination-button">Next
                                &raquo;</button>
                        </div>
                    </div><!-- pagination-->
                </div><!-- edit product -->

                <!-- Confirmation Modal  -->
                <div id="confirmationModal" class="modal">
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
                </div><!-- confirmation mdale  -->


                <!-- -----------------------------------------
                    users page
                    ------------------------------------------>
                <div id="usersDisplay" class="content">
                    <div class="top-controls">
                        <div class="search_div">
                            <input class="search-field_style" name="usersSearchFieldName" id="usersSearchField"
                                type="text" placeholder="Search... attribute:key ex(name:ft7y)" />
                            <button class="search-button_style" name="usersSearchButtonName" id="usersSearchButton"
                                type="submit">
                                Search
                            </button>
                            <span class="rows-label">Rows:</span>

                            <select id="usersRowsPerPage" class="filter_value_style">
                                <option value="10">10</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                                <option value="250">250</option>
                            </select>
                        </div>
                    </div>
                    <div class="table_container" id="usersTableDisplay">
                        <!-- table generated from js renderTable() -->
                    </div>

                    <div class="users-pagination" id="usersPagination">
                        <div class="pagination-info" id="usersPaginationInfo">
                            <!-- added from js updatePaginationControl()-->
                        </div>
                        <div class="pagination-controls" id="paginationControls">
                            <button type="button" id="usersPrevPage" class="pagination-button"
                                disabled>&laquo;Previous</button>
                            <span id="usersCurrentPage">1</span>
                            <button type="button" id="usersNextPage" class="pagination-button">Next &raquo;</button>
                        </div>
                    </div>
                </div><!-- users div-->
                <!-- -----------------------------------------
                    orders page
                    ------------------------------------------>

                <div id="ordersDisplay" class="content">

                    <div class="top-controls">
                        <div class="search_div">
                            <input class="search-field_style" name="ordersSearchFieldName" id="ordersSearchField"
                                type="text" placeholder="Search... attribute:key ex(name:ft7y)" />
                            <button class="search-button_style" name="ordersSearchButtonName" id="ordersSearchButton"
                                type="submit">Search</button>
                            <span>Rows:</span>
                            <select id="ordersRowsPerPage" class="filter_value_style">
                                <option value="10">10</option>  
                                <option value="25">25</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                                <option value="250">250</option>
                            </select>
                        </div>
                    </div>
                    <div class="table_container" id="ordersTableDisplay">
                        <!-- table generated from js renderTable() -->
                    </div>

                    <div class="users-pagination" id="ordersPagination">
                        <div class="pagination-info" id="ordersPaginationInfo">
                            <!-- added from js updatePaginationControl()-->
                        </div>
                        <div class="pagination-controls" id="paginationControls">
                            <button type="button" id="ordersPrevPage" class="pagination-button"
                                disabled>&laquo;Previous</button>
                            <span id="ordersCurrentPage">1</span>
                            <button type="button" id="ordersNextPage" class="pagination-button">Next &raquo;</button>
                        </div>
                    </div>
                </div><!-- orders div-->

                <!-- -----------------------------------------
                    transactions page
                    ------------------------------------------>
                <div id="transactionsDisplay" class="content">
                    <div class="top-controls">
                        <div class="search_div">
                            <input class="search-field_style" name="transactionsSearchFieldName" id="transactionsSearchField"
                                type="text" placeholder="Search... attribute:key ex(name:ft7y)" />
                            <button class="search-button_style" name="transactionsSearchButtonName" id="transactionsSearchButton"
                                type="submit">Search</button>
                            <span>Rows:</span>
                            <select id="transactionsRowsPerPage" class="filter_value_style">
                                <option value="10">10</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                                <option value="250">250</option>
                            </select>
                        </div>
                    </div>
                    <div class="table_container" id="transactionsTableDisplay">
                        <!-- table generated from js renderTable() -->
                    </div>

                    <div class="users-pagination" id="transactionsPagination">
                        <div class="pagination-info" id="transactionsPaginationInfo">
                            <!-- added from js updatePaginationControl()-->
                        </div>
                        <div class="pagination-controls" id="paginationControls">
                            <button type="button" id="transactionsPrevPage" class="pagination-button"
                                disabled>&laquo;Previous</button>
                            <span id="transactionsCurrentPage">1</span>
                            <button type="button" id="transactionsNextPage" class="pagination-button">Next &raquo;</button>
                        </div>
                    </div>
                </div><!-- transactions div-->


                <!-- -----------------------------------------
                    reviews page
                    ------------------------------------------>
                    <div id="reviewsDisplay" class="content">
                    <div class="top-controls">
                        <div class="search_div">
                            <input class="search-field_style" name="reviewsSearchFieldName" id="reviewsSearchField"
                                type="text" placeholder="Search... attribute:key ex(name:ft7y)" />
                            <button class="search-button_style" name="reviewsSearchButtonName" id="reviewsSearchButton"
                                type="submit">Search</button>
                            <span>Rows:</span>
                            <select id="reviewsRowsPerPage" class="filter_value_style">
                                <option value="10">10</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                                <option value="250">250</option>
                            </select>
                        </div>
                    </div>
                    <div class="table_container" id="reviewsTableDisplay">
                        <!-- table generated from js renderTable() -->
                    </div>

                    <div class="users-pagination" id="reviewsPagination">
                        <div class="pagination-info" id="reviewsPaginationInfo">
                            <!-- added from js updatePaginationControl()-->
                        </div>
                        <div class="pagination-controls" id="paginationControls">
                            <button type="button" id="reviewsPrevPage" class="pagination-button"
                                disabled>&laquo;Previous</button>
                            <span id="reviewsCurrentPage">1</span>
                            <button type="button" id="reviewsNextPage" class="pagination-button">Next &raquo;</button>
                        </div>
                    </div>
                </div><!-- reviews div-->
                <!-- ##################################
                popup-forms Section 
                ####################################-->
                <div id="addProPopupDisplay" class="addProPopup">
                    <span class="close" id="closeProductPopUpButton">&times;</span>
                    <form name="addProductFormName" id="addProductForm" method="post" action="/assets/php/addproduct.php" enctype="multipart/form-data">
                        <!-- notes: i added "Name" at the end of each name attribute to make it diffrent from id to avoid browser warnings use any naming you find suits u best -->
                        <div id="addDisplay" class="AddProduct">
                            <div style="display: inline;">
                                <label for="productName">Product Name:</label>
                                <input type="text" name="productNameName" id="productName"
                                    placeholder="Enter the product's name" maxlength="255" required>

                                <label for="categoryList">Category:</label>
                                <input id="categoryList" type="text" name="ProCategoryName"
                                    placeholder="Choose a category..." list="Pro-category">
                                <datalist id="Pro-category">
                                <<?php
try {
    $stmt = $pdo->query('SELECT category_name FROM categories');
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo '<option value="' . htmlspecialchars($row['category_name']) . '">';
    }
} catch (PDOException $e) {
    echo 'Error: ' . $e->getMessage();
}
?>
                                </datalist>

                                <div id="imageContainer">
                                    <label for="imageUpload">Upload Image:</label>
                                    <input id="imageUpload" type="file" name="imageName" accept="image/*">


                                    <label for="ProductDescreption">Product Description:</label>
                                    <textarea id="ProductDescreption" placeholder="Product description"
                                        name="ProductDescreptionName" required></textarea>

                                    <label for="productColor">Product Color:</label>
                                    <input type="text" name="productColorName" id="productColor"
                                        placeholder="Enter the product's color" maxlength="255" required>
                                </div>
                                <div style="display: inline;">
                                    <label for="productSize">Product Size:</label>
                                    <input type="text" name="productSizeName" id="productSize"
                                        placeholder="Enter the product's size" maxlength="50" required>


                                    <label for="productPrice">Product Price:</label>
                                    <input type="number" name="productPriceName" id="productPrice"
                                        placeholder="Enter the product's price" step="0.01" required>


                                    <label for="productStock">Product Stock:</label>
                                    <input type="number" name="productStockName" id="productStock"
                                        placeholder="Enter the product's stock" step="1" required>
                                </div>
                                <input name="submitAddProductName" id="submitAddProduct" type="submit" value="Add" />
                            </div>
                    </form>
                </div><!-- add product-->
            </div><!-- content container -->
        </div><!-- funcarea -->
    </div> <!-- boss-->

    <!-- misght be usless confirmational.php
        <div name="confirmationPopup-display" id="confirmationPopup-display" class="confirmationPopup" style="display:none;">
        <span class="close" onclick="closeconfirmationPopup()">&times;</span> 
        <input name="confirm-input" id="confirm-input" type="submit" value="Confirm"/>
    -->
</div>
</body>
<script src="assets/js/add_product.js"></script>
</html>