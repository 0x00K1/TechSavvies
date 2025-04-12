<div class="content" id="Reviews-Display">
   <!-- <?php include("search-rows.php")?> -->
    <div class="table-container">
        <table id="transactions-table">
            <thead>
                <tr>
                    <th data-sort="Review-ID">Review ID <span class="sort-icon">↕</span></th>
                    <th data-sort="User-ID">User ID <span class="sort-icon">↕</span></th>
                    <th data-sort="Username">Username <span class="sort-icon">↕</span></th>
                    <th data-sort="Product-ID">Product ID <span class="sort-icon">↕</span></th>
                    <th data-sort="Product-Name">Product Name <span class="sort-icon">↕</span></th>
                    <th data-sort="Rating">Rating<span class="sort-icon">↕</span></th>
                    <th data-sort="Review-Text">Review Text <span class="sort-icon">↕</span></th>
                    <th data-sort="Created-At">Created at <span class="sort-icon">↕</span></th>
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