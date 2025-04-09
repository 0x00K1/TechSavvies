<div id="users_display" class="content">
    <div class="top-controls">
            <div class="search_div">
                <input class="search-field_style" name="product_search_field" id="users_search_field" type="text"
                    placeholder="Search... attribute:key ex(name:ft7y)" />
                <button class="search-button_style" name="search_button" id="users_search_button" type="submit">
                    Search
                </button>
                <span class="rows_label">Rows:</span>
                <input type="number" name="rows_per_page" id="users_rows_per_page" class="filter_value_style"  list="rowsPerPageOptions">
                <datalist id="rowsPerPageOptions">
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                    <option value="250">250</option>
                </datalist>
            </div>
    </div>
    <div class="table-container">
    <table id="users-table">
        <thead>
            <tr><th data-sort="customer_id">customer_id <span class="sort-icon">↕</span></th>
                <th data-sort="email">email<span class="sort-icon">↕</span></th>
                <th data-sort="username">username<span class="sort-icon">↕</span></th>
                <th data-sort="created_at">Created at<span class="sort-icon">↕</span></th>

                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="users-table-body">
            <!-- Table rows will be added dynamically via JavaScript -->
        </tbody>
        </table>
    </div>

    <div class="users-pagination" id="users-pagination">
        <div class="pagination-info" id="pagination-info">
            Showing <span id="showing-start">1</span> to <span id="showing-end">10</span> of <span
                id="total-items">100</span> items
        </div>
        <div class="pagination-controls" id="pagination-controls">
            <button type="button" id="prev-page" class="pagination-button" disabled>&laquo; Previous</button>
            <button type="button" id="next-page" class="pagination-button">Next &raquo;</button>
        </div>
    </div>
</div>
