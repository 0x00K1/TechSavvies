<!DOCTYPE html>
<html lang="en">
    <head>
        <link rel="icon" href="..\assets\icons\Logo.ico">
        <link rel="stylesheet" href="..\assets\css\main.css">
        <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
        <title>Admin Page</title>
    </head>
    <body>
        <!-- Header Section -->
        <?php include('..\assets\php\header.php'); ?>
        <script><?php include ('..\assets\js\root.js'); ?></script>

        <!-- functions section -->
<<<<<<< HEAD
        <section class= "hero">
            <div class="funcarea">
                <div class="toolbar">
                    <button id="EditPro_button">Edit Products</button> <!-- make on default-->
                    <button id="EditUser_button">Edit User</button>
                    <button id="Queries_button">Queries</button>
                </div>
                <div class="content-container">
                    <div id="EditProduct" class="content">
                        <form>
                            <p>test</p>
                        </form>
                    </div>
                    <div id="EditUser" class="content" style="display: none;">
                        <p>Remove Item Form/Content</p>
                    </div>
                    <div id="Queries" class="content" style="display: none;">
                        <p>Edit Item Form/Content</p>
                    </div>
                </div>
            </div>    
        </section>
=======
        <div class="funcarea">
            <div class="toolbar">
                    <!-- STILL NOT CLEAR WHETHER I WILL MAKE ANOTHER PAGES FOR EVERY FUNCTION OR ADD FUNCTIONS IN <div></div> CONTAINERS -->
                <div><button id="BAdd">Add Item</button></div>
                <div><button id="BRemove">Remove Item</button></div>
                <div><button id="BEdit">Edit Item</button></div>
                <div class=toolbar-spacer></div>
            </div>
                <div class="content-container">
                    <div id="addContent" class="content">
                        <p>Add Item Form/Content</p>
                    </div>
                    <div id="removeContent" class="content" style="display: none;">
                        <p>Remove Item Form/Content</p>
                    </div>
                    <div id="editContent" class="content" style="display: none;">
                        <p>Edit Item Form/Content</p>
                    </div>
                </div>
            </div>        
>>>>>>> 6ca0705560e707a812b2f5d2d3f6394ed6314e41
    </body>
</html> 