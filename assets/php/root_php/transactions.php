<div class="content" id="Transactions_display">
   <?php include("../assets/php/root_php/search_rows.php")?>
    <div class="table-container">
        <table id="transactions-table">
            <thead>
                <tr>
                    <th data-sort="Payment_ID">Payment ID <span class="sort-icon">↕</span></th>
                    <th data-sort="Order_ID">Order ID <span class="sort-icon">↕</span></th>
                    <th data-sort="User_ID">User ID <span class="sort-icon">↕</span></th>
                    <th data-sort="Username">Username <span class="sort-icon">↕</span></th>
                    <th data-sort="Payment_Method">Payment Method <span class="sort-icon">↕</span></th>
                    <th data-sort="Payment_Status">Payment Status <span class="sort-icon">↕</span></th>
                    <th data-sort="Transaction_ID">Transaction ID<span class="sort-icon">↕</span></th>
                    <th data-sort="Amount">Amount <span class="sort-icon">↕</span></th>
                    <th data-sort="Created_At">Created at <span class="sort-icon">↕</span></th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="transactions-table-body">
                <!-- Table rows will be added dynamically via JavaScript -->
            </tbody>
        </table>
    </div>

    <div class="pagination">  <!-- keep here or refrence as a single php file?-->
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
const sampleProducts = [{
        Payment_ID: 1,
        Order_ID: 3,
        User_ID: 2,
        Username: "Afnan_3xx",
        Payment_Method: "credit card",
        Payment_Status: "Complete",
        Transaction_ID: 12,
        Amount: 12.75,
        Created_At: "2025-02-27"
    }]
/* call initilize function*/
</script>