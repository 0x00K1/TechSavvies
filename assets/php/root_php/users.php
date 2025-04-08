<div id="users_display" class="content">
    <?php include("search_rows.php")?>
    <div class="table-container">
    <table id="users-table">
        <thead>
            <tr><th data-sort="role">Role <span class="sort-icon">↕</span></th>
                <th data-sort="user_ID">User ID <span class="sort-icon">↕</span></th>
                <th data-sort="user_name">User name<span class="sort-icon">↕</span></th>
                <th data-sort="country">Country<span class="sort-icon">↕</span></th>
                <th data-sort="created_at">Created at<span class="sort-icon">↕</span></th>

                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="users-table-body">
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


<script>
       
</script>