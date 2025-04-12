<div id="users-display" class="content">
    <div class="top-controls">
        <div class="search-div">
            <input class="search-field-style" name="product-search-field" id="users-search-field" type="text"
                placeholder="Search... attribute:key ex(name:ft7y)" />
            <button class="search-button-style" name="search-button" id="users-search-button" type="submit">
                Search
            </button>
            <span class="rows-label">Rows:</span>

            <select id="users-rows-per-page" class="filter-value-style">
                <option>Rows to display</option>
                <option value="4">4</option>
                <option value="25">25</option>
                <option value="50">50</option>
                <option value="100">100</option>
                <option value="250">250</option>
            </select>
        </div>
    </div>
    

    <div class="users-pagination" id="users-pagination">
        <div class="pagination-info" id="users-pagination-info">
            Showing <span id="users-showing-start">1</span> to <span id="users-showing-end">10</span> of <span
                id="users-total-items">100</span> items
        </div>
        <div class="pagination-controls" id="pagination-controls">
            <button type="button" id="users-prev-page" class="pagination-button" disabled>&laquo; Previous</button>
            <span id="users-current-page">1</span>
            <button type="button" id="users-next-page" class="pagination-button">Next &raquo;</button>
        </div>
    </div>
</div>