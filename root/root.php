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
    <div class="Boss">
        <!-- Toolbar -->
        <?php include('..\assets\php\toolbar.php'); ?>
        <!-- functions section -->
        <?php include('..\assets\php\funcarea.php'); ?>
        </div>
        <?php include('..\assets\php\add_product_popup.php')?>


        <script src="../assets/js/root.js"></script>  <!-- keep last in body so all html elemnts are loaded-->
    </body>
</html>