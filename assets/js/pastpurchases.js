document.addEventListener('DOMContentLoaded', function() {
    // Check if we're on the homepage
    if (window.location.pathname === '/' || window.location.pathname === '/index.php') {
      initPastPurchasesSection();
    }
  });
  
  /**
   * Initialize the past purchases section if the user has past orders
   */
  function initPastPurchasesSection() {
    // Get past purchases from cookie
    const pastPurchasesData = readCookie('pastPurchases');
    
    if (!pastPurchasesData) return; // No past purchases, exit
    
    try {
      const pastPurchases = JSON.parse(pastPurchasesData);
      
      // Only show if there are past purchases
      if (pastPurchases && pastPurchases.length > 0) {
        createPastPurchasesSection(pastPurchases);
      }
    } catch (error) {
      console.error('Error parsing past purchases:', error);
    }
  }
  
  /**
   * Helper to read a cookie by name
   */
  function readCookie(name) {
    const match = document.cookie.match(new RegExp('(?:^|; )' + name + '=([^;]*)'));
    return match ? decodeURIComponent(match[1]) : null;
  }
  
  /**
   * Create and insert the past purchases section
   */
  function createPastPurchasesSection(purchases) {
    // Create the section
    const section = document.createElement('section');
    section.className = 'past-purchases';
    section.id = 'past-purchases';
    
    // Format the HTML
    section.innerHTML = `
      <h2 class="section-title">Your Recent Purchases</h2>
      <div class="past-purchases-container">
        <div class="past-purchases-list">
          ${renderPastPurchases(purchases)}
        </div>
      </div>
    `;
    
    // Insert before the testimonials section
    const testimonialsSection = document.querySelector('.testimonials');
    if (testimonialsSection) {
      testimonialsSection.parentNode.insertBefore(section, testimonialsSection);
    } else {
      // Fallback: append to main content
      const mainContent = document.querySelector('.main-content');
      if (mainContent) {
        mainContent.appendChild(section);
      }
    }
  
    setTimeout(() => {
      section.classList.add('fade-in');
    }, 100);
  }
  
  /**
   * Render the list of past purchases
   */
  function renderPastPurchases(purchases) {
    if (!purchases.length) return '<p>No recent orders found.</p>';
  
    let html = '';
    purchases.forEach((purchase, index) => {
      if (index < 5) { // Limit to 5 most recent
        const date = new Date(purchase.orderDate);
        const formattedDate = formatDate(date);
        
        html += `
          <div class="past-purchase-item" data-order-id="${purchase.orderId}">
            <div class="past-purchase-info">
              <div class="past-purchase-date">${formattedDate}</div>
              <div class="past-purchase-id">Order #${purchase.orderId}</div>
              <div class="past-purchase-total">$${parseFloat(purchase.total).toFixed(2)}</div>
            </div>
            <div class="past-purchase-actions">
              <a href="/categories/orders/view.php?id=${purchase.orderId}" class="view-details-btn">View Details</a>
            </div>
          </div>
        `;
      }
    });
  
    html += `
      <div class="view-all-orders">
        <a href="/categories/orders/" class="view-all-btn">View All Orders</a>
      </div>
    `;
  
    return html;
  }

  function formatDate(date) {
    if (!(date instanceof Date) || isNaN(date)) {
      return 'Invalid date';
    }
    
    const now = new Date();
    const diffDays = Math.floor((now - date) / (1000 * 60 * 60 * 24));
    
    if (diffDays === 0) {
      return 'Today';
    } else if (diffDays === 1) {
      return 'Yesterday';
    } else if (diffDays < 7) {
      return `${diffDays} days ago`;
    } else {
      const options = { year: 'numeric', month: 'short', day: 'numeric' };
      return date.toLocaleDateString('en-US', options);
    }
  }
  
  // Add analytics event tracking for past purchase interactions
  document.addEventListener('click', function(e) {
    // Check if the click was on a past purchase item or its children
    const purchaseItem = e.target.closest('.past-purchase-item');
    
    if (purchaseItem) {
      const orderId = purchaseItem.dataset.orderId;
      
      // If they clicked the view details button
      if (e.target.classList.contains('view-details-btn')) {
        console.log('View details clicked for order:', orderId);
      }
    }
  });