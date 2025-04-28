<?php
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    // Not logged in -> redirect or return error
    header('Location: /');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Order Confirmation</title>
  <?php require_once __DIR__ . '/../../assets/php/main.php'; ?>
  <link rel="stylesheet" href="/assets/css/main.css">
  <link rel="stylesheet" href="/assets/css/confirmation.css">
  <script src="/assets/js/main.js"></script>
</head>
<body>

  <!-- Header Section -->
  <?php require_once __DIR__ . '/../../assets/php/header.php'; ?>

  <!-- Confirmation Container -->
  <div class="confirmation-container">
    <div class="confirmation-header">
      <h1>Order Confirmation</h1>
      <p>Thank you for your purchase!</p>
      
      <div class="breadcrumb">
        <div class="breadcrumb-item">Cart</div>
        <div class="breadcrumb-item">Checkout</div>
        <div class="breadcrumb-item active">Confirmation</div>
      </div>
    </div>

    <!-- Success Animation -->
    <div class="success-animation">
      <div class="checkmark-circle">
        <div class="background"></div>
        <div class="checkmark draw"></div>
      </div>
    </div>

    <!-- Confirmation Message -->
    <div class="confirmation-message">
      <h2>Your order has been placed successfully!</h2>
      <p>We've received your order and will begin processing it right away. You'll receive a confirmation email shortly.</p>
      <p>Order #: <span class="order-id" id="orderId"></span></p>
      <p class="confirmation-email">An email receipt has been sent to <span id="customerEmail"></span></p>
    </div>

    <!-- Order Status Timeline -->
    <div class="order-status">
      <div class="status-step completed">
        <div class="status-icon">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <polyline points="20 6 9 17 4 12"></polyline>
          </svg>
        </div>
        <div class="status-label">Order Placed</div>
      </div>
      <div class="status-step current">
        <div class="status-icon">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M22 12h-4l-3 9L9 3l-3 9H2"></path>
          </svg>
        </div>
        <div class="status-label">Processing</div>
      </div>
      <div class="status-step">
        <div class="status-icon">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <rect x="1" y="3" width="15" height="13"></rect>
            <polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon>
            <circle cx="5.5" cy="18.5" r="2.5"></circle>
            <circle cx="18.5" cy="18.5" r="2.5"></circle>
          </svg>
        </div>
        <div class="status-label">Shipped</div>
      </div>
      <div class="status-step">
        <div class="status-icon">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
            <polyline points="9 22 9 12 15 12 15 22"></polyline>
          </svg>
        </div>
        <div class="status-label">Delivered</div>
      </div>
    </div>

    <!-- Order Details -->
    <div class="order-details">
      <h3>
        Order Details
        <span class="order-date" id="orderDate">April 15, 2025</span>
      </h3>

      <div class="order-content">
        <div class="left-section">
          <!-- Ordered Items -->
          <div class="ordered-items" id="orderedItems">
            <!-- Ordered items will be populated here by JavaScript -->
            <div class="loading-placeholder">Loading order items...</div>
          </div>
        </div>

        <div class="right-section">
          <!-- Order Summary -->
          <div class="order-summary-section">
            <h4>Order Summary</h4>
            <table class="order-summary-table">
              <tr>
                <td>Subtotal</td>
                <td id="subtotalAmount">$0.00</td>
              </tr>
              <tr>
                <td>Shipping</td>
                <td id="shippingAmount">$0.00</td>
              </tr>
              <tr>
                <td>Tax</td>
                <td id="taxAmount">$0.00</td>
              </tr>
              <tr class="order-total">
                <td>Total</td>
                <td id="totalAmount">$0.00</td>
              </tr>
            </table>
          </div>

          <!-- Shipping Information -->
          <div class="shipping-info">
            <h4>
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                <polyline points="9 22 9 12 15 12 15 22"></polyline>
              </svg>
              Shipping Address
            </h4>
            <div class="shipping-address">
              <div class="shipping-name" id="shippingName">Loading...</div>
              <div id="shippingAddress">Loading...</div>
              <div id="shippingCityState">Loading...</div>
              <div id="shippingCountry">Loading...</div>
            </div>
          </div>

          <!-- Payment Information -->
          <div class="payment-info">
            <h4>
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect>
                <line x1="1" y1="10" x2="23" y2="10"></line>
              </svg>
              Payment Method
            </h4>
            <div class="payment-method" id="paymentMethodDisplay">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect>
                <line x1="1" y1="10" x2="23" y2="10"></line>
              </svg>
              <div>
                <div class="payment-method-name">Credit Card</div>
                <div class="censored-card" id="censoredCardNumber">**** **** **** 4567</div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Action Buttons -->
    <div class="action-buttons">
      <a href="/#shop" class="btn btn-outline">Continue Shopping</a>
      <a href="#" class="btn btn-primary" id="trackOrderBtn">Track Order</a>
    </div>
  </div>

  <!-- JavaScript for Confirmation Page Logic -->
  <script src="/assets/js/confirmation.js"></script>
</body>
</html>