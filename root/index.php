<?php require_once __DIR__ . '/../includes/root_guard.php'; ?>
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
                <button id="manageRootButton">
                    <svg fill="#000000" class="b-icon" viewBox="-2.4 -2.4 28.80 28.80" role="img" xmlns="http://www.w3.org/2000/svg" transform="rotate(0)">
                        <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                        <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round" stroke="#CCCCCC" stroke-width="0.048"></g>
                        <g id="SVGRepo_iconCarrier">
                            <path d="M10.857 14.458s.155.921-.034 2.952c-.236 2.546.97 6.59.97 6.59s1.645-4.052 1.358-6.67c-.236-2.152.107-2.904.034-2.803-1.264 1.746-2.328-.069-2.328-.069zm3.082 2.185c.206 1.591-.023 2.462-.32 4.164-.15.861 3.068-2.589 4.302-4.645.206-.343-1.18 1.337-2.55.137-.952-.832-1.115-1.085-1.854-1.808-.249-.244.277 1.014.423 2.151zm-3.512-2.025c-.739.723-.903.976-1.853 1.808-1.371 1.2-2.757-.48-2.551-.137 1.234 2.057 4.452 5.506 4.302 4.645-.297-1.703-.526-2.574-.32-4.164.147-1.137.673-2.395.423-2.15zm3.166-2.839c1.504.434 2.088 2.523 3.606 2.781.314.053.667.148 1.08.128.77-.037 1.743-.472 3.044-2.318.385-.546-.955 3.514-4.313 3.563-2.46.036-2.747-2.408-4.387-2.482-.592-.027-.629-1.156-.629-1.156s.706-.774 1.598-.517zm-3.186-.012c-1.504.434-2.088 2.523-3.606 2.781-.314.053-.667.148-1.08.128-.77-.037-1.743-.472-3.044-2.318-.385-.546.955 3.514 4.313 3.563 2.46.036 2.747-2.408 4.387-2.482.592-.027.629-1.156.629-1.156s-.706-.774-1.598-.517zm5.626-.02c1.513 1.146 1.062 2.408 1.911 2.048 2.86-1.212 2.36-7.434 2.128-6.682-1.303 4.242-4.143 4.48-6.876 2.528-.534-.38 1.985 1.46 2.837 2.105zm-5.24-2.106C8.06 11.592 5.22 11.355 3.917 7.113c-.231-.752-.731 5.47 2.128 6.682.849.36.398-.902 1.91-2.048.853-.646 3.372-2.486 2.838-2.105zm5.526.584c3.3-.136 3.91-5.545 3.65-4.885-1.165 2.963-5.574 1.848-5.995 3.718-.083.367.747 1.233 2.345 1.167zm-6.304-1.167c-.421-1.87-4.831-.755-5.995-3.718-.26-.66.35 4.75 3.65 4.885 1.599.066 2.428-.8 2.345-1.167zm3.753-.824s1.794-.964 3.33-1.384c1.435-.393 2.512-1.359 2.631-2.38.09-.76-1.11-2.197-1.11-2.197s-.84 2.334-1.945 3.501c-1.2 1.27-.745 1.1-2.906 2.46zm-6.453-2.46c-1.104-1.167-1.945-3.5-1.945-3.5S4.17 3.708 4.26 4.47c.12 1.021 1.196 1.987 2.63 2.38 1.537.421 3.331 1.384 3.331 1.384-2.162-1.36-1.705-1.19-2.906-2.46zm6.235 2.312c1.943-1.594 2.976-3.673 4.657-5.949.317-.429-1.419-1.465-2.105-1.533-.686-.068-1.262 2.453-1.327 3.936-.059 1.354-1.486 3.761-1.224 3.547z"></path>
                        </g>
                    </svg>
                    <span>Roots</span></button>
                <button id="manageProButton"><img class="b-icon" src="..\assets\icons\Product.svg">
                    <span>Products</span></button>
                <button id="manageUserButton"><img class="b-icon" src="..\assets\icons\User.svg">
                    <span>Users</span></button>
                <button id="OrdersButton"><img class="b-icon" src="..\assets\icons\Orders.svg">
                        <span>Orders</span></button>
                <button id="transactionsButton"><img class="b-icon" src="..\assets\icons\transaction.svg">
                        <span>Transactions</span></button>
                <button id="ReviewsButton"><img class="b-icon" src="..\assets\icons\review.svg">
                        <span>Reviews</span></button>
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
                <!-- Roots page -->
                <div id="rootsDisplay" class="content" style="display:none">
                <div class="top-controls">
                    <div class="search_div">
                    <input
                        class="search-field_style"
                        id="rootsSearchField"
                        type="text"
                        placeholder="Search . . "
                    />
                    <button
                        type="button"
                        class="search-button_style"
                        id="rootsSearchButton"
                    >Search</button>
                    <span>Rows:</span>
                    <select id="rootsRowsPerPage" class="filter_value_style">
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                        <option value="250">250</option>
                    </select>
                    </div>
                </div>
                <div class="table_container" id="rootsTableDisplay"></div>
                <div class="pagination">
                    <div class="pagination-info" id="rootsPaginationInfo"></div>
                    <div class="pagination-controls">
                    <button id="rootsPrevPage" class="pagination-button" disabled>&laquo;Prev</button>
                    <span id="rootsCurrentPage">1</span>
                    <button id="rootsNextPage" class="pagination-button">Next &raquo;</button>
                    </div>
                </div>
                </div>
                <!-- Products page -->
                <div id="editProduct" class="content">
                    <button id="addProPopupButton" class="addProPopup_button_style" type="button">
                        <i class="fas fa-plus"></i> Add Product
                    </button>
                    <div class="top-controls">
                        <div class="search_div">
                            <label for="productsCategoryFilter">Category:</label>
                            <select id="productsCategoryFilter" class="filter_value_style">
                            <option value="">All</option>
                            </select>
                            <input class="search-field_style" name="productsSearchFieldName" id="productsSearchField"
                                type="text" placeholder="Search . . " />
                            <button type="button" class="search-button_style" name="productsSearchButtonName"
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
                        <p>Are you sure you want to delete this ..? This action cannot be undone.</p>
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
                                type="text" placeholder="Search . . " />
                            <button type="button" class="search-button_style" name="usersSearchButtonName" id="usersSearchButton"
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
                                type="text" placeholder="Search . . " />
                            <button type="button" class="search-button_style" name="ordersSearchButtonName" id="ordersSearchButton"
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
                                type="text" placeholder="Search . . " />
                            <button type="button" class="search-button_style" name="transactionsSearchButtonName" id="transactionsSearchButton"
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
                                type="text" placeholder="Search . . " />
                            <button type="button" class="search-button_style" name="reviewsSearchButtonName" id="reviewsSearchButton"
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
                    <form name="addProductFormName" id="addProductForm" method="post" enctype="multipart/form-data">
                        <div id="addDisplay" class="AddProduct">
                            <div style="display: inline;">
                                <label for="productName">Product Name:</label>
                                <input type="text" name="product_name" id="productName"
                                    placeholder="Enter the product's name" maxlength="255" required>
                                <label for="addProductCategory">Category:</label>
                                <select id="addProductCategory" name="category_id" required>
                                <option value="">–– Select a category ––</option>
                                </select>
                                <div id="imageContainer">
                                    <label for="imageUpload">Upload Image:</label>
                                    <input id="imageUpload" type="file" name="imageName" accept="image/*">


                                    <label for="ProductDescreption">Product Description:</label>
                                    <textarea id="ProductDescription" placeholder="Product description"
                                        name="description" required></textarea>

                                    <label for="productColor">Product Color:<span id="NR">(Not Required)</span></label>
                                    <input type="text" name="color" id="productColor"
                                        placeholder="Enter the product's color" maxlength="255">
                                </div>
                                <div style="display: inline;">
                                    <label for="productSize">Product Size:<span id="NR">(Not Required)</span></label>
                                    <input type="text" name="size" id="productSize"
                                        placeholder="Enter the product's size" maxlength="50">


                                    <label for="productPrice">Product Price:</label>
                                    <input type="number" name="price" id="productPrice"
                                        placeholder="Enter the product's price" step="0.01" required>


                                    <label for="productStock">Product Stock:</label>
                                    <input type="number" name="stock" id="productStock"
                                        placeholder="Enter the product's stock" min=0 step="1" required>
                                </div>
                                <input name="submitAddProductName" id="submitAddProduct" type="submit" value="Add Product" />
                            </div>
                    </form>
                </div><!-- add product-->
            </div><!-- content container -->
        </div><!-- funcarea -->
    </div> <!-- boss-->
</div>
<script> window.currentRootId = <?= json_encode($_SESSION['root_id']); ?>;</script>
</body>
</html>