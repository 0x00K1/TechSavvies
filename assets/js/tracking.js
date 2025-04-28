document.addEventListener('DOMContentLoaded', function() {
    // Get the search form and attach event listener
    const trackingForm = document.getElementById('trackingForm');
    const orderIdInput = document.getElementById('orderIdInput');
    
    // Check URL for order_id parameter
    const urlParams = new URLSearchParams(window.location.search);
    const orderIdFromUrl = urlParams.get('id');
    
    if (orderIdFromUrl) {
      // If order ID is in URL, populate the input and trigger search
      orderIdInput.value = orderIdFromUrl;
      fetchOrderDetails(orderIdFromUrl);
    }
    
    // Handle form submission
    trackingForm.addEventListener('submit', function(e) {
      e.preventDefault();
      const orderId = orderIdInput.value.trim();
      
      if (orderId) {
        fetchOrderDetails(orderId);
      }
    });
  });
  
  /**
   * Fetch order details from the server
   */
  function fetchOrderDetails(orderId) {
    // Show loading state
    showLoadingState();
    
    // Update URL with order ID for sharing/bookmarking
    const newUrl = window.location.pathname + '?id=' + orderId;
    window.history.pushState({ orderId: orderId }, '', newUrl);
    
    // Fetch order tracking details from the server
    fetch(`/includes/order_tracking.php?order_id=${orderId}`, {
      method: 'GET',
      credentials: 'same-origin', // Send cookies for auth
      headers: {
        'Accept': 'application/json'
      }
    })
    .then(response => {
      if (!response.ok) {
        throw new Error('Order not found or access denied');
      }
      return response.json();
    })
    .then(orderData => {
      // Hide loading state
      hideLoadingState();
      
      if (orderData.error) {
        showOrderNotFound(orderData.error);
        return;
      }
      
      // Display order details
      displayOrderDetails(orderData);
    })
    .catch(error => {
      // Hide loading state
      hideLoadingState();
      
      console.error('Error fetching order details:', error);
      showOrderNotFound('Unable to load order details. Please try again or contact customer support.');
    });
  }
  
  /**
   * Show loading state while fetching order
   */
  function showLoadingState() {
    const loadingOverlay = document.getElementById('loadingOverlay');
    if (loadingOverlay) {
      loadingOverlay.style.display = 'flex';
    }
  }
  
  /**
   * Hide loading state when finished fetching
   */
  function hideLoadingState() {
    const loadingOverlay = document.getElementById('loadingOverlay');
    if (loadingOverlay) {
      loadingOverlay.style.display = 'none';
    }
  }
  
  /**
   * Display order not found message
   */
  function showOrderNotFound(message) {
    // Hide order details if they were previously shown
    document.getElementById('orderDetails').style.display = 'none';
    
    // Show the order not found message
    const notFoundEl = document.getElementById('orderNotFound');
    notFoundEl.style.display = 'block';
    
    // If there's a custom message, update the paragraph text
    if (message) {
      const messageEl = notFoundEl.querySelector('p');
      if (messageEl) {
        messageEl.textContent = message;
      }
    }
  }
  
  /**
   * Display order details on the page
   */
  function displayOrderDetails(orderData) {
    // Hide not found message if it was shown
    document.getElementById('orderNotFound').style.display = 'none';
    
    // Show order details container
    document.getElementById('orderDetails').style.display = 'block';
    
    // Update the order ID display
    document.getElementById('displayOrderId').textContent = orderData.order_id;
    
    // Update order dates
    const orderDate = new Date(orderData.order_date);
    const formattedDate = orderDate.toLocaleDateString('en-US', { 
      year: 'numeric', 
      month: 'long', 
      day: 'numeric' 
    });
    document.getElementById('orderDate').textContent = formattedDate;
    document.getElementById('summaryOrderDate').textContent = formattedDate;
    
    // Update order summary information
    document.getElementById('summaryItems').textContent = `${orderData.item_count} items`;
    document.getElementById('summaryTotal').textContent = `$${orderData.order_total.toFixed(2)}`;
    document.getElementById('summaryPayment').textContent = orderData.payment_method;
    document.getElementById('summaryStatus').textContent = orderData.status;
    
    // Update shipping information
    document.getElementById('shippingName').textContent = orderData.shipping.name;
    document.getElementById('shippingAddress').textContent = orderData.shipping.address;
    document.getElementById('shippingCityState').textContent = `${orderData.shipping.city}, ${orderData.shipping.state} ${orderData.shipping.zip}`;
    document.getElementById('shippingCountry').textContent = orderData.shipping.country;
    
    // Update tracking information if available
    const trackWithCarrierBtn = document.getElementById('trackWithCarrierBtn');
    if (orderData.tracking && orderData.tracking.number) {
      document.getElementById('carrierName').textContent = orderData.tracking.carrier;
      document.getElementById('trackingNumber').textContent = orderData.tracking.number;
      
      // Show and update tracking button if there's a tracking URL
      if (orderData.tracking.url) {
        trackWithCarrierBtn.style.display = 'inline-block';
        trackWithCarrierBtn.href = orderData.tracking.url;
      } else {
        trackWithCarrierBtn.style.display = 'none';
      }
    } else {
      document.getElementById('carrierName').textContent = '-';
      document.getElementById('trackingNumber').textContent = '-';
      trackWithCarrierBtn.style.display = 'none';
    }
    
    // Update estimated delivery date
    if (orderData.estimated_delivery) {
      const estDeliveryDate = new Date(orderData.estimated_delivery);
      const deliveryDay = estDeliveryDate.toLocaleDateString('en-US', { 
        weekday: 'long',
        year: 'numeric', 
        month: 'long', 
        day: 'numeric' 
      });
      document.getElementById('estDeliveryDate').textContent = deliveryDay;
    } else {
      document.getElementById('estDeliveryDate').textContent = 'Calculating...';
    }
    
    // Create and display status timeline
    createStatusTimeline(orderData.status_updates);
  }
  
  /**
   * Create status timeline from status updates
   */
  function createStatusTimeline(statusUpdates) {
    const timelineContainer = document.getElementById('statusTimeline');
    timelineContainer.innerHTML = ''; // Clear any existing timelines
    
    if (!statusUpdates || statusUpdates.length === 0) {
      timelineContainer.innerHTML = '<p>No status updates available yet.</p>';
      return;
    }
    
    // Sort updates by timestamp (newest first)
    statusUpdates.sort((a, b) => new Date(b.timestamp) - new Date(a.timestamp));
    
    // Process each status update
    statusUpdates.forEach((update, index) => {
      const isLatest = index === 0;
      const updateDate = new Date(update.timestamp);
      const timeString = updateDate.toLocaleString('en-US', {
        month: 'short',
        day: 'numeric',
        year: 'numeric',
        hour: 'numeric',
        minute: 'numeric',
        hour12: true
      });
      
      // Create timeline item
      const timelineItem = document.createElement('div');
      timelineItem.className = `timeline-item ${isLatest ? 'current' : 'completed'}`;
      
      // Create timeline icon 
      const iconHtml = getStatusIcon(update.status_type);
      
      timelineItem.innerHTML = `
        <div class="timeline-icon">
          ${iconHtml}
        </div>
        <div class="timeline-content">
          <div class="timeline-title">${update.status}</div>
          <div class="timeline-time">${timeString}</div>
          ${update.description ? `<div class="timeline-description">${update.description}</div>` : ''}
        </div>
      `;
      
      timelineContainer.appendChild(timelineItem);
    });
  }
  
  /**
   * Get appropriate icon for status type
   */
  function getStatusIcon(statusType) {
    switch (statusType) {
      case 'order_placed':
        return '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg>';
      case 'processing':
        return '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 12h-4l-3 9L9 3l-3 9H2"></path></svg>';
      case 'shipped':
        return '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="3" width="15" height="13"></rect><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon><circle cx="5.5" cy="18.5" r="2.5"></circle><circle cx="18.5" cy="18.5" r="2.5"></circle></svg>';
      case 'out_for_delivery':
        return '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="10" r="3"></circle><path d="M12 21.7C17.3 17 20 13 20 10a8 8 0 1 0-16 0c0 3 2.7 7 8 11.7z"></path></svg>';
      case 'delivered':
        return '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>';
      case 'delayed':
        return '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>';
      case 'cancelled':
        return '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>';
      default:
        return '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>';
    }
  }
  
  /**
   * Add event listener for back/forward browser navigation
   */
  window.addEventListener('popstate', function(event) {
    if (event.state && event.state.orderId) {
      // If we navigated back to a page with an order ID, fetch that order
      orderIdInput.value = event.state.orderId;
      fetchOrderDetails(event.state.orderId);
    } else {
      // If we navigated back to the initial page, clear the form and results
      orderIdInput.value = '';
      document.getElementById('orderDetails').style.display = 'none';
      document.getElementById('orderNotFound').style.display = 'none';
    }
  });