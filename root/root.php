<!DOCTYPE html>
<html lang="en">
    <head>
        <link rel="icon" href="..\assets\icons\Logo.ico">
        <link rel="stylesheet" href="..\assets\css\main.css">
        <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
        <title>root</title>
    </head>
    <body>
        <!-- Header Section -->
        <?php include('..\assets\php\header.php'); ?>
        <script><?php include ('..\assets\js\root.js'); ?></script>

        <!-- functions section -->
        <section class= "hero">
            <div class="funcarea">
                <div class="toolbar">
                    <button id="EditPro_button">Edit Products</button> 
                    <button id="EditUser_button">Edit User</button>
                    <button id="Queries_button">Queries</button>
                </div>
                <div class="content-container">
                    <div id="EditProduct" class="content">
                        <!-- specify required inputs!!!!!-->
    <!-- product form--> <form id="addProduct_form"><!-- the form attributes!!!!!!!-->
                            <div class="EditProduct_bar">
                                <button id="AddPro_butoon">Add</button> 
                                <button id="RemovePro_button">Remove</button>
                                <button id="ModifyPro_button">modify</button>
                            </div>
                            <div id="add_display" class="AddProduct">
<!--product name-->              <input type="text" name="product_name" placeholder="Enter the product's name" maxlength="255">
<!-- add category or chose exist-->
<!-- add category logic and interface needed!!!!!!-->
<!-- category list-->            <input id="categoryList" type="text"  placeholder="Chose a category.." list="Pro_category">
               
                                    <datalist id="Pro_category">
                                        <option>test1 <!-- php logic for fetching category data--> </option>
                                        <option>2</option>
                                    </datalist>
<!-- image upload-->             <div id="imageContainer">
                                    <label for="imageUpload">Upload Image:</label>
                                    <input id="imageUpload" type="file" name="image" accept="image/*">
                                </div>
<!--Product Descreption-->       <div id="ProductTextareaDiv">
                                    <textarea id="ProductDescreption" placeholder="Product descreption"></textarea>
                                </div>
<!--product color-->             <input type="text" name="product_color" placeholder="Enter the product's color" maxlength="255">
<!--product size-->              <input type="text" name="product_size" placeholder="Enter the product's size" maxlength="50">                                
<!--product price-->             <input type="number" name="product_price" placeholder="Enter the product's price" step="0.01">
<!--product stock-->             <input type="number" name="product_stock" placeholder="Enter the product's stock" step="1">
<!-- generated time and updated time implemntaion is automatic code last in the file V-->
                            </div><!-- id="add_display"-->
                        </form><!-- id="addProduct_form"-->
                    </div>
                    <div id="EditUser" class="content" style="display: none;">
                        <p>Edit user</p>
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