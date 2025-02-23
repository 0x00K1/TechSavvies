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
    </body>
</html> 