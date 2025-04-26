document.addEventListener('DOMContentLoaded', function() {
    // Star rating hover effects for better UX
    const ratingInputs = document.querySelectorAll('.rate input');
    const ratingLabels = document.querySelectorAll('.rate label');
    
    // Add hover effect for visual feedback
    ratingLabels.forEach(label => {
        label.addEventListener('mouseover', function() {
            const currentId = this.getAttribute('for');
            const currentValue = currentId.replace('star', '');
            
            // Highlight stars on hover
            ratingLabels.forEach(lbl => {
                const lblId = lbl.getAttribute('for');
                const lblValue = lblId.replace('star', '');
                
                if (lblValue <= currentValue) {
                    lbl.classList.add('hover');
                } else {
                    lbl.classList.remove('hover');
                }
            });
        });
    });
    
    // Handle form submission for review
    const reviewForm = document.getElementById('reviewForm');
    const loadingOverlay = document.getElementById('loadingOverlay');
    
    if (reviewForm) {
        reviewForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            // Validate rating is selected
            const rating = document.querySelector('input[name="rating"]:checked');
            if (!rating) {
                showToast('Please select a rating', 'error');
                return;
            }
            
            // Validate review text
            const reviewText = document.getElementById('reviewText').value.trim();
            if (!reviewText || reviewText.length < 5) {
                showToast('Please enter a more detailed review', 'error');
                return;
            }
            
            // Show loading overlay
            loadingOverlay.style.display = 'flex';
            
            // Get form data
            const formData = new FormData(reviewForm);
            
            try {
                const response = await fetch(reviewForm.action, {
                    method: 'POST',
                    body: formData
                });
                
                const data = await response.json();
                
                // Hide loading overlay
                loadingOverlay.style.display = 'none';
                
                if (data.success) {
                    showToast('Your review has been submitted successfully!');
                    reviewForm.reset();
                    
                    // Add the new review to the page dynamically
                    if (data.review) {
                        addNewReviewToPage(data.review);
                    } else {
                        // Or reload the page to show all updated reviews
                        setTimeout(() => {
                            window.location.reload();
                        }, 1500);
                    }
                } else {
                    showToast(data.message || 'Failed to submit review. Please try again.', 'error');
                }
            } catch (error) {
                console.error('Error submitting review:', error);
                loadingOverlay.style.display = 'none';
                showToast('An error occurred. Please try again later.', 'error');
            }
        });
    }
    
    // Function to add new review to the page dynamically without reload
    function addNewReviewToPage(review) {
        const reviewsContainer = document.querySelector('.reviews-container');
        const noReviewsMessage = document.querySelector('.no-reviews');
        
        // Remove the "no reviews" message if it exists
        if (noReviewsMessage) {
            noReviewsMessage.remove();
        }
        
        // Create new review element
        const reviewEl = document.createElement('div');
        reviewEl.className = 'review';
        reviewEl.innerHTML = `
            <div class="review-header">
                <div class="review-author">You</div>
                <div class="review-date">${new Date().toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' })}</div>
            </div>
            <div class="static-rating">
                ${generateStars(review.rating)}
            </div>
            <div class="review-content">
                <p>${escapeHtml(review.review_text)}</p>
            </div>
        `;
        
        // Add new review at the top
        reviewsContainer.insertBefore(reviewEl, reviewsContainer.firstChild);
        
        // Smooth scroll to the new review
        reviewEl.scrollIntoView({ behavior: 'smooth', block: 'center' });
        
        // Add highlight effect
        setTimeout(() => {
            reviewEl.classList.add('highlight');
        }, 100);
    }
    
    // Helper function to generate star rating HTML
    function generateStars(rating) {
        let stars = '';
        for (let i = 1; i <= 5; i++) {
            if (i <= rating) {
                stars += '<i class="fas fa-star"></i>';
            } else {
                stars += '<i class="far fa-star"></i>';
            }
        }
        return stars;
    }
    
    // Helper function to escape HTML
    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
    
    // Toast notification system
    const toastContainer = document.getElementById('toastContainer');
    const toast = document.getElementById('toast');
    const toastContent = document.getElementById('toastContent');
    const toastClose = document.getElementById('toastClose');
    
    // Function to show toast
    window.showToast = function(message, type = 'success') {
        toastContent.textContent = message;
        toast.className = 'toast ' + type;
        toastContainer.style.display = 'block';
        
        // Auto hide after 5 seconds
        setTimeout(() => {
            toastContainer.style.display = 'none';
        }, 5000);
    };
    
    // Close toast on click
    if (toastClose) {
        toastClose.addEventListener('click', function() {
            toastContainer.style.display = 'none';
        });
    }
});