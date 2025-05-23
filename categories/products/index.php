<?php
require_once __DIR__ . '/../../includes/get_products.php';

$productsById = [];
foreach ($products as $prod) {
    $productsById[$prod['product_id']] = $prod;
}

if (isset($_GET['product_id']) && array_key_exists($_GET['product_id'], $productsById)) {
    $product = $productsById[$_GET['product_id']];
} else {
    echo "Product not found.";
    exit;
}

// Fetch reviews for this product
$reviews = [];
try {
    $reviewsSql = "
        SELECT r.review_id, r.rating, r.review_text, r.created_at, c.username 
        FROM reviews r
        JOIN customers c ON r.customer_id = c.customer_id
        WHERE r.product_id = :product_id
        ORDER BY r.created_at DESC
    ";
    $reviewsStmt = $pdo->prepare($reviewsSql);
    $reviewsStmt->bindValue(':product_id', $product['product_id'], PDO::PARAM_INT);
    $reviewsStmt->execute();
    $reviews = $reviewsStmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // Silent fail - we'll just show empty reviews
}

// Calculate average rating
$avgRating = 0;
$totalRatings = count($reviews);
if ($totalRatings > 0) {
    $ratingSum = array_sum(array_column($reviews, 'rating'));
    $avgRating = $ratingSum / $totalRatings;
}

$isLoggedIn  = isset($_SESSION['logged_in']) && $_SESSION['logged_in']
               && isset($_SESSION['customer_id']);
$customerId  = $isLoggedIn ? $_SESSION['customer_id'] : null;
?>
<html lang="en">
<head>
    <title>TechSavvies</title>
    <?php require_once __DIR__ . '/../../assets/php/main.php'; ?>
    <link rel="stylesheet" href="/../../assets/css/main.css">
    <link rel="stylesheet" href="../../assets/css/products.css">
    <script src="/assets/js/main.js"></script>
</head>
<body>
    <!-- Include header -->
    <?php require_once __DIR__ . '/../../assets/php/header.php'; ?>

    <div class="product-container">
        <div class="product-content">
        <div class="product-image">
            <img id="productImage"
                src="../<?php echo htmlspecialchars($product['image']); ?>"
                alt="<?php echo htmlspecialchars($product['name']); ?>"
                onerror="handleImageError(this, '<?php echo htmlspecialchars($product['image']); ?>')">
                </div>
            <div class="product-info">
                <h2 id="productName"><?php echo htmlspecialchars($product['name']); ?></h2>
                
                <div class="product-meta">
                    <div class="rating-container">
                        <div class="static-rating">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <?php if ($i <= round($avgRating)): ?>
                                    <i class="fas fa-star"></i>
                                <?php else: ?>
                                    <i class="far fa-star"></i>
                                <?php endif; ?>
                            <?php endfor; ?>
                            <span>(<?php echo $totalRatings; ?> reviews)</span>
                        </div>
                    </div>
                    
                    <div class="stock <?php echo $product['stock'] > 0 ? '' : 'out-of-stock'; ?>">
                        <?php echo $product['stock'] > 0 ? 'In Stock' : 'Out of Stock'; ?>
                    </div>
                </div>
                
                <p class="description"><?php echo htmlspecialchars($product['description']); ?></p>
                
                <div class="product-price">
                    <span class="currency">$</span><span id="productPrice"><?php echo htmlspecialchars($product['price']); ?></span>
                </div>

                <?php if (!empty($product['color'])): ?>
                    <div class="form-group">
                        <strong>Color:</strong> <?php echo htmlspecialchars($product['color']); ?>
                        <input type="hidden" id="productColor" value="<?php echo htmlspecialchars($product['color']); ?>">
                    </div>
                <?php else: ?>
                    <input type="hidden" id="productColor" value="">
                <?php endif; ?>

                <?php if (!empty($product['size'])): ?>
                    <div class="form-group">
                        <strong>Size:</strong> <?php echo htmlspecialchars($product['size']); ?>
                        <input type="hidden" id="productSize" value="<?php echo htmlspecialchars($product['size']); ?>">
                    </div>
                <?php else: ?>
                    <input type="hidden" id="productSize" value="">
                <?php endif; ?>

                <!-- Hidden inputs -->
                <input type="hidden" id="productId" value="<?php echo htmlspecialchars($product['product_id']); ?>">
                <input type="hidden" id="quantityInput" value="1">
                <input type="hidden" id="productStock" value="<?php echo htmlspecialchars($product['stock']); ?>">

                <!-- Add to Cart button -->
                <div class="add-to-cart-container">
                    <button type="button" class="add-to-cart">
                        <i class="fas fa-shopping-cart"></i> Add to Cart
                    </button>
                </div>
                <button id="helpButton" class="help-button"><i class="fas fa-question-circle"></i> Help</button>
            </div>
        </div>

        <!-- Reviews section -->
        <div class="review-section-container">
            <h2 class="review-section-title">Customer Reviews</h2>
            
            <div id="reviews" class="reviews-section">
                <div class="reviews-container">
                    <?php if (count($reviews) > 0): ?>
                        <?php foreach ($reviews as $review): ?>
                            <div class="review">
                                <div class="review-header">
                                    <div class="review-author"><?php echo htmlspecialchars($review['username']); ?></div>
                                    <div class="review-date"><?php echo date('F j, Y', strtotime($review['created_at'])); ?></div>
                                </div>
                                <div class="static-rating">
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <?php if ($i <= $review['rating']): ?>
                                            <i class="fas fa-star"></i>
                                        <?php else: ?>
                                            <i class="far fa-star"></i>
                                        <?php endif; ?>
                                    <?php endfor; ?>
                                </div>
                                <div class="review-content">
                                    <p><?php echo htmlspecialchars($review['review_text']); ?></p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="no-reviews">
                            <p>No reviews yet for this product. Be the first to leave a review!</p>
                        </div>
                    <?php endif; ?>
                </div>
                
                <!-- Review form -->
                <?php if ($isLoggedIn): ?>
                    <div class="review-form">
                        <h3>Write a Review</h3>
                        <form id="reviewForm" action="/includes/submit_review.php" method="POST">
                            <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($product['product_id']); ?>">
                            
                            <div class="form-group">
                                <div class="rate">
                                    <div class="star-rating">
                                        <input type="radio" id="star5" name="rating" value="5" required class="visually-hidden">
                                        <label for="star5" title="5 stars">★</label>
                                        <input type="radio" id="star4" name="rating" value="4" class="visually-hidden">
                                        <label for="star4" title="4 stars">★</label>
                                        <input type="radio" id="star3" name="rating" value="3" class="visually-hidden">
                                        <label for="star3" title="3 stars">★</label>
                                        <input type="radio" id="star2" name="rating" value="2" class="visually-hidden">
                                        <label for="star2" title="2 stars">★</label>
                                        <input type="radio" id="star1" name="rating" value="1" class="visually-hidden">
                                        <label for="star1" title="1 star">★</label>
                                    </div>
                                </div>
                                <div id="ratingError" class="error-message">Please select a rating</div>
                            </div>
                            
                            <div class="form-group">
                                <textarea id="reviewText" name="review_text" class="form-control" placeholder="Share your thoughts about this product..." required></textarea>
                            </div>
                            
                            <button type="submit" class="btn-submit">Submit Review</button>
                        </form>
                    </div>
                <?php else: ?>
                    <div class="login-message">
                        <p>Please <a id="login">log in</a> to leave a review.</p>
                    </div>
                <?php endif; ?>
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

    <!-- Help Modal -->
    <div id="helpModal" class="help-modal" style="display: none;">
        <div class="help-modal-content">
            <button id="closeHelpModal" class="help-modal-close">&times;</button>
            <h3>Product Page Help</h3>
            
            <div class="help-section">
                <h4><i class="fas fa-info-circle"></i> Product Information</h4>
                <p>This page displays detailed information about the product, including:</p>
                <ul>
                    <li>Product image (click to enlarge)</li>
                    <li>Product name and description</li>
                    <li>Price and availability</li>
                    <li>Customer reviews and ratings</li>
                    <li>Product specifications (color, size, etc. if applicable)</li>
                </ul>
            </div>
            
            <div class="help-section">
                <h4><i class="fas fa-shopping-cart"></i> Adding to Cart</h4>
                <p>To purchase this item:</p>
                <ol>
                    <li>Review the product details</li>
                    <li>Check availability (shown as "In Stock" or "Out of Stock")</li>
                    <li>Click the "Add to Cart" button</li>
                    <li>You'll see a confirmation message when the item is added</li>
                    <li>To complete your purchase, Click the Cart icon on the header or go to the <a href="/categories/cart">Cart</a> page</li>
                </ol>
            </div>
            
            <div class="help-section">
                <h4><i class="fas fa-star"></i> Reviews</h4>
                <p>Read customer reviews at the bottom of the page to help with your decision.</p>
                <p><strong>To write a review:</strong></p>
                <ol>
                    <li>You must be logged in (look for the login link if you aren't)</li>
                    <li>Select a star rating (1-5 stars)</li>
                    <li>Write your review in the text box</li>
                    <li>Click "Submit Review"</li>
                </ol>
            </div>
            
            <div class="help-section">
                <h4><i class="fas fa-question-circle"></i> Need More Help?</h4>
                <p>If you have any questions about this product or need further assistance, please contact our customer service team at <a href="/contact.php">Contact</a> page.</p>
            </div>
        </div>
    </div>

    <!-- Include footer -->
    <?php require_once __DIR__ . '/../../assets/php/footer.php'; ?>
    
    <script src="/assets/js/addtocart.js"></script>
    <script>
        window.isRoot = <?= json_encode($_SESSION['is_root'] ?? false) ?>;
        document.addEventListener('DOMContentLoaded', function() {
            const loginLink = document.getElementById('login');
            if (loginLink) {
                loginLink.addEventListener('click', function (e) {
                    e.preventDefault();
                    // if you have a flag telling you the user is already the root user:
                    if (window.isRoot === true) {
                    showToast('Not for Roots.', 'error');
                    return;
                    }
                    // make sure you have a global isAuthenticated boolean
                    if (!window.isAuthenticated) {
                    openAuthModal();
                    }
                });
            }

            // Toast functionality
            const toastContainer = document.getElementById('toastContainer');
            const toast = document.getElementById('toast');
            const toastContent = document.getElementById('toastContent');
            const toastClose = document.getElementById('toastClose');
            
            // Function to show toast
            function showToast(message, type = 'success') {
                toastContent.textContent = message;
                toast.className = 'toast ' + type;
                toastContainer.style.display = 'block';
                
                // Auto hide after 5 seconds
                setTimeout(() => {
                    toastContainer.style.display = 'none';
                }, 5000);
            }
            
            // Close toast on click
            toastClose.addEventListener('click', function() {
                toastContainer.style.display = 'none';
            });
            
            window.showToast = showToast;
            
            // Review form submission
            const reviewForm = document.getElementById('reviewForm');
            if (reviewForm) {
                reviewForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    // Add submitted class for error styling
                    this.classList.add('submitted');
                    
                    // Check if rating is selected
                    const ratingSelected = this.querySelector('input[name="rating"]:checked');
                    if (!ratingSelected) {
                        this.querySelector('#ratingError').style.display = 'block';
                        showToast('Please select a rating', 'error');
                        return false;
                    }
                    
                    // Validate review text
                    const reviewText = this.querySelector('#reviewText').value.trim();
                    if (reviewText.length < 3) {
                        showToast('Review must be at least 3 characters long', 'error');
                        return false;
                    }

                    if (reviewText.length > 500) {
                        showToast('Review must be at most 500 characters long', 'error');
                        return false;
                    }
                    
                    // Show loading overlay
                    document.getElementById('loadingOverlay').style.display = 'flex';
                    
                    // Submit the form
                    fetch(this.action, {
                        method: 'POST',
                        body: new FormData(this)
                    })
                    .then(response => response.json())
                    .then(data => {
                        document.getElementById('loadingOverlay').style.display = 'none';
                        if (data.success) {
                            showToast(data.message);
                            setTimeout(() => location.reload(), 1500);
                        } else {
                            showToast(data.message, 'error');
                        }
                    })
                    .catch(error => {
                        document.getElementById('loadingOverlay').style.display = 'none';
                        showToast('Error submitting review', 'error');
                        console.error('Error:', error);
                    });
                });
            }
        });

         // Get modal elements
        const helpModal = document.getElementById('helpModal');
        const helpButton = document.getElementById('helpButton');
        const closeHelpModal = document.getElementById('closeHelpModal');
        
        // Open modal when help button is clicked
        helpButton.addEventListener('click', function() {
            helpModal.style.display = 'flex';
            document.body.style.overflow = 'hidden'; // Prevent scrolling while modal is open
        });
        
        // Close modal when close button is clicked
        closeHelpModal.addEventListener('click', function() {
            helpModal.style.display = 'none';
            document.body.style.overflow = 'auto'; // Re-enable scrolling
        });
        
        // Close modal when clicking outside of modal content
        helpModal.addEventListener('click', function(event) {
            if (event.target === helpModal) {
                helpModal.style.display = 'none';
                document.body.style.overflow = 'auto'; // Re-enable scrolling
            }
        });
        
        // Close modal when ESC key is pressed
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape' && helpModal.style.display === 'flex') {
                helpModal.style.display = 'none';
                document.body.style.overflow = 'auto'; // Re-enable scrolling
            }
        });
    </script>
</body>
</html>