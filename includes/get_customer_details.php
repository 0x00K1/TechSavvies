<?php
require_once __DIR__ . '/secure_session.php';
require_once __DIR__ . '/db.php';

header('Content-Type: application/json');

// Verify user is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    echo json_encode(['success' => false, 'error' => 'User not logged in']);
    exit;
}

$customerId = $_SESSION['user']['customer_id'] ?? null;
if (!$customerId) {
    echo json_encode(['success' => false, 'error' => 'Customer ID not found']);
    exit;
}

// Query customer info and primary address using the is_primary flag.
$stmt = $pdo->prepare("
    SELECT 
      c.username AS name, 
      c.email,
      a.address_line1,
      a.address_line2,
      a.city,
      a.state,
      a.postal_code,
      a.country,
      a.is_primary,
      a.created_at
    FROM customers c
    LEFT JOIN addresses a ON c.customer_id = a.customer_id AND a.is_primary = 1
    WHERE c.customer_id = :cid
    LIMIT 1
");
$stmt->execute([':cid' => $customerId]);
$customer = $stmt->fetch(PDO::FETCH_ASSOC);

if ($customer) {
    echo json_encode(['success' => true, 'customer' => $customer]);
} else {
    echo json_encode(['success' => false, 'error' => 'No primary address found for customer']);
}
?>