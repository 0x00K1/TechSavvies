<div class="top-controls">
        <div class="search_div">
            <input class="search-field_style" name="product_search_field" id="search_field" type="text"
                placeholder="Search... attribute:key ex(name:ft7y)" />
            <button class="search-button_style" name="search_button" id="search_button" type="submit">
                Search
            </button>
            <span class="rows_label">Rows:</span>
            <select name="rows_per_page" id="rows_per_page" class="filter_value_style" onchange="changeRowsPerPage()">
                <option value="25">25</option>
                <option value="50">50</option>
                <option value="100" selected>100</option>
                <option value="250">250</option>
            </select>
        </div>
    </div>