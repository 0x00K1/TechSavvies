<?php
require_once __DIR__ . '/secure_session.php';
require_once __DIR__ . '/db.php';

// Set content type to JSON
header('Content-Type: application/json');
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Check if user is logged in
if (!isset($_SESSION['customer_id'])) {
    echo json_encode(['success' => false,
                      'message' => 'You must be logged in to submit a review']);
    exit;
}
$customerId = (int)$_SESSION['customer_id'];

// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request method'
    ]);
    exit;
}

// Get form data
$productId  = filter_input(INPUT_POST, 'product_id',  FILTER_VALIDATE_INT);
$rating     = filter_input(INPUT_POST, 'rating',      FILTER_VALIDATE_INT);
$reviewText = filter_input(INPUT_POST, 'review_text', FILTER_UNSAFE_RAW,
                           FILTER_FLAG_NO_ENCODE_QUOTES);

// Validate input
if (!$productId || !$rating || $rating < 1 || $rating > 5 ||
    !trim($reviewText)) {
    echo json_encode(['success'=>false,
                      'message'=>'Please fill in all required fields.']);
    exit;
}

if (strlen(trim($reviewText)) < 3) {
    echo json_encode([
        'success' => false,
        'message' => 'Review must be at least 10 characters long'
    ]);
    exit;
}

if (!$rating || $rating < 1 || $rating > 5) {
    echo json_encode([
        'success' => false,
        'message' => 'Please select a valid rating (1-5 stars)'
    ]);
    exit;
}

// Verify that user ID in form matches session
if ($customerId !== $_SESSION['customer_id']) {
    echo json_encode([
        'success' => false,
        'message' => 'User ID mismatch'
    ]);
    exit;
}

$reviewText = htmlspecialchars(trim($reviewText), ENT_QUOTES, 'UTF-8');

if (isset($_SESSION['last_review_time']) && 
    (time() - $_SESSION['last_review_time']) < 60) {
    echo json_encode([
        'success' => false,
        'message' => 'Please wait at least 1 minute between reviews'
    ]);
    exit;
}

// After successful submission
$_SESSION['last_review_time'] = time();

// Check if user has already reviewed this product
try {
    $checkSql = "SELECT review_id FROM reviews WHERE customer_id = :customer_id AND product_id = :product_id";
    $checkStmt = $pdo->prepare($checkSql);
    $checkStmt->bindValue(':customer_id', $customerId, PDO::PARAM_INT);
    $checkStmt->bindValue(':product_id', $productId, PDO::PARAM_INT);
    $checkStmt->execute();
    
    if ($checkStmt->rowCount() > 0) {
        // User has already reviewed this product, update their review
        $reviewId = $checkStmt->fetchColumn();
        
        $updateSql = "UPDATE reviews SET rating = :rating, review_text = :review_text WHERE review_id = :review_id";
        $updateStmt = $pdo->prepare($updateSql);
        $updateStmt->bindValue(':rating', $rating, PDO::PARAM_INT);
        $updateStmt->bindValue(':review_text', $reviewText, PDO::PARAM_STR);
        $updateStmt->bindValue(':review_id', $reviewId, PDO::PARAM_INT);
        $updateStmt->execute();
        
        echo json_encode([
            'success' => true,
            'message' => 'Your review has been updated successfully'
        ]);
        exit;
    }
    
    // Insert new review
    $insertSql = "INSERT INTO reviews (customer_id, product_id, rating, review_text) VALUES (:customer_id, :product_id, :rating, :review_text)";
    $insertStmt = $pdo->prepare($insertSql);
    $insertStmt->bindValue(':customer_id', $customerId, PDO::PARAM_INT);
    $insertStmt->bindValue(':product_id', $productId, PDO::PARAM_INT);
    $insertStmt->bindValue(':rating', $rating, PDO::PARAM_INT);
    $insertStmt->bindValue(':review_text', $reviewText, PDO::PARAM_STR);
    $insertStmt->execute();
    
    // Update product average rating
    // Calculate new average rating for the product
    $avgSql = "SELECT AVG(rating) as avg_rating FROM reviews WHERE product_id = :product_id";
    $avgStmt = $pdo->prepare($avgSql);
    $avgStmt->bindValue(':product_id', $productId, PDO::PARAM_INT);
    $avgStmt->execute();
    $avgRating = $avgStmt->fetch(PDO::FETCH_ASSOC)['avg_rating'];
    
    echo json_encode([
        'success' => true,
        'message' => 'Your review has been submitted successfully',
        'review' => [
            'customer_id' => $customerId,
            'rating' => $rating,
            'review_text' => $reviewText,
            'created_at' => date('Y-m-d H:i:s')
        ]
    ]);

} catch (PDOException $e) {
    // Log the error (in a production environment)
    error_log('Review submission error: ' . $e->getMessage());
    
    echo json_encode([
        'success' => false,
        'message' => 'An error occurred while processing your review. Please try again later.'
    ]);
}
?>