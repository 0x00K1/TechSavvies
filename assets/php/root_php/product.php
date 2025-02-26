<div id="EditProduct" class="content">
    <button id="addProPopup_button" class="addProPopup_button" onclick="addProPopup();">Add</button>
    <form name="Product_queries" method="post" action="index.php">
        <!-- or root_product.php-->
        <div class="search_div">
            <input class="search-field" name="search_field" id="search_field" type="text" placeholder="Search" />
            <input class="search-button" name="search_button" id="search_button" type="submit" value="Search" />
        </div>
        <button id="Delete_button" class="Deletebutton">Delete</button>
        <!--same button repeated for easy user experience
                        in the popup window ___> <input name="Delete" id="Delete" type="submit" value="Delete Selected"/>-->
        <div class="filter_div">
            <label>Filter: </label>
            <select name="filter_value" id="filter_value" placeholder="Chose a value">
                <option value="" disabled selected>Choose a value</option>
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
                        <th><input name="selectall" id="selectall" type="checkbox" /></th>
                        <!-- this is a toggle better-->
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
                        <td><input name="select_one" id="select_one" type="checkbox" /></td>
                        <td>ID</td>
                        <td>Name</td>
                        <td>Price</td>
                        <td>Stock</td>
                        <td>Category</td>
                        <td>Updated Date</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <!--table_div-->
        <button id="Delete_button">Delete</button>
        <!--same button repeated for easy user experience
                        in the popup window ___> <input name="Delete" id="Delete" type="submit" value="Delete Selected"/>-->
    </form>
</div>