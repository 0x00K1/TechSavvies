<div id="orders-display" class="content">
    <div class="top-controls">
        <div class="search-div">
            <input class="search-field-style" name="orders-search-field" id="orders-search-field" type="text"
                placeholder="Search... attribute:key ex(name:ft7y)" />
            <button class="search-button-style" name="search-button" id="orders-search-button" type="submit">
                Search
            </button>
            <span class="rows-label">Rows:</span>
            <select id="orders-rows-per-page">
                <option value="25">25</option>
                <option value="50">50</option>
                <option value="100">100</option>
                <option value="250">250</option>
            </select>
        </div>
    </div>
    <div class="table-container">
        <table id="orders-table">
            <thead>
                <tr>
                    <th data-sort="Order-ID">Order ID <span class="sort-icon">↕</span></th>
                    <th data-sort="Order-Date">Order Date<span class="sort-icon">↕</span></th>
                    <th data-sort="Product-ID">Product ID <span class="sort-icon">↕</span></th>
                    <th data-sort="Product-Name">Product Name<span class="sort-icon">↕</span></th>
                    <th data-sort="Quantity">Quantity <span class="sort-icon">↕</span></th>
                    <th data-sort="Price-per-Unit">Price per Unit<span class="sort-icon">↕</span></th>
                    <th data-sort="Total-Price">Total Price<span class="sort-icon">↕</span></th>

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
        <div class="orders-pagination-info" id="orders-pagination-info">
            Showing <span id="showing-start">1</span> to <span id="showing-end">10</span> of <span
                id="total-items">100</span> items
        </div>
        <div class="pagination-controls">
            <button type="button" id="orders-prev-page" class="pagination-button" disabled>&laquo; Previous</button>
            <span id="orders-current-page">1</span>
            <button type="button" id="orders-next-page" class="pagination-button">Next &raquo;</button>
        </div>
    </div>
</div>

