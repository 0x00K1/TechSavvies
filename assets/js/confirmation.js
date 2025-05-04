document.addEventListener('DOMContentLoaded', function() {
    // First check for a server-provided order from the URL parameter
    const urlParams = new URLSearchParams(window.location.search);
    const orderIdFromUrl = urlParams.get('order_id');
    
    if (orderIdFromUrl) {
      // Fetch order details from the server
      fetchOrderDetails(orderIdFromUrl);
    } else {
      // Try to get order from session storage as fallback
      loadOrderFromSessionStorage();
    }

    setupTrackOrderButton();
    setupPrintButton();
    setupEmailResend();
  });
  
  function readCookie(name) {
    const match = document.cookie.match(new RegExp('(?:^|; )' + name + '=([^;]*)'));
    return match ? decodeURIComponent(match[1]) : null;
  }
  
  // helper to write a cookie
  function writeCookie(name, value, days = 365) {
    const expires = new Date(Date.now() + days*864e5).toUTCString();
    document.cookie = name + '=' + encodeURIComponent(value) + 
                      '; path=/; expires=' + expires;
  }
  
  /**
   * Fetch order details from the server using the confirmation_process.php endpoint,
   * then ensure orderData.customer has all fields, falling back to sessionStorage if needed.
   */
  function fetchOrderDetails(orderId) {
    // Show loading overlay
    showLoadingState();

    fetch(`/includes/confirmation_process.php?order_id=${orderId}`, {
      method: 'GET',
      credentials: 'same-origin',
      headers: { 'Accept': 'application/json' }
    })
    .then(response => {
      if (!response.ok) throw new Error('Order not found or access denied');
      return response.json();
    })
    .then(orderData => {
      // Hide loading overlay
      hideLoadingState();

      // Clear the cart
      clearCart();

      // Handle server error
      if (orderData.error) {
        showErrorMessage(orderData.error);
        return;
      }

      // Then see if we have a previous version in sessionStorage
      let savedCust = {};
      try {
        const raw = sessionStorage.getItem('lastOrder');
        if (raw) {
          const saved = JSON.parse(raw);
          if (saved.customer) savedCust = saved.customer;
        }
      } catch (_) {
        // ignore JSON parse errors
      }

      const saved = JSON.parse(sessionStorage.getItem('shippingInfo') || '{}');
      const srv  = orderData.customer;
      orderData.customer = {
        name:    srv.name    || saved.name    || '',
        email:   srv.email   || saved.email   || '',
        address: srv.address || saved.address || '',
        city:    srv.city    || saved.city    || '',
        state:   srv.state   || saved.state   || '',
        postal_code: srv.postal_code || saved.postal_code || '',
        country: srv.country || saved.country || ''
      };

      // Re‑save the merged orderData back into sessionStorage
      sessionStorage.setItem('lastOrder', JSON.stringify(orderData));
      sessionStorage.setItem('shippingInfo', JSON.stringify(orderData.customer));

      // Initialize the confirmation page UI
      initializeConfirmationPage(orderData);
    })
    .catch(error => {
      hideLoadingState();
      console.error('Error fetching order details:', error);
      showErrorMessage('Unable to load order details. Please try again later.');

      // Fallback to sessionStorage if available
      loadOrderFromSessionStorage();
    });
  }
  
  /**
   * Try to load order data from session storage (fallback method)
   */
  function loadOrderFromSessionStorage() {
    try {
      const orderStr = sessionStorage.getItem('lastOrder');
      if (orderStr) {
        const order = JSON.parse(orderStr);
        initializeConfirmationPage(order);
      } else {
        // If no order data available, redirect to home or show sample
        handleNoOrderData();
      }
    } catch (e) {
      console.error('Error parsing order from session storage:', e);
      handleNoOrderData();
    }
  }
  
  /**
   * Handle the case when no order data is available
   */
  function handleNoOrderData() {
    // Check if we should show a sample order or redirect
    const urlParams = new URLSearchParams(window.location.search);
    
    showErrorMessage('No order details found. You may not have permission to view this order or it may have been deleted.');
    setTimeout(() => window.location.href = '/', 10000);
  }
  
  /**
   * Show loading state while fetching order
   */
  function showLoadingState() {
    // Create and show loading overlay if it doesn't exist
    let loadingOverlay = document.getElementById('loadingOverlay');
    if (!loadingOverlay) {
      loadingOverlay = document.createElement('div');
      loadingOverlay.id = 'loadingOverlay';
      loadingOverlay.innerHTML = `
        <div class="loading-spinner">
          <div class="spinner"></div>
          <p>Loading your order details...</p>
        </div>
      `;
      loadingOverlay.style.position = 'fixed';
      loadingOverlay.style.top = '0';
      loadingOverlay.style.left = '0';
      loadingOverlay.style.width = '100%';
      loadingOverlay.style.height = '100%';
      loadingOverlay.style.backgroundColor = 'rgba(255, 255, 255, 0.8)';
      loadingOverlay.style.display = 'flex';
      loadingOverlay.style.justifyContent = 'center';
      loadingOverlay.style.alignItems = 'center';
      loadingOverlay.style.zIndex = '9999';
      document.body.appendChild(loadingOverlay);
    } else {
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
   * Show error message to user
   */
  function showErrorMessage(message) {
    // Create error message element
    let errorContainer = document.querySelector('.confirmation-container');
    
    if (errorContainer) {
      // Replace container content with error
      errorContainer.innerHTML = `
        <div class="error-message" style="text-align: center; padding: 40px 20px;">
          <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="#ff4444" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="12" cy="12" r="10"></circle>
            <line x1="12" y1="8" x2="12" y2="12"></line>
            <line x1="12" y1="16" x2="12.01" y2="16"></line>
          </svg>
          <h2 style="color: #333; margin-top: 20px;">Order Information Unavailable</h2>
          <p style="color: #666; margin-top: 10px;">${message}</p>
          <a href="/" class="btn btn-primary" style="display: inline-block; margin-top: 20px; padding: 10px 20px; background-color: #0117ff; color: white; text-decoration: none; border-radius: 4px;">Return to Homepage</a>
        </div>
      `;
    } else {
      // Create a new error container if needed
      const errorDiv = document.createElement('div');
      errorDiv.className = 'error-container';
      errorDiv.innerHTML = `
        <div class="error-message" style="text-align: center; padding: 40px 20px;">
          <h2>Error</h2>
          <p>${message}</p>
          <a href="/" class="btn btn-primary">Return to Homepage</a>
        </div>
      `;
      document.body.appendChild(errorDiv);
    }
  }
  
  /**
   * Track order functionality
   */
  function setupTrackOrderButton() {
    const trackOrderBtn = document.getElementById('trackOrderBtn');
    if (trackOrderBtn) {
      trackOrderBtn.addEventListener('click', function(e) {
        e.preventDefault();
        const orderId = document.getElementById('orderId').textContent;
        window.location.href = `/categories/orders/track?id=${orderId}`;
      });
    }
  }
  
  /**
   * Print functionality
   */
  function setupPrintButton() {
    const actionButtons = document.querySelector('.action-buttons');
    
    if (actionButtons) {
      // Check if print button already exists
      let printButton = actionButtons.querySelector('.print-button');
      
      if (!printButton) {
        printButton = document.createElement('a');
        printButton.href = '#';
        printButton.className = 'btn btn-outline print-button';
        printButton.innerHTML = `
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 8px; vertical-align: text-bottom;">
            <polyline points="6 9 6 2 18 2 18 9"></polyline>
            <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path>
            <rect x="6" y="14" width="12" height="8"></rect>
          </svg>
          Print Order
        `;
        printButton.addEventListener('click', function(e) {
          e.preventDefault();
          window.print();
        });
        actionButtons.appendChild(printButton);
      }
    }
  }
  
  /**
   * Automatically send the confirmation email using order data.
   */
  function sendEmailReceiptAutomatically(order) {
    // Send request to resend email (you must implement /includes/resend_confirmation.php server-side)
    fetch('/includes/resend_confirmation.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({
        order_id: order.orderId,
        email: order.customer.email
      })
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        console.log("Confirmation email sent automatically.");
      } else {
        console.error("Failed to send confirmation email automatically:", data.error);
      }
    })
    .catch(error => {
      console.error("Error sending confirmation email automatically:", error);
    });
  }

  /**
   * Set up the email resend functionality for user-triggered resend.
   */
  function setupEmailResend() {
    const emailElement = document.querySelector('.confirmation-email');
    if (emailElement && !emailElement.querySelector('a')) {
      const resendLink = document.createElement('a');
      resendLink.href = '#';
      resendLink.textContent = '(Resend)';
      resendLink.style.textDecoration = 'underline';
      resendLink.style.color = '#0117ff';
      resendLink.style.marginLeft = '5px';
      resendLink.addEventListener('click', function(e) {
        e.preventDefault();
        const orderId = document.getElementById('orderId').textContent;
        const customerEmail = document.getElementById('customerEmail').textContent;
        
        // Show sending indicator
        this.textContent = '(Sending...)';
        
        // Send request to resend email
        fetch('/includes/resend_confirmation.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
          },
          body: JSON.stringify({
            order_id: orderId,
            email: customerEmail
          })
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            this.textContent = '(Sent!)';
            setTimeout(() => {
              this.textContent = '(Resend)';
            }, 3000);
          } else {
            this.textContent = '(Failed, try again)';
            setTimeout(() => {
              this.textContent = '(Resend)';
            }, 3000);
          }
        })
        .catch(error => {
          console.error('Error resending email:', error);
          this.textContent = '(Failed, try again)';
          setTimeout(() => {
            this.textContent = '(Resend)';
          }, 3000);
        });
      });
      emailElement.appendChild(resendLink);
    }
  }
  
  /**
   * Clear cart when confirmation page is loaded successfully
   */
  function clearCart() {
    // Clear cart cookie
    document.cookie = 'cartItems=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;';
    console.log('Cart cleared after successful order');
  }
  
  // Format currency
  function formatCurrency(amount) {
    return '$' + parseFloat(amount).toFixed(2);
  }
  
  // Initialize the confirmation page with order data
  function initializeConfirmationPage(order) {
    // Generate or set order ID
    const orderId = order.orderId || 'ORD-' + Math.floor(Math.random() * 10000000).toString().padStart(8, '0');
    document.getElementById('orderId').textContent = orderId;
    
    // Set customer email
    document.getElementById('customerEmail').textContent = order.customer.email;
    
    // Format and set the order date
    const orderDate = new Date(order.orderDate || new Date());
    const formattedDate = orderDate.toLocaleDateString('en-US', { 
      year: 'numeric', 
      month: 'long', 
      day: 'numeric' 
    });
    document.getElementById('orderDate').textContent = formattedDate;
    
    // Render ordered items and update summary
    renderOrderedItems(order.items);
    document.getElementById('subtotalAmount').textContent = formatCurrency(order.totals.subtotal);
    document.getElementById('shippingAmount').textContent = formatCurrency(order.totals.shipping);
    document.getElementById('taxAmount').textContent = formatCurrency(order.totals.tax);
    document.getElementById('totalAmount').textContent = formatCurrency(order.totals.total);
    
    // Update shipping address
    document.getElementById('shippingName').textContent = order.customer.name;
    document.getElementById('shippingAddress').textContent = order.customer.address;
    document.getElementById('shippingCityState').textContent = `${order.customer.city}, ${order.customer.state} ${order.customer.postal_code}`;
    document.getElementById('shippingCountry').textContent = order.customer.country;
    
    // Update payment method display
    updatePaymentMethodDisplay(order.payment);
    
    // Check flag before automatically sending the confirmation email.
    const autoEmailSentKey = "autoEmailSent_" + order.orderId;
    if (!sessionStorage.getItem(autoEmailSentKey)) {
      sendEmailReceiptAutomatically(order);
      sessionStorage.setItem(autoEmailSentKey, "true");
    }

    (function saveToPastPurchases() {
      // keep only the 10 most recent
      const existing = JSON.parse(readCookie('pastPurchases') || '[]');
      // we’ll store minimal info: id, date, total
      const entry = {
        orderId:    order.orderId,
        orderDate:  order.orderDate,
        total:      order.totals.total
      };
      // dedupe if they refresh
      const filtered = existing.filter(e => e.orderId !== entry.orderId);
      filtered.unshift(entry);
      if (filtered.length > 10) filtered.pop();
      writeCookie('pastPurchases', JSON.stringify(filtered));
    })();
  }  
  
  // Render ordered items
  function renderOrderedItems(items) {
    const orderedItemsContainer = document.getElementById('orderedItems');
    let html = '';
    
    items.forEach(item => {
      html += `
        <div class="ordered-item">
          <img src="../${item.image}" alt="${item.name}" data-filename="${item.image}" onerror="handleImageError(this, this.getAttribute('data-filename'))">
          <div class="ordered-item-info">
            <div class="ordered-item-name">${item.name}</div>
            ${item.color || item.size ? `
              <div class="ordered-item-options">
                ${item.color ? `${item.color}` : ''}
                ${item.color && item.size ? ' / ' : ''}
                ${item.size ? `${item.size}` : ''}
              </div>
            ` : ''}
            <div class="ordered-item-price">${formatCurrency(item.price)}</div>
          </div>
          <div class="ordered-item-quantity">
            ${item.quantity}
            <span>Qty</span>
          </div>
        </div>
      `;
    });
    
    orderedItemsContainer.innerHTML = html;
  }

  /**
   * Handle payment method display update with proper icon
   */
  function updatePaymentMethodDisplay(payment) {
    const paymentMethodDisplay = document.getElementById('paymentMethodDisplay');
    const censoredCardNumber = document.getElementById('censoredCardNumber');
    
    if (!paymentMethodDisplay || !payment) return;
    
    if (payment.method === 'credit_card') {
      // Get the last 4 digits of the card number
      const lastFour = payment.cardNumber ? 
        payment.cardNumber.replace(/\D/g, '').slice(-4) : 
        payment.transactionId?.slice(-4) || '4567';
      
      // Update the display
      if (censoredCardNumber) {
        censoredCardNumber.textContent = `**** **** **** ${lastFour}`;
      }
      
      // Add card icon based on first digit if available
      let cardIcon = getCardIcon(payment.cardNumber);
      paymentMethodDisplay.querySelector('svg').outerHTML = cardIcon;
      
    } else if (payment.method === 'paypal') {
      // Show PayPal icon and info
      paymentMethodDisplay.innerHTML = `
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="#0070ba" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M17.5 7H20.5C21.3 7 22 7.7 22 8.5V15.5C22 16.3 21.3 17 20.5 17H17.5"></path>
          <path d="M12 7H9.5C8.7 7 8 7.7 8 8.5V15.5C8 16.3 8.7 17 9.5 17H12"></path>
          <path d="M2 7H5.5C6.3 7 7 7.7 7 8.5V15.5C7 16.3 6.3 17 5.5 17H2"></path>
        </svg>
        <div>
          <div class="payment-method-name">PayPal</div>
          <div class="censored-card">${payment.email || payment.transactionId || 'Transaction completed'}</div>
        </div>
      `;
    } else {
      // Default for other payment methods
      paymentMethodDisplay.innerHTML = `
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect>
          <line x1="1" y1="10" x2="23" y2="10"></line>
        </svg>
        <div>
          <div class="payment-method-name">${payment.method || 'Payment'}</div>
          <div class="censored-card">Transaction ID: ${payment.transactionId || 'Completed'}</div>
        </div>
      `;
    }
  }

  /**
   * Get appropriate card icon based on card number
   */
  function getCardIcon(cardNumber) {
    if (!cardNumber) return `
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect>
        <line x1="1" y1="10" x2="23" y2="10"></line>
      </svg>
    `;
    
    // Get first digit
    const firstDigit = cardNumber.replace(/\D/g, '').charAt(0);
    
    // Visa starts with 4
    if (firstDigit === '4') {
      return `
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="#1434CB" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <rect x="1" y="4" width="22" height="16" rx="2" ry="2" fill="#f7f7f7"></rect>
          <polygon points="5 12 7 7 9 12" fill="#1434CB" stroke-width="0"></polygon>
          <line x1="6" y1="10" x2="8" y2="10" stroke="#1434CB"></line>
          <path d="M14 9v6M10 9l4 6M18 9v6" stroke="#1434CB"></path>
        </svg>
      `;
    }
    
    // Mastercard starts with 5
    if (firstDigit === '5') {
      return `
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <rect x="1" y="4" width="22" height="16" rx="2" ry="2" fill="#f7f7f7" stroke="#dfdfdf"></rect>
          <circle cx="9" cy="12" r="3" fill="#EA001B" stroke-width="0"></circle>
          <circle cx="15" cy="12" r="3" fill="#FFA200" stroke-width="0"></circle>
          <path d="M12 12m-1.5,0a1.5,1.5 0 1,0 3,0a1.5,1.5 0 1,0 -3,0" fill="#FF5F00" stroke-width="0"></path>
        </svg>
      `;
    }
    
    // American Express starts with 3
    if (firstDigit === '3') {
      return `
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <rect x="1" y="4" width="22" height="16" rx="2" ry="2" fill="#016FD0" stroke="#016FD0"></rect>
          <path d="M5 12l1-2.5h2L9 12m-4 0l1 2.5h2L9 12M15 9h3.5v1.5H15V12h3.5v1.5H15V15" stroke="#FFFFFF"></path>
        </svg>
      `;
    }
    
    // Default card icon
    return `
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect>
        <line x1="1" y1="10" x2="23" y2="10"></line>
      </svg>
    `;
  }