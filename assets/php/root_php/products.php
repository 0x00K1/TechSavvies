<div id="EditProduct" class="content">
        <form name="Product_queries" id="Product_queries" method="post" action="index.php">
                <button id="addProPopup_button" class="addProPopup_button_style" type="button" onclick="addProPopup()">
                    <i class="fas fa-plus"></i> Add Product
                </button>
                <div class="top-controls">
            <div class="search_div">
                <input class="search-field_style" name="product_search_field" id="users_search_field" type="text"
                    placeholder="Search... attribute:key ex(name:ft7y)" />
                <button class="search-button_style" name="search_button" id="users_search_button" type="submit">
                    Search
                </button>
                <span class="rows_label">Rows:</span>
                <input type="number" name="rows_per_page" id="users_rows_per_page" class="filter_value_style" onchange="changeRowsPerPage('users')" list="rowsPerPageOptions">
                <datalist id="rowsPerPageOptions">
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                    <option value="250">250</option>
                </datalist>
            </div>
    </div>
            
            <div class="table-container">
                <table id="products-table">
                    <thead>
                        <tr>
                            <th data-sort="id">ID <span class="sort-icon">↕</span></th>
                            <th data-sort="name">Name <span class="sort-icon">↕</span></th>
                            <th data-sort="price">Price <span class="sort-icon">↕</span></th>
                            <th data-sort="stock">Stock <span class="sort-icon">↕</span></th>
                            <th data-sort="category">Category <span class="sort-icon">↕</span></th>
                            <th data-sort="updated_date">Updated Date <span class="sort-icon">↕</span></th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="products-table-body">
                        <!-- Table rows will be added dynamically via JavaScript -->
                    </tbody>
                </table>
            </div>
            
            <div class="pagination">
                <div class="pagination-info">
                    Showing <span id="showing-start">1</span> to <span id="showing-end">10</span> of <span id="total-items">100</span> items
                </div>
                <div class="pagination-controls">
                    <button type="button" id="prev-page" class="pagination-button" disabled>&laquo; Previous</button>
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
                <button type="button" class="modal-button modal-button-cancel" onclick="closeConfirmationModal()">Cancel</button>
                <button type="button" class="modal-button modal-button-confirm" onclick="confirmDelete()">Delete</button>
            </div>
        </div>
    </div>

    