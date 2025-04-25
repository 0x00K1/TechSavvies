<!DOCTYPE html>
<html lang="en">
<head>
  <title>Shopping Cart</title>
  <?php require_once __DIR__ . '/../../assets/php/main.php'; ?>
  <link rel="stylesheet" href="../../assets/css/main.css">
  <link rel="stylesheet" href="/assets/css/cart.css">
  <script src="/assets/js/main.js"></script>
</head>
<body>

  <!-- Header Section -->
  <?php require_once __DIR__ . '/../../assets/php/header.php'; ?>

  <!-- Cart Container -->
  <div class="cart-container">
    <div class="cart-header">
      <h1>Shopping Cart</h1>
      <p>Review your items before checkout</p>
      
      <div class="breadcrumb">
        <div class="breadcrumb-item active">Cart</div>
        <div class="breadcrumb-item">Checkout</div>
        <div class="breadcrumb-item">Confirmation</div>
      </div>
    </div>

    <div id="cartContent">
      <!-- Cart will be dynamically populated here -->
    </div>
  </div>
</body>
</html>