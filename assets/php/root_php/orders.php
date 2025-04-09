<div id="orders_display" class="content">
    <div class="top-controls">
        <div class="search_div">
            <input class="search-field_style" name="orders_search_field" id="orders_search_field" type="text"
                placeholder="Search... attribute:key ex(name:ft7y)" />
            <button class="search-button_style" name="search_button" id="orders_search_button" type="submit">
                Search
            </button>
            <span class="rows_label">Rows:</span>
            <input type="number" name="rows_per_page" id="orders_rows_per_page" class="filter_value_style"
                onchange="changeRowsPerPage('orders')" list="rowsPerPageOptions">
            <datalist id="rowsPerPageOptions">
                <option value="25">25</option>
                <option value="50">50</option>
                <option value="100">100</option>
                <option value="250">250</option>
            </datalist>
        </div>
    </div>
    <div class="table-container">
        <table id="orders-table">
            <thead>
                <tr>
                    <th data-sort="Order_ID">Order ID <span class="sort-icon">↕</span></th>
                    <th data-sort="Order_Date">Order Date<span class="sort-icon">↕</span></th>
                    <th data-sort="Product_ID">Product ID <span class="sort-icon">↕</span></th>
                    <th data-sort="Product_Name">Product Name<span class="sort-icon">↕</span></th>
                    <th data-sort="Quantity">Quantity <span class="sort-icon">↕</span></th>
                    <th data-sort="Price_per_Unit">Price per Unit<span class="sort-icon">↕</span></th>
                    <th data-sort="Total_Price">Total Price<span class="sort-icon">↕</span></th>

                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="orders-table-body">
                <!-- Table rows will be added dynamically via JavaScript -->
            </tbody>
        </table>
    </div>

    <div class="pagination">
        <!-- keep here or refrence as a single php file?-->
        <div class="pagination-info">
            Showing <span id="showing-start">1</span> to <span id="showing-end">10</span> of <span
                id="total-items">100</span> items
        </div>
        <div class="pagination-controls">
            <button type="button" id="prev-page" class="pagination-button" disabled>&laquo; Previous</button>
            <button type="button" id="next-page" class="pagination-button">Next &raquo;</button>
        </div>
    </div>
</div>

