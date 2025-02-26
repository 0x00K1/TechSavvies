<div id="addProPopup_display" class="popup">
        <span class="close" onclick="closeaddProPopup()">&times;</span>
        <form name="add_product_form" id="addProduct_form" method="post" action="index.php">
            <div id="add_display" class="AddProduct">
                <p>
                    <label for="product_name">Product Name:</label>
                    <input type="text" name="product_name" id="product_name" placeholder="Enter the product's name"
                        maxlength="255" required>
                </p>
                <p>
                    <label for="categoryList">Category:</label>
                    <input id="categoryList" type="text" name="Pro_category" placeholder="Choose a category..." list="Pro_category">
                    <datalist id="Pro_category">
                        <option value="test1"></option>
                        <option value="test2"></option>
                    </datalist>
                </p>
                <div id="imageContainer">
                    <label for="imageUpload">Upload Image:</label>
                    <input id="imageUpload" type="file" name="image" accept="image/*">
                </div>
                <p>
                    <label for="ProductDescreption">Product Description:</label>
                    <textarea id="ProductDescreption" placeholder="Product description" naem="ProductDescreption" required></textarea>
                </p>
                <p>
                    <label for="product_color">Product Color:</label>
                    <input type="text" name="product_color" id="product_color" placeholder="Enter the product's color"
                        maxlength="255" required>
                </p>
                <p>
                    <label for="product_size">Product Size:</label>
                    <input type="text" name="product_size" id="product_size" placeholder="Enter the product's size"
                        maxlength="50" required>
                </p>
                <p>
                    <label for="product_price">Product Price:</label>
                    <input type="number" name="product_price" id="product_price" placeholder="Enter the product's price"
                        step="0.01" required>
                </p>
                <p>
                    <label for="product_stock">Product Stock:</label>
                    <input type="number" name="product_stock" id="product_stock" placeholder="Enter the product's stock"
                        step="1" required>
                </p>
                <input name="submit_add_product" id="submit_add_product" type="submit" value="Add"/>
            </div>
        </form>
    </div>