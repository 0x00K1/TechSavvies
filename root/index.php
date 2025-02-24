<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="icon" href="..\assets\icons\Logo.ico">
    <link rel="stylesheet" href="..\assets\css\main.css">
    <link rel="stylesheet" href="..\assets\css\root.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <title>root</title>
</head>

<body>
    <!-- Header Section -->
    <?php include('..\assets\php\header.php'); ?>
    <script>
        <?php include('..\assets\js\root.js'); ?>
    </script>

    <!-- functions section -->
    <section class="hero">
        <div class="funcarea">
            <div class="toolbar">
                <button id="EditPro_button">Edit Products</button>
                <button id="EditUser_button">Edit User</button>
                <button id="Queries_button">Queries</button>
            </div>
            <div class="content-container">
                <div id="EditProduct" class="content">
                    <form id="addProduct_form">
                        <div class="EditProduct_bar">
                            <button id="AddPro_button">Add</button>
                            <button id="RemovePro_button">Remove</button>
                            <button id="ModifyPro_button">Modify</button>
                        </div>
                        <div id="add_display" class="AddProduct">
                            <p>
                                <label for="product_name">Product Name:</label>
                                <input type="text" name="product_name" id="product_name" placeholder="Enter the product's name" maxlength="255" required>
                            </p>
                            <p>
                                <label for="categoryList">Category:</label>
                                <input id="categoryList" type="text" placeholder="Choose a category..." list="Pro_category">
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
                                <textarea id="ProductDescreption" placeholder="Product description" required></textarea>
                            </p>
                            <p>
                                <label for="product_color">Product Color:</label>
                                <input type="text" name="product_color" id="product_color" placeholder="Enter the product's color" maxlength="255" required>
                            </p>
                            <p>
                                <label for="product_size">Product Size:</label>
                                <input type="text" name="product_size" id="product_size" placeholder="Enter the product's size" maxlength="50" required>
                            </p>
                            <p>
                                <label for="product_price">Product Price:</label>
                                <input type="number" name="product_price" id="product_price" placeholder="Enter the product's price" step="0.01" required>
                            </p>
                            <p>
                                <label for="product_stock">Product Stock:</label>
                                <input type="number" name="product_stock" id="product_stock" placeholder="Enter the product's stock" step="1" required>
                            </p>
                        </div>
                    </form>
                </div>
                <!-- Edit User -->
                <div id="EditUser" class="content" style="display: none;">
                    <form id="editUser_form">
                        <div class="EditUser_bar">
                            <button type="button" id="Add_user">Add</button>
                            <button type="button" id="Edit_User">Edit</button>
                        </div>
                        <div class="EditUser">
                            <p>
                                <label for="User_ID">User ID:</label>
                                <input type="text" id="User_ID" required>
                            </p>
                            <button class="search-button" id="User_Query_Button">Search</button>
                            <p>
                                <label for="Username">Username:</label>
                                <input type="text" id="Username" required>
                            </p>
                            <p>
                                <label for="User_Email">Email:</label>
                                <input type="email" id="User_Email" required>
                            </p>
                            <p>
                                <label for="User_Role">Role:</label>
                                <select id="User_Role" required>
                                    <option value="admin">Admin</option>
                                    <option value="user">User</option>
                                </select>
                            </p>
                        </div>



                </div>
                <div id="Queries" class="content" style="display: none;">
                    <p>queriese Like bills, payments, statistics , printing users or products, some graphs wpuld be amazing</p>
                </div>
            </div>
        </div>
    </section>
</body>

</html>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $name = $_POST["name"];
    $message = $_POST["message"];

    // Generate timestamp
    $timestamp = date("Y-m-d H:i:s"); // MySQL format

    // Database insertion (replace with your database code)
    // ... insert $name, $message, and $timestamp into your database ...

    // Display success message (or redirect)
    echo "Form submitted successfully at: " . $timestamp;
}
?>