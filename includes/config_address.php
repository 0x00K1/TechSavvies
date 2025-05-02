<?php
ob_start(); // Start output buffering immediately

require_once __DIR__ . '/../assets/php/main.php';
require_once __DIR__ . '/secure_session.php';
require_once __DIR__ . '/db.php';

header('Content-Type: application/json; charset=utf-8');

// Check if user is logged in and not a root user
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    // Return JSON error for AJAX or redirect for form submit
    if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
        echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
        exit;
    } else {
        header("Location: /");
        exit;
    }
}

// Get current user data from session
$user = $_SESSION['user'];
$customer_id = $user['customer_id'];

// Handle GET request to fetch address data for editing
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $address_id = intval($_GET['id']);
    
    try {
        // Verify this address belongs to the current customer
        $stmt = $pdo->prepare("SELECT * FROM addresses WHERE address_id = ? AND customer_id = ?");
        $stmt->execute([$address_id, $customer_id]);
        $address = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($address) {
            // Return address data as JSON
            header('Content-Type: application/json');
            ob_clean(); // Remove any previous output
            echo json_encode(['success' => true, 'address' => $address]);
            exit;
        } else {
            header('Content-Type: application/json');
            ob_clean(); // Remove any previous output
            echo json_encode(['success' => false, 'message' => 'Address not found']);
            exit;
        }
    } catch (PDOException $e) {
        error_log("Database error fetching address: " . $e->getMessage());
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Database error']);
        exit;
    }
}

// Handle different actions based on the 'action' parameter
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Add new address
    if (isset($_POST['action']) && $_POST['action'] === 'add_address') {
        // Validate inputs
        $address_line1 = trim($_POST['address_line1'] ?? '');
        $address_line2 = trim($_POST['address_line2'] ?? '');
        $city = trim($_POST['city'] ?? '');
        $state = trim($_POST['state'] ?? '');
        $postal_code = trim($_POST['postal_code'] ?? '');
        $country = trim($_POST['country'] ?? '');
        $is_primary = isset($_POST['is_primary']) ? 1 : 0;
        
        if (empty($address_line1) || empty($city) || empty($state) || empty($postal_code) || empty($country)) {
            $error = "Please fill in all required fields.";
        } else {
            try {
                // Check if user already has 3 addresses
                $stmt = $pdo->prepare("SELECT COUNT(*) as address_count FROM addresses WHERE customer_id = ?");
                $stmt->execute([$customer_id]);
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                
                if ($result['address_count'] >= 3) {
                    $error = "You have reached the maximum limit of 3 addresses.";
                } else {
                    // Begin transaction
                    $pdo->beginTransaction();
                    
                    // If this address is set as primary, remove primary status from all other addresses
                    if ($is_primary) {
                        $stmt = $pdo->prepare("UPDATE addresses SET is_primary = 0 WHERE customer_id = ?");
                        $stmt->execute([$customer_id]);
                    } else {
                        // If this is the first address, make it primary regardless
                        if ($result['address_count'] == 0) {
                            $is_primary = 1;
                        }
                    }
                    
                    // Insert new address
                    $stmt = $pdo->prepare("
                        INSERT INTO addresses 
                        (customer_id, address_line1, address_line2, city, state, postal_code, country, is_primary) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?)
                    ");
                    $stmt->execute([
                        $customer_id, 
                        $address_line1, 
                        $address_line2, 
                        $city, 
                        $state, 
                        $postal_code, 
                        $country,
                        $is_primary
                    ]);
                    
                    // Commit transaction
                    $pdo->commit();
                }
            } catch (PDOException $e) {
                // Rollback transaction on error
                $pdo->rollBack();
                error_log("Database error during address addition: " . $e->getMessage());
                $error = "A database error occurred. Please try again later.";
            }
        }
        
        // Redirect back to profile page with success/error message
        if (isset($error)) {
            $_SESSION['error_message'] = $error;
        } else {
            $_SESSION['success_message'] = $success;
        }
        
        $referer = $_SERVER['HTTP_REFERER'] ?? '';
        if (strpos($referer, '/categories/checkouts') !== false && strpos($referer, $_SERVER['HTTP_HOST']) !== false) {
            header("Location: $referer");
            exit;
        } else {
            header("Location: /profile.php#address");
            exit;
        }
    }
    
    // Edit existing address
    if (isset($_POST['action']) && $_POST['action'] === 'edit_address') {
        // Validate inputs
        $address_id = intval($_POST['address_id'] ?? 0);
        $address_line1 = trim($_POST['address_line1'] ?? '');
        $address_line2 = trim($_POST['address_line2'] ?? '');
        $city = trim($_POST['city'] ?? '');
        $state = trim($_POST['state'] ?? '');
        $postal_code = trim($_POST['postal_code'] ?? '');
        $country = trim($_POST['country'] ?? '');
        $is_primary = isset($_POST['is_primary']) ? 1 : 0;
        
        if ($address_id <= 0 || empty($address_line1) || empty($city) || empty($state) || empty($postal_code) || empty($country)) {
            $error = "Please fill in all required fields.";
        } else {
            try {
                // Verify this address belongs to the current customer
                $stmt = $pdo->prepare("SELECT address_id FROM addresses WHERE address_id = ? AND customer_id = ?");
                $stmt->execute([$address_id, $customer_id]);
                if (!$stmt->fetch(PDO::FETCH_ASSOC)) {
                    $error = "Address not found or you don't have permission to edit it.";
                } else {
                    // Begin transaction
                    $pdo->beginTransaction();
                    
                    // If this address is set as primary, remove primary status from all other addresses
                    if ($is_primary) {
                        $stmt = $pdo->prepare("UPDATE addresses SET is_primary = 0 WHERE customer_id = ?");
                        $stmt->execute([$customer_id]);
                    }
                    
                    // Update address
                    $stmt = $pdo->prepare("
                        UPDATE addresses 
                        SET address_line1 = ?, address_line2 = ?, city = ?, state = ?, 
                            postal_code = ?, country = ?, is_primary = ? 
                        WHERE address_id = ? AND customer_id = ?
                    ");
                    $stmt->execute([
                        $address_line1, 
                        $address_line2, 
                        $city, 
                        $state, 
                        $postal_code, 
                        $country,
                        $is_primary,
                        $address_id,
                        $customer_id
                    ]);
                    
                    // Commit transaction
                    $pdo->commit();
                    $success = "Address updated successfully.";
                }
            } catch (PDOException $e) {
                // Rollback transaction on error
                $pdo->rollBack();
                error_log("Database error during address update: " . $e->getMessage());
                $error = "A database error occurred. Please try again later.";
            }
        }
        
        // Redirect back to profile page with success/error message
        if (isset($error)) {
            $_SESSION['error_message'] = $error;
        } else {
            $_SESSION['success_message'] = $success;
        }
        
        $referer = $_SERVER['HTTP_REFERER'] ?? '';
        if (strpos($referer, '/categories/checkouts') !== false && strpos($referer, $_SERVER['HTTP_HOST']) !== false) {
            header("Location: $referer");
            exit;
        } else {
            header("Location: /profile.php#address");
            exit;
        }
    }
    
    // Set address as primary
    if (isset($_POST['action']) && $_POST['action'] === 'set_primary') {
        $address_id = intval($_POST['address_id'] ?? 0);
        
        if ($address_id <= 0) {
            $error = "Invalid address selected.";
        } else {
            try {
                // Verify this address belongs to the current customer
                $stmt = $pdo->prepare("SELECT address_id FROM addresses WHERE address_id = ? AND customer_id = ?");
                $stmt->execute([$address_id, $customer_id]);
                if (!$stmt->fetch(PDO::FETCH_ASSOC)) {
                    $error = "Address not found or you don't have permission to modify it.";
                } else {
                    // Begin transaction
                    $pdo->beginTransaction();
                    
                    // Remove primary status from all addresses for this customer
                    $stmt = $pdo->prepare("UPDATE addresses SET is_primary = 0 WHERE customer_id = ?");
                    $stmt->execute([$customer_id]);
                    
                    // Set this address as primary
                    $stmt = $pdo->prepare("UPDATE addresses SET is_primary = 1 WHERE address_id = ?");
                    $stmt->execute([$address_id]);
                    
                    // Commit transaction
                    $pdo->commit();
                    $success = "Address set as primary successfully.";
                }
            } catch (PDOException $e) {
                // Rollback transaction on error
                $pdo->rollBack();
                error_log("Database error setting primary address: " . $e->getMessage());
                $error = "A database error occurred. Please try again later.";
            }
        }
        
        // Return JSON response for AJAX requests
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
            header('Content-Type: application/json');
            ob_clean(); // Remove any previous output
            if (isset($error)) {
                echo json_encode(['success' => false, 'message' => $error]);
            } else {
                echo json_encode(['success' => true, 'message' => $success]);
            }
            exit;
        } else {
            if (isset($error)) {
                $_SESSION['error_message'] = $error;
            } else {
                $_SESSION['success_message'] = $success;
            }
            $referer = $_SERVER['HTTP_REFERER'] ?? '';
            if (strpos($referer, '/categories/checkouts') !== false && strpos($referer, $_SERVER['HTTP_HOST']) !== false) {
                header("Location: $referer");
                exit;
            } else {
                header("Location: /profile.php#address");
                exit;
            }
        }
    }
    
    // Delete address (POST with id parameter)
    if (isset($_POST['id'])) {
        $address_id = intval($_POST['id']);
        
        try {
            // Begin transaction
            $pdo->beginTransaction();
            
            // First check if this address exists and belongs to the customer
            $stmt = $pdo->prepare("SELECT is_primary FROM addresses WHERE address_id = ? AND customer_id = ?");
            $stmt->execute([$address_id, $customer_id]);
            $address = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$address) {
                throw new Exception("Address not found or you don't have permission to delete it.");
            }
            
            // Delete the address
            $stmt = $pdo->prepare("DELETE FROM addresses WHERE address_id = ? AND customer_id = ?");
            $stmt->execute([$address_id, $customer_id]);
            
            // If we deleted a primary address, set another address as primary (if any exist)
            if ($address['is_primary']) {
                $stmt = $pdo->prepare("SELECT address_id FROM addresses WHERE customer_id = ? LIMIT 1");
                $stmt->execute([$customer_id]);
                $newPrimary = $stmt->fetch(PDO::FETCH_ASSOC);
                
                if ($newPrimary) {
                    $stmt = $pdo->prepare("UPDATE addresses SET is_primary = 1 WHERE address_id = ?");
                    $stmt->execute([$newPrimary['address_id']]);
                }
            }
            
            // Commit transaction
            $pdo->commit();
            $success = "Address deleted successfully.";
            
            // Return success response
            if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
                header('Content-Type: application/json');
                ob_clean(); // Remove any previous output
                echo json_encode(['success' => true, 'message' => $success]);
                exit;
            } else {
                $_SESSION['success_message'] = $success;
                $referer = $_SERVER['HTTP_REFERER'] ?? '';
                if (strpos($referer, '/categories/checkouts') !== false && strpos($referer, $_SERVER['HTTP_HOST']) !== false) {
                    header("Location: $referer");
                    exit;
                } else {
                    header("Location: /profile.php#address");
                    exit;
                }
            }
            
        } catch (Exception $e) {
            // Rollback transaction on error
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            
            error_log("Error during address deletion: " . $e->getMessage());
            $error = $e->getMessage();
            
            // Return error response
            if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
                header('Content-Type: application/json');
                ob_clean(); // Remove any previous output
                echo json_encode(['success' => false, 'message' => $error]);
                exit;
            } else {
                $_SESSION['error_message'] = $error;
                $referer = $_SERVER['HTTP_REFERER'] ?? '';
                if (strpos($referer, '/categories/checkouts') !== false && strpos($referer, $_SERVER['HTTP_HOST']) !== false) {
                    header("Location: $referer");
                    exit;
                } else {
                    header("Location: /profile.php#address");
                    exit;
                }
            }
        }
    }
}

// If we get here, it means no valid request was made
header("Location: /profile.php");
exit;
?>