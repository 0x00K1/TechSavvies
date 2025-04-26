document.addEventListener('DOMContentLoaded', function() {
    const reviewForm = document.getElementById('reviewForm');
    const messageDiv = document.getElementById('reviewMessage');

    if (reviewForm) {
        reviewForm.addEventListener('submit', async function(e) {
            e.preventDefault();

            const formData = new FormData(this);

            const response = await fetch('../../categories/products/submit_review.php', {  // <-- correct path
                method: 'POST',
                body: formData
            });

            const result = await response.json();

            if (result.success) {
                messageDiv.innerHTML = '<p style="color:green;">Review submitted successfully!</p>';
                this.reset();
                loadReviews();
            } else {
                messageDiv.innerHTML = `<p style="color:red;">Error: ${result.error ?? 'Unknown error'}</p>`;
            }
        });
    }
});

async function loadReviews() {
    location.reload();
}
