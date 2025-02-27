<div id="EditProduct" class="content">
    
    <form name="Product_queries" id="Product_queries" method="post" action="index.php">
        <div class="search_div">
            <input class="search-field" name="search_field" id="search_field" type="text" placeholder="Search" />
            <input class="search-button" name="search_button" id="search_button" type="submit" value="Search" />
            <button id="addProPopup_button" class="addProPopup_button_style">Add</button>
            <span>Filter:</span>
            <select name="filter_value" id="filter_value" class="filter_value_style" placeholder="Chose a value">
                <option value="">None</option>
                <option name="f_category" value="category">Category</option>
                <option name="f_price" value="price">Price</option>
                <option name="f_stock" value="stock">Stock</option>
                <option name="f_size" value="size">Size</option>
                <option name="f_update_date" value="update_date">update Date</option>
            </select>
            <button name="order_toggler" id="order_toggler">^</button>
            <!-- some crazy js for ico change-->
        </div>
        <div class="table_div">
            <table border="1">
                <thead class="table_header">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Price</th>
                        <th>Stock</th>
                        <th>Category</th>
                        <th>Updated Date</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>ID</td>
                        <td>Name</td>
                        <td>Price</td>
                        <td>Stock</td>
                        <td>Category</td>
                        <td>Updated Date</td>
                        <td><div id="buttons_table" class="buttons_table">
                            <button name="remove_product_button" id="remove_product_button">Remove</button>
                            <button name="edit_product_button" id="edit_product_button" class="edit_product_button">Edit</button></div></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <?php include('..\assets\php\root_php\confirmation.php');?> <!-- <- submit button-->
    </form>
</div>