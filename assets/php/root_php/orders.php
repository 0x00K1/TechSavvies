<div id="orders_display" class="content">
    <h2 class="page-title">Orders</h2>
    <?php include("search_rows.php")?>
    <div class="table-container">
    <table id="products-table">
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
        <tbody id="transactions-table-body">
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
