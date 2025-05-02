<?php
require_once __DIR__ . '/assets/php/main.php';
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: /");
    exit;
}

require_once __DIR__ . '/includes/db.php';

// Get current user data from session
$user = $_SESSION['user'];
$isRoot = $_SESSION['is_root'];

// Process profile update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_profile') {
    // Validate inputs
    $new_username = trim($_POST['username'] ?? '');
    $new_email = trim($_POST['email'] ?? '');

    // Backend filters: username must be 3-20 characters and only contain letters, numbers, underscores, and periods.
    if (empty($new_username) || strlen($new_username) < 3 || strlen($new_username) > 20) {
        $_SESSION['error_message'] = "Username must be between 3 and 20 characters.";
    } elseif (!preg_match('/^[a-zA-Z0-9_.]+$/', $new_username)) {
        $_SESSION['error_message'] = "Username may only contain letters, numbers, underscores, and periods.";
    } elseif (empty($new_email) || !filter_var($new_email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error_message'] = "Please provide a valid email address.";
    } else {
        try {
            // Check if the new email already exists in either the roots or customers tables.
            // Exclude the current user from the check.
            $emailExists = false;
            
            // Prepare the query for roots table.
            $stmt = $pdo->prepare("SELECT root_id FROM roots WHERE email = ? AND root_id != ?");
            $stmt->execute([$new_email, $isRoot ? $user['root_id'] : 0]);
            if ($stmt->fetch(PDO::FETCH_ASSOC)) {
                $emailExists = true;
            }
            
            // Prepare the query for customers table.
            $stmt = $pdo->prepare("SELECT customer_id FROM customers WHERE email = ? AND customer_id != ?");
            $stmt->execute([$new_email, !$isRoot ? $user['customer_id'] : 0]);
            if ($stmt->fetch(PDO::FETCH_ASSOC)) {
                $emailExists = true;
            }
            
            if ($emailExists) {
                $_SESSION['error_message'] = "The email address is already in use by another account.";
            } else {
                // Depending on user type, update the appropriate table.
                if ($isRoot) {
                    $stmt = $pdo->prepare("UPDATE roots SET username = ?, email = ? WHERE root_id = ?");
                    $stmt->execute([$new_username, $new_email, $user['root_id']]);
                    // Update session info.
                    $user['username'] = $new_username;
                    $user['email'] = $new_email;
                    $_SESSION['user'] = $user;
                } else {
                    $stmt = $pdo->prepare("UPDATE customers SET username = ?, email = ? WHERE customer_id = ?");
                    $stmt->execute([$new_username, $new_email, $user['customer_id']]);
                    // Update session info.
                    $user['username'] = $new_username;
                    $user['email'] = $new_email;
                    $_SESSION['user'] = $user;
                }
                $_SESSION['success_message'] = "Profile updated successfully.";
            }
        } catch (PDOException $e) {
            error_log("Database error during profile update: " . $e->getMessage());
            $_SESSION['error_message'] = "A database error occurred. Please try again later.";
        }
    }
    // After processing the update, redirect to the profile page so the message can be shown.
    header("Location: profile.php");
    exit;
}

// Check for message session variables and use them (for address operations)
if (isset($_SESSION['success_message'])) {
    $success = $_SESSION['success_message'];
    unset($_SESSION['success_message']);
}
if (isset($_SESSION['error_message'])) {
    $error = $_SESSION['error_message'];
    unset($_SESSION['error_message']);
}

// Retrieve saved addresses for non-root users.
$addresses = [];
if (!$isRoot) {
    try {
        $stmt = $pdo->prepare("SELECT * FROM addresses WHERE customer_id = ? ORDER BY is_primary DESC");
        $stmt->execute([$user['customer_id']]);
        $addresses = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Database error fetching addresses: " . $e->getMessage());
        $error = "Failed to load addresses.";
    }
}

// Retrieve previous orders for non-root users
$orders = [];
if (!$isRoot) {
    try {
        $stmt = $pdo->prepare("
            SELECT o.*, 
                   COUNT(oi.product_id) as total_items,
                   p.payment_status
            FROM orders o
            LEFT JOIN order_items oi ON o.order_id = oi.order_id
            LEFT JOIN payments p ON o.order_id = p.order_id
            WHERE o.customer_id = ?
            GROUP BY o.order_id
            ORDER BY o.order_date DESC
            LIMIT 3
        ");
        $stmt->execute([$user['customer_id']]);
        $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Database error fetching orders: " . $e->getMessage());
        $error = "Failed to load order history.";
    }
}

// Handle address operations
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    require_once __DIR__ . '/includes/config_address.php';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Profile</title>
  <link rel="stylesheet" href="assets/css/main.css" />
  <link rel="stylesheet" href="assets/css/profile.css">
  <style>
    
  </style>
</head>
<body>
  <!-- Header Section -->
  <?php require_once __DIR__ . '/assets/php/header.php'; ?>

  <!-- Main Content -->
  <div class="main-content">
    <!-- Profile Section -->
    <div class="profile">
      <h1>Profile</h1>
      <form method="POST" action="profile.php" onsubmit="return validateProfileForm()">
          <input type="hidden" name="action" value="update_profile">
          <label for="username">Username</label>
          <input type="text" id="username" name="username" placeholder="Username" required value="<?php echo htmlspecialchars($user['username']); ?>">
          
          <label for="email">Email</label>
          <input type="email" id="email" name="email" placeholder="Email" required value="<?php echo htmlspecialchars($user['email']); ?>">
          
          <div class="button-group">
              <button type="submit" class="add-button">Update</button>
              <button type="button" class="cancel-button" onclick="window.location.href='/';">Cancel</button>
          </div>
      </form>
    </div>

    <!-- Addresses Section (only for non-root users) -->
    <?php if (!$isRoot): ?>
    <div id="address" class="address-section">
        <h2>Addresses</h2>
        
        <!-- Display saved addresses -->
        <div class="saved-addresses">
            <?php if (!empty($addresses)): ?>
                <?php foreach ($addresses as $address): ?>
                    <div class="address-card">
                        <?php if ($address['is_primary']): ?>
                            <span class="primary-badge">Primary</span>
                        <?php endif; ?>
                        
                        <p><strong><?php echo htmlspecialchars($address['address_line1']); ?></strong></p>
                        
                        <?php if (!empty($address['address_line2'])): ?>
                            <p><?php echo htmlspecialchars($address['address_line2']); ?></p>
                        <?php endif; ?>
                        
                        <p>
                            <?php 
                                echo htmlspecialchars(
                                    $address['city'] . ', ' . 
                                    $address['state'] . ' ' . 
                                    $address['postal_code'] . ', ' . 
                                    $address['country']
                                ); 
                            ?>
                        </p>
                        
                        <div>
                            <button class="edit-btn" onclick="editAddress(<?php echo htmlspecialchars($address['address_id']); ?>)">Edit</button>
                            <button class="delete-btn" onclick="deleteAddress(<?php echo htmlspecialchars($address['address_id']); ?>)">Delete</button>
                            
                            <?php if (!$address['is_primary']): ?>
                                <button class="primary-btn" onclick="setPrimaryAddress(<?php echo htmlspecialchars($address['address_id']); ?>)">Set as Primary</button>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="no-addresses">No saved addresses.</p>
            <?php endif; ?>
        </div>
        
        <?php if (count($addresses) < 3): ?>
            <button class="add-button" onclick="openAddPopup()">Add New Address</button>
        <?php endif; ?>
        
        <!-- Add Address Popup -->
        <div id="add-popup" class="popup" onclick="closePopup('add-popup', event)">
            <div class="form-container" onclick="event.stopPropagation()">
                <h3>Add Address</h3>
                <form id="addAddressForm" method="POST" action="profile.php" onsubmit="return validateAddressForm('addAddressForm')">
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
                <form id="editAddressForm" method="POST" action="profile.php" onsubmit="return validateAddressForm('editAddressForm')">
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
    </div>
    <?php endif; ?>

    <!-- Previous Orders Section (only for non-root users) -->
    <?php if (!$isRoot): ?>
    <div id="orders" class="orders-section">
        <h2>Order History</h2>
        <?php if (!empty($orders)): ?>
            <div class="saved-orders">
                <?php foreach ($orders as $order): ?>
                    <div class="order-card">
                        <div class="order-header">
                            <div>
                                <strong>Order #<?php echo htmlspecialchars($order['order_id']); ?></strong>
                                <div class="order-date">
                                    <?php echo date('M j, Y', strtotime($order['order_date'])); ?>
                                </div>
                            </div>
                            <div>
                                <span class="status-badge status-<?php echo strtolower($order['status']); ?>">
                                    <?php echo ucfirst($order['status']); ?>
                                </span>
                            </div>
                        </div>
                        <div class="order-details">
                            <div>Total Items: <?php echo htmlspecialchars($order['total_items']); ?></div>
                            <div>Amount: $<?php echo number_format($order['total_amount'], 2); ?></div>
                            <div>Payment: <?php echo ucfirst($order['payment_status'] ?? 'Pending'); ?></div>
                        </div>
                        <div style="text-align: right; margin-top: 10px;">
                            <button class="view-order-btn" onclick="window.location.href='/categories/orders/view.php?id=<?php echo $order['order_id']; ?>'">View Details</button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <button class="view-orders-button" onclick="window.location.href='/categories/orders/index.php'">View All Orders</button>
        <?php else: ?>
            <p class="no-orders">No orders yet.</p>
        <?php endif; ?>
    </div>
    <?php endif; ?>
  </div>

  <!-- Dialog Modal for Success/Error -->
  <div id="dialogOverlay" class="dialog-overlay">
    <div class="dialog-box">
      <p id="dialogMessage"></p>
      <button onclick="closeDialog()">Done</button>
    </div>
  </div>

  <script src="/assets/js/main.js"></script>
  <script src="/assets/js/addressconfig.js"></script>
  <script>
    function showDialog(message) {
        document.getElementById('dialogMessage').innerText = message;
        document.getElementById('dialogOverlay').style.display = 'flex';
    }
    
    function closeDialog() {
      document.getElementById('dialogOverlay').style.display = 'none';
    }

    function validateProfileForm() {
        const usernameInput = document.getElementById('username');
        const emailInput = document.getElementById('email');
        const username = usernameInput.value.trim();
        const email = emailInput.value.trim();

        let isValid = true;
        let errorMsg = '';

        // Username validation
        if (username.length < 3 || username.length > 20) {
            errorMsg += '- Username must be between 3 and 20 characters.\n';
            usernameInput.classList.add('input-error');
            isValid = false;
        } else if (!/^[a-zA-Z0-9_.]+$/.test(username)) {
            errorMsg += '- Username may only contain letters, numbers, underscores, and periods.\n';
            usernameInput.classList.add('input-error');
            isValid = false;
        } else {
            usernameInput.classList.remove('input-error');
        }

        // Email validation
        if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
            errorMsg += '- Please enter a valid email address.\n';
            emailInput.classList.add('input-error');
            isValid = false;
        } else {
            emailInput.classList.remove('input-error');
        }

        if (!isValid) {
            showDialog('Please fix the following:\n' + errorMsg);
        }

        return isValid;
    }
  </script>
</body>
</html>