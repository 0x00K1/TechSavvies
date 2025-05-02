<?php
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    // Not logged in -> redirect or return error
    header('Location: /');
    exit;
}

// Ensure an order_id is provided; if not, redirect (for example, to the cart)
if (!isset($_GET['order_id'])) {
  header('Location: /categories/cart');
  exit;
}
$orderId = $_GET['order_id'];

require_once __DIR__ . '/../../includes/db.php';

// Verify that the order exists and is still pending for this customer
$stmt = $pdo->prepare("SELECT status FROM orders WHERE order_id = :order_id AND customer_id = :cust");
$stmt->execute([
  ':order_id' => $orderId,
  ':cust' => $_SESSION['user']['customer_id'] ?? 0
]);
$order = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$order || $order['status'] !== 'pending') {
  header('Location: /categories/cart');
  exit;
}

$addresses = [];
try {
    $stmt = $pdo->prepare("SELECT * FROM addresses WHERE customer_id = ? ORDER BY is_primary DESC");
    $stmt->execute([$_SESSION['user']['customer_id']]);
    $addresses = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Error loading addresses: ".$e->getMessage());
}

$hasAddress = count($addresses) > 0;
if ($hasAddress) {
    // pick the primary (or first) address
    $primaryAddress = array_reduce($addresses, function($carry, $addr){
        return $addr['is_primary'] ? $addr : $carry;
    }, $addresses[0]);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Checkout</title>
  <?php require_once __DIR__ . '/../../assets/php/main.php'; ?>
  <link rel="stylesheet" href="../../assets/css/main.css">
  <link rel="stylesheet" href="../../assets/css/checkout.css">
  <script src="/assets/js/main.js"></script>
</head>
<body>

  <!-- Header Section -->
  <?php require_once __DIR__ . '/../../assets/php/header.php'; ?>

  <!-- Checkout Container -->
  <div class="checkout-container">
    <div class="checkout-header">
      <h1>Checkout</h1>
      <p>Complete your purchase securely</p>
      
      <div class="breadcrumb">
        <div class="breadcrumb-item">Cart</div>
        <div class="breadcrumb-item active">Checkout</div>
        <div class="breadcrumb-item">Confirmation</div>
      </div>
    </div>

    <!-- Check if cart is empty -->
    <div id="empty-cart">
      <!-- This will be populated by JavaScript -->
      <div class="empty-cart-message" id="emptyCartMessage" style="display:none;">
      <svg width="82px" height="82px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" stroke="#141414"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M7.5 18C8.32843 18 9 18.6716 9 19.5C9 20.3284 8.32843 21 7.5 21C6.67157 21 6 20.3284 6 19.5C6 18.6716 6.67157 18 7.5 18Z" stroke="#6e6e6e" stroke-width="1.5"></path> <path d="M16.5 18.0001C17.3284 18.0001 18 18.6716 18 19.5001C18 20.3285 17.3284 21.0001 16.5 21.0001C15.6716 21.0001 15 20.3285 15 19.5001C15 18.6716 15.6716 18.0001 16.5 18.0001Z" stroke="#6e6e6e" stroke-width="1.5"></path> <path d="M11.5 12.5L14.5 9.5M14.5 12.5L11.5 9.5" stroke="#6e6e6e" stroke-width="1.5" stroke-linecap="round"></path> <path d="M2 3L2.26121 3.09184C3.5628 3.54945 4.2136 3.77826 4.58584 4.32298C4.95808 4.86771 4.95808 5.59126 4.95808 7.03836V9.76C4.95808 12.7016 5.02132 13.6723 5.88772 14.5862C6.75412 15.5 8.14857 15.5 10.9375 15.5H12M16.2404 15.5C17.8014 15.5 18.5819 15.5 19.1336 15.0504C19.6853 14.6008 19.8429 13.8364 20.158 12.3075L20.6578 9.88275C21.0049 8.14369 21.1784 7.27417 20.7345 6.69708C20.2906 6.12 18.7738 6.12 17.0888 6.12H11.0235M4.95808 6.12H7" stroke="#6e6e6e" stroke-width="1.5" stroke-linecap="round"></path> </g></svg>
        <p>Your cart is currently empty.</p>
        <p>Looks like you haven't added any items to your cart yet.</p>
        <button id="continueShopping" class="continue-btn">Continue Shopping</button>
      </div>

      <div class="checkout-content" id="checkoutMainContent">
        <!-- LEFT COLUMN: Cart Preview, Billing Info & Payment Info -->
        <div class="left-section">
          
          <!-- Cart Preview (New Section) -->
          <div class="cart-preview" id="cartPreviewSection">
            <h2>
              Your Items
              <button class="edit-cart-btn" id="editCartBtn">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M12 20h9"></path>
                  <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path>
                </svg>
                Edit Cart
              </button>
            </h2>
            <div class="cart-preview-items" id="cartPreviewItems">
              <!-- Cart preview items will be populated here -->
            </div>
          </div>
          
          <!-- ========== BILLING BOX ========== -->
          <div class="billing-box" id="billing-box">
            <h2>
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                <polyline points="9 22 9 12 15 12 15 22"></polyline>
              </svg>
              Shipping Address
            </h2>
            <?php if ($hasAddress): ?>
              <ul class="billing-details">
                <li>
                  <strong>Name:</strong>
                  <span id="customerName">
                    <?= !empty($_SESSION['user']['username'])
                        ? htmlspecialchars($_SESSION['user']['username'])
                        : '' ?>
                  </span>
                </li>
                <li>
                  <strong>Email:</strong>
                  <span id="customerEmail">
                    <?= !empty($_SESSION['user']['email'])
                        ? htmlspecialchars($_SESSION['user']['email'])
                        : '' ?>
                  </span>
                </li>
                <li>
                  <strong>Address:</strong>
                  <span id="customerAddress">
                    <?= $hasAddress
                        ? htmlspecialchars($primaryAddress['address_line1']
                            . (!empty($primaryAddress['address_line2'])
                                ? ', '.$primaryAddress['address_line2']
                                : ''))
                        : '' ?>
                  </span>
                </li>
                <li>
                  <strong>City:</strong>
                  <span id="customerCity">
                    <?= $hasAddress ? htmlspecialchars($primaryAddress['city']) : '' ?>
                  </span>
                </li>
                <li>
                  <strong>State/Province:</strong>
                  <span id="customerState">
                    <?= $hasAddress ? htmlspecialchars($primaryAddress['state']) : '' ?>
                  </span>
                </li>
                <li>
                  <strong>ZIP/Postal:</strong>
                  <span id="customerZip">
                    <?= $hasAddress ? htmlspecialchars($primaryAddress['postal_code']) : '' ?>
                  </span>
                </li>
                <li>
                  <strong>Country:</strong>
                  <span id="customerCountry">
                    <?= $hasAddress ? htmlspecialchars($primaryAddress['country']) : '' ?>
                  </span>
                </li>
              </ul>

              <button id="editBillingBtn" class="address-billing-btn"
                      onclick="editAddress(<?= $primaryAddress['address_id'] ?>)">
                Edit Address
              </button>

            <?php else: ?>
              <ul class="billing-details">
                <li>
                  <strong>Name:</strong>
                  <span id="customerName">
                    <?= !empty($_SESSION['user']['username'])
                        ? htmlspecialchars($_SESSION['user']['username'])
                        : '' ?>
                  </span>
                </li>
                <li>
                  <strong>Email:</strong>
                  <span id="customerEmail">
                    <?= !empty($_SESSION['user']['email'])
                        ? htmlspecialchars($_SESSION['user']['email'])
                        : '' ?>
                  </span>
                </li>
                <li>
                  <strong>Address:</strong>
                  <span id="customerAddress">
                    <?= $hasAddress
                        ? htmlspecialchars($primaryAddress['address_line1']
                            . (!empty($primaryAddress['address_line2'])
                                ? ', '.$primaryAddress['address_line2']
                                : ''))
                        : '' ?>
                  </span>
                </li>
                <li>
                  <strong>City:</strong>
                  <span id="customerCity">
                    <?= $hasAddress ? htmlspecialchars($primaryAddress['city']) : '' ?>
                  </span>
                </li>
                <li>
                  <strong>State/Province:</strong>
                  <span id="customerState">
                    <?= $hasAddress ? htmlspecialchars($primaryAddress['state']) : '' ?>
                  </span>
                </li>
                <li>
                  <strong>ZIP/Postal:</strong>
                  <span id="customerZip">
                    <?= $hasAddress ? htmlspecialchars($primaryAddress['postal_code']) : '' ?>
                  </span>
                </li>
                <li>
                  <strong>Country:</strong>
                  <span id="customerCountry">
                    <?= $hasAddress ? htmlspecialchars($primaryAddress['country']) : '' ?>
                  </span>
                </li>
              </ul>

              <button id="createAddressBtn" class="address-billing-btn" onclick="openAddPopup()">
                Create Address
              </button>
            <?php endif; ?>
          </div>

          <!-- Add Address Popup -->
        <div id="add-popup" class="popup" onclick="closePopup('add-popup', event)">
            <div class="form-container" onclick="event.stopPropagation()">
                <h3>Add Address</h3>
                <form id="addAddressForm" onsubmit="return validateAddressForm('addAddressForm')">
                    <input type="hidden" name="action" value="add_address">
                    <div class="form-row">
                        <label for="country">Country</label>
                        <input type="text" id="country" name="country" placeholder="Country" required>
                    </div>
                    <div class="form-row">
                        <label for="address_line1">Address Line 1</label>
                        <input type="text" id="address_line1" name="address_line1" placeholder="Address Line 1" required>
                    </div>
                    <div class="form-row">
                        <label for="address_line2">Address Line 2</label>
                        <input type="text" id="address_line2" name="address_line2" placeholder="Address Line 2">
                    </div>
                    <div class="form-row inline-fields">
                        <div>
                            <label for="city">City</label>
                            <input type="text" id="city" name="city" placeholder="City" required>
                        </div>
                        <div>
                            <label for="state">State/Province</label>
                            <input type="text" id="state" name="state" placeholder="State/Province" required>
                        </div>
                        <div>
                            <label for="postal_code">Postal Code</label>
                            <input type="text" id="postal_code" name="postal_code" placeholder="Postal Code" required>
                        </div>
                    </div>
                    <div class="checkbox-container">
                        <input type="checkbox" id="is_primary" name="is_primary" <?php echo count($addresses) == 0 ? 'checked disabled' : ''; ?>>
                        <label for="is_primary">Set as primary address</label>
                    </div>
                    <div class="button-group">
                        <button type="submit" class="add-button">Save</button>
                        <button type="button" class="cancel-button" onclick="closePopup('add-popup')">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Edit Address Popup -->
        <div id="edit-popup" class="popup" onclick="closePopup('edit-popup', event)">
            <div class="form-container" onclick="event.stopPropagation()">
                <h3>Edit Address</h3>
                <form id="editAddressForm" method="POST" action="/profile.php" onsubmit="return validateAddressForm('editAddressForm')">
                    <input type="hidden" name="action" value="edit_address">
                    <input type="hidden" id="edit_address_id" name="address_id" value="">
                    <div class="form-row">
                        <label for="edit_country">Country</label>
                        <input type="text" id="edit_country" name="country" placeholder="Country" required>
                    </div>
                    <div class="form-row">
                        <label for="edit_address_line1">Address Line 1</label>
                        <input type="text" id="edit_address_line1" name="address_line1" placeholder="Address Line 1" required>
                    </div>
                    <div class="form-row">
                        <label for="edit_address_line2">Address Line 2</label>
                        <input type="text" id="edit_address_line2" name="address_line2" placeholder="Address Line 2">
                    </div>
                    <div class="form-row inline-fields">
                        <div>
                            <label for="edit_city">City</label>
                            <input type="text" id="edit_city" name="city" placeholder="City" required>
                        </div>
                        <div>
                            <label for="edit_state">State/Province</label>
                            <input type="text" id="edit_state" name="state" placeholder="State/Province" required>
                        </div>
                        <div>
                            <label for="edit_postal_code">Postal Code</label>
                            <input type="text" id="edit_postal_code" name="postal_code" placeholder="Postal Code" required>
                        </div>
                    </div>
                    <div class="checkbox-container">
                        <input type="checkbox" id="edit_is_primary" name="is_primary">
                        <label for="edit_is_primary">Set as primary address</label>
                    </div>
                    <div class="button-group">
                        <button type="submit" class="add-button">Update</button>
                        <button type="button" class="cancel-button" onclick="closePopup('edit-popup')">Cancel</button>
                    </div>
                </form>
            </div>
        </div>

          <!-- ========== PAYMENT INFO (FORM) ========== -->
          <div class="payment-info">
            <h2>
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect>
                <line x1="1" y1="10" x2="23" y2="10"></line>
              </svg>
              Payment Method
            </h2>

            <form id="checkoutForm">
              <div class="payment-methods-container">
                <div class="payment-options">

                  <!-- credit_card -->
                  <input
                    type="radio"
                    id="creditCard"
                    name="payment_method"
                    value="credit_card"
                    checked
                  />
                  <label for="creditCard" class="payment-option-label">
                    <!-- Card SVG -->
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                      <rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect>
                      <line x1="1" y1="10" x2="23" y2="10"></line>
                    </svg>
                    <span class="payment-option-name">Credit Card</span>
                  </label>

                  <!-- paypal -->
                  <input
                    type="radio"
                    id="paypal"
                    name="payment_method"
                    value="paypal"
                  />
                  <label for="paypal" class="payment-option-label">
                    <!-- PayPal SVG -->
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                      <path d="M17.5 5c0 2-1.3 3.5-4 3.5h-5.5l-1 7h-3l2-14h7.5c2 0 4 .5 4 3.5z"></path>
                      <path d="M20 5c0 2-1.3 3.5-4 3.5h-5.5l-1 7h-3l2-14h7.5c2 0 4 .5 4 3.5z"></path>
                    </svg>
                    <span class="payment-option-name">PayPal</span>
                  </label>

                  <!-- cash_on_delivery -->
                  <input
                    type="radio"
                    id="cod"
                    name="payment_method"
                    value="cash_on_delivery"
                  />
                  <label for="cod" class="payment-option-label">
                    <!-- Cash SVG -->
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                      <rect x="2" y="6" width="20" height="12" rx="2"></rect>
                      <circle cx="12" cy="12" r="2"></circle>
                      <path d="M6 12h.01M18 12h.01"></path>
                    </svg>
                    <span class="payment-option-name">Cash on Delivery</span>
                  </label>
                </div>
              </div>

              <!-- Credit Card Fields -->
              <div class="cc-fields" id="creditCardFields">
                <div class="form-group">
                  <label for="cardName">Name on Card</label>
                  <input
                    type="text"
                    id="cardName"
                    name="cardName"
                    placeholder="John Doe"
                  />
                </div>
                <div class="form-group">
                  <label for="cardNumber">Card Number</label>
                  <input
                    type="text"
                    id="cardNumber"
                    name="cardNumber"
                    placeholder="1234 5678 9123 4567"
                  />
                </div>
                <div class="card-row">
                  <div class="form-group">
                    <label for="expDate">Expiration Date</label>
                    <input
                      type="text"
                      id="expDate"
                      name="expDate"
                      placeholder="MM/YY"
                    />
                  </div>
                  <div class="form-group">
                    <label for="cvv">Security Code (CVV)</label>
                    <input
                      type="text"
                      id="cvv"
                      name="cvv"
                      placeholder="123"
                    />
                  </div>
                </div>
              </div>
            </form>
          </div>
        </div>

        <!-- RIGHT COLUMN: ORDER SUMMARY -->
        <div class="order-summary">
          <h2>Order Summary</h2>

          <div id="orderSummaryItems">
            <!-- Order summary items will be populated here -->
          </div>
          
          <div class="totals">
            <p><span>Subtotal</span> <span id="subtotalAmount">$0.00</span></p>
            <p><span>Shipping</span> <span id="shippingAmount">$0.00</span></p>
            <p><span>Tax</span> <span id="taxAmount">$0.00</span></p>
            <p class="total-row"><span>Total</span> <span id="totalAmount">$0.00</span></p>
          </div>

          <button type="submit" form="checkoutForm" class="place-order" id="placeOrderBtn">
            P a y
          </button>
          <a href="/#shop" class="continue-shopping-a">Continue Shopping</a>
          
          <div class="order-security">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
              <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
            </svg>
            Secure checkout - Your data is protected
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Loading Overlay -->
  <div class="loading-overlay" id="loadingOverlay">
    <div class="loading-spinner"></div>
  </div>

  <!-- Success/Error Message Toast -->
  <div class="toast-container" id="toastContainer">
    <div class="toast" id="toast">
      <div class="toast-content" id="toastContent"></div>
      <button class="toast-close" id="toastClose">&times;</button>
    </div>
  </div>

  <!-- Dialog Modal for Success/Error -->
  <div id="dialogOverlay" class="dialog-overlay">
    <div class="dialog-box">
      <p id="dialogMessage"></p>
      <button onclick="closeDialog()">Done</button>
    </div>
  </div>

   <!-- Include Checkout JS -->
   <script>
    window.orderId = <?= json_encode($orderId) ?>;
    function closeDialog() {
        document.getElementById('dialogOverlay').style.display = 'none';
    }
  </script>
  <script src="/assets/js/checkout.js"></script>
  <script src="/assets/js/addressconfig.js"></script>
</body>
</html>