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
  <title>Track Your Order</title>
  <?php require_once __DIR__ . '/../../../assets/php/main.php'; ?>
  <link rel="stylesheet" href="/assets/css/main.css">
  <link rel="stylesheet" href="/assets/css/track.css">
  <script src="/assets/js/main.js"></script>
</head>
<body>

  <!-- Header Section -->
  <?php require_once __DIR__ . '/../../../assets/php/header.php'; ?>

  <!-- Tracking Container -->
  <div class="tracking-container">
    <div class="tracking-header">
      <h1>Track Your Order</h1>
      <p>Enter your order details to see your shipment status</p>
    </div>

    <!-- Order Search Box -->
    <div class="search-box">
      <h3>Track Your Order</h3>
      <form class="search-form" id="trackingForm">
        <input type="text" id="orderIdInput" placeholder="Enter your order number" required>
        <button type="submit">Track</button>
      </form>
    </div>

    <!-- Order Tracking Details (initially hidden) -->
    <div class="order-details" id="orderDetails" style="display: none;">
      <h3>
        Order #<span id="displayOrderId">12345</span>
        <span class="order-date" id="orderDate">April 15, 2025</span>
      </h3>

      <div class="order-content">
        <div class="left-section">
          <!-- Order Status Timeline -->
          <div class="order-status-details">
            <div class="order-status-timeline" id="statusTimeline">
              <!-- Timeline items will be populated by JavaScript -->
            </div>

            <!-- Estimated Delivery -->
            <div class="est-delivery">
              <h4>Estimated Delivery</h4>
              <div class="date" id="estDeliveryDate">Wednesday, April 23, 2025</div>
              <div class="time-range">Between 9:00 AM - 5:00 PM</div>
            </div>

            <!-- Tracking Map -->
            <div class="tracking-map">
              <div class="map-placeholder">
                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <circle cx="12" cy="10" r="3"></circle>
                  <path d="M12 21.7C17.3 17 20 13 20 10a8 8 0 1 0-16 0c0 3 2.7 7 8 11.7z"></path>
                </svg>
                <p>Interactive map will be available once your order ships</p>
              </div>
            </div>
          </div>
        </div>

        <div class="right-section">
          <!-- Order Summary -->
          <div class="order-summary">
            <h4>Order Summary</h4>
            <div class="order-summary-details">
              <div class="summary-item">
                <span class="summary-label">Order Date:</span>
                <span class="summary-value" id="summaryOrderDate">April 15, 2025</span>
              </div>
              <div class="summary-item">
                <span class="summary-label">Items:</span>
                <span class="summary-value" id="summaryItems">3 items</span>
              </div>
              <div class="summary-item">
                <span class="summary-label">Order Total:</span>
                <span class="summary-value" id="summaryTotal">$129.99</span>
              </div>
              <div class="summary-item">
                <span class="summary-label">Payment Method:</span>
                <span class="summary-value" id="summaryPayment">Credit Card</span>
              </div>
              <div class="summary-item">
                <span class="summary-label">Status:</span>
                <span class="summary-value" id="summaryStatus">Processing</span>
              </div>
            </div>
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
              <div class="shipping-name" id="shippingName"></div>
              <div id="shippingAddress"></div>
              <div id="shippingCityState"></div>
              <div id="shippingCountry"></div>
            </div>
          </div>

          <!-- Tracking Links -->
          <div class="shipping-info" id="trackingInfo">
            <h4>
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect x="1" y="3" width="15" height="13"></rect>
                <polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon>
                <circle cx="5.5" cy="18.5" r="2.5"></circle>
                <circle cx="18.5" cy="18.5" r="2.5"></circle>
              </svg>
              Tracking Information
            </h4>
            <div id="trackingDetails">
              <div class="summary-item">
                <span class="summary-label">Carrier:</span>
                <span class="summary-value" id="carrierName">-</span>
              </div>
              <div class="summary-item">
                <span class="summary-label">Tracking Number:</span>
                <span class="summary-value" id="trackingNumber">-</span>
              </div>
              <div id="trackingLink" style="margin-top: 15px; text-align: center;">
                <a href="#" class="btn btn-outline" style="display: none;" id="trackWithCarrierBtn">Track with Carrier</a>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Action Buttons -->
      <div class="action-buttons">
        <a href="/#shop" class="btn btn-outline">Continue Shopping</a>
      </div>
    </div>

    <!-- Order Not Found (initially hidden) -->
    <div class="order-not-found" id="orderNotFound" style="display: none;">
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <circle cx="12" cy="12" r="10"></circle>
        <line x1="12" y1="8" x2="12" y2="12"></line>
        <line x1="12" y1="16" x2="12.01" y2="16"></line>
      </svg>
      <h3>Order Not Found</h3>
      <p>We couldn't find an order with that number. Please check your order number and try again.</p>
      <a href="/#shop" class="btn btn-primary">Return to Shop</a>
    </div>

    <!-- Help Options Section -->
    <div class="help-options">
      <h3>Need Help with Your Order?</h3>
      <div class="help-cards">
        <div class="help-card">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path>
          </svg>
          <h4>Contact Support</h4>
          <p>Get help from our customer service team</p>
          <a href="/contact.php" class="help-link">Contact Us</a>
        </div>
        <div class="help-card">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
            <polyline points="22,6 12,13 2,6"></polyline>
          </svg>
          <h4>Email Us</h4>
          <p>Send us an email with your questions</p>
          <a href="mailto:support@techsavvies.com" class="help-link">support@techsavvies.shop</a>
        </div>
      </div>
    </div>
  </div>

  <!-- Loading Spinner -->
  <div id="loadingOverlay" style="display: none;">
    <div class="loading-spinner">
      <div class="spinner"></div>
      <p>Loading order details...</p>
    </div>
  </div>

  <!-- JavaScript for Tracking Page Logic -->
  <script src="/assets/js/tracking.js"></script>
</body>
</html>