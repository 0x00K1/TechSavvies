// Function to get cart from cookie
function getCartFromCookie() {
    let cartStr = readCookie("cartItems");
    if (!cartStr) {
      return [];
    }
    try {
      return JSON.parse(cartStr);
    } catch (e) {
      console.error("Invalid cart cookie JSON:", e);
      return [];
    }
  }
  
  function readCookie(name) {
    let match = document.cookie.match(new RegExp('(^| )' + name + '=([^;]+)'));
    return match ? decodeURIComponent(match[2]) : null;
  }
  
  // Format currency
  function formatCurrency(amount) {
    return '$' + parseFloat(amount).toFixed(2);
  }
  
  // Render cart preview items
  function renderCartPreview() {
    const cart = getCartFromCookie();
    const cartPreviewItems = document.getElementById('cartPreviewItems');
    
    if (!cartPreviewItems) return;
    
    if (cart.length === 0) {
      // Show empty cart message
      document.getElementById('emptyCartMessage').style.display = 'block';
      document.getElementById('checkoutMainContent').style.display = 'none';
      return;
    }
    
    // Hide empty cart message and show checkout content
    document.getElementById('emptyCartMessage').style.display = 'none';
    document.getElementById('checkoutMainContent').style.display = 'grid';
    
    let previewHTML = '';
    
    cart.forEach(item => {
      previewHTML += `
        <div class="cart-preview-item">
          <img src="${item.image}" alt="${item.name}" class="item-image">
          <div class="item-details">
            <div class="item-name">${item.name}</div>
            ${item.color || item.size ? `
              <div class="item-options">
                ${item.color ? `Color: ${item.color}` : ''}
                ${item.color && item.size ? ' | ' : ''}
                ${item.size ? `Size: ${item.size}` : ''}
              </div>
            ` : ''}
            <div class="item-meta">
              <div>Quantity: ${item.quantity}</div>
              <div class="item-quantity-price">${formatCurrency(item.price)} each</div>
            </div>
          </div>
        </div>
      `;
    });
    
    cartPreviewItems.innerHTML = previewHTML;
  }
  
  // Render order summary
  function renderOrderSummary() {
    const cart = getCartFromCookie();
    const orderSummaryItems = document.getElementById('orderSummaryItems');
    
    if (!orderSummaryItems) return;
    
    let summaryHTML = '';
    let subtotal = 0;
    
    cart.forEach(item => {
      const itemTotal = item.price * item.quantity;
      subtotal += itemTotal;
      
      summaryHTML += `
        <div class="cart-item">
          <img src="${item.image}" alt="${item.name}">
          <div class="cart-item-info">
            <p class="item-name">${item.name}</p>
            ${item.color || item.size ? `
              <p class="item-options">
                ${item.color ? `${item.color}` : ''}
                ${item.color && item.size ? ' / ' : ''}
                ${item.size ? `${item.size}` : ''}
              </p>
            ` : ''}
            <p class="item-price">${formatCurrency(item.price)}</p>
          </div>
          <div class="cart-quantity">${item.quantity}</div>
        </div>
      `;
    });
    
    orderSummaryItems.innerHTML = summaryHTML;
    
    // Calculate totals
    const shipping = subtotal > 0 ? 10.00 : 0.00;
    const tax = subtotal * 0.08; // 8% tax
    const total = subtotal + shipping + tax;
    
    // Update totals
    document.getElementById('subtotalAmount').textContent = formatCurrency(subtotal);
    document.getElementById('shippingAmount').textContent = formatCurrency(shipping);
    document.getElementById('taxAmount').textContent = formatCurrency(tax);
    document.getElementById('totalAmount').textContent = formatCurrency(total);
  }
  
  // Toggle credit card fields based on selected payment method
  function toggleCreditCardFields() {
    const paymentMethod = document.querySelector('input[name="payment_method"]:checked').value;
    const creditCardFields = document.getElementById('creditCardFields');
    
    if (paymentMethod === 'credit_card') {
      creditCardFields.style.display = 'block';
    } else {
      creditCardFields.style.display = 'none';
    }
  }
  
  // Validate credit card fields
  function validateCreditCardFields() {
    const paymentMethod = document.querySelector('input[name="payment_method"]:checked').value;
    
    if (paymentMethod !== 'credit_card') {
      return true;
    }
    
    const cardName = document.getElementById('cardName').value.trim();
    const cardNumber = document.getElementById('cardNumber').value.trim();
    const expDate = document.getElementById('expDate').value.trim();
    const cvv = document.getElementById('cvv').value.trim();
    
    if (!cardName) {
      showToast('Please enter the name on the card.', 'error');
      return false;
    }
    
    if (!cardNumber || cardNumber.length < 13) {
      showToast('Please enter a valid card number.', 'error');
      return false;
    }
    
    if (!expDate || !expDate.match(/^\d{2}\/\d{2}$/)) {
      showToast('Please enter a valid expiration date (MM/YY)', 'error');
      return false;
    }
    
    if (!cvv || !cvv.match(/^\d{3,4}$/)) {
      showToast('Please enter a valid security code (CVV).', 'error');
      return false;
    }
    
    return true;
  }
  
  // Handle form submission
  function handleFormSubmission(e) {
    e.preventDefault();
  
    if (!validateCreditCardFields()) {
      return;
    }
  
    const cart = getCartFromCookie();
    if (cart.length === 0) {
      showToast('Your cart is empty. Please add items to your cart before checking out.', 'error');
      return;
    }
  
    // Gather customer details from the page
    const customerInfo = {
      name:    document.getElementById('customerName').textContent.trim(),
      email:   document.getElementById('customerEmail').textContent.trim(),
      address: document.getElementById('customerAddress').textContent.trim(),
      city:    document.getElementById('customerCity').textContent.trim(),
      state:   document.getElementById('customerState').textContent.trim(),
      postal_code:     document.getElementById('customerZip').textContent.trim(),
      country: document.getElementById('customerCountry').textContent.trim()
    };    
  
    // Check if any required customer detail is missing
    if (
      !customerInfo.name ||
      !customerInfo.email ||
      !customerInfo.address ||
      !customerInfo.city ||
      !customerInfo.state ||
      !customerInfo.postal_code ||
      !customerInfo.country
    ) {
      showToast('Please complete your shipping address details in your profile before checking out.', 'error');
      return;
    }
  
    // Show loading overlay
    showLoadingOverlay();
  
    // Gather payment method and details
    const paymentMethod = document.querySelector('input[name="payment_method"]:checked').value;
    let paymentDetails = { method: paymentMethod };
    if (paymentMethod === 'credit_card') {
      paymentDetails.cardName = document.getElementById('cardName').value.trim();
      paymentDetails.cardNumber = document.getElementById('cardNumber').value.trim();
      paymentDetails.expDate = document.getElementById('expDate').value.trim();
      paymentDetails.cvv = document.getElementById('cvv').value.trim();
    }
  
    // Calculate totals from the cart
    let subtotal = 0;
    cart.forEach(item => {
      subtotal += item.price * item.quantity;
    });
    const shipping = subtotal > 0 ? 10.00 : 0.00;
    const tax = subtotal * 0.08; // 8% tax
    const total = subtotal + shipping + tax;
  
    // Create payment request object
    const paymentRequest = {
      order_id: window.orderId, // this should be set globally # We set window.orderId = <?= json_encode($orderId) ?>; in the index
      payment_method: paymentMethod,
      amount: total.toFixed(2),
      card_details: paymentMethod === 'credit_card' ? {
        name: paymentDetails.cardName,
        number: paymentDetails.cardNumber,
        exp_date: paymentDetails.expDate,
        cvv: paymentDetails.cvv
      } : null,
      customer: customerInfo
    };
  
    // Send payment request to the server
    fetch('/includes/payment_process.php', {
      method: 'POST',
      credentials: 'include',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(paymentRequest)
    })
      .then(response => response.json())
      .then(data => {
        hideLoadingOverlay();
        if (data.error) {
          showToast(data.error, 'error');
        } else {
          // Payment successful: cancel auto-cancellation
          window.finalizeOrder();
  
          // Clear cart cookie
          document.cookie = 'cartItems=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;';
  
          showToast('Payment successful! ...', 'success');
  
          // Store order info in session storage for confirmation page
          const order = {
            items: cart,
            customer: customerInfo,
            payment: paymentDetails,
            totals: {
              subtotal: subtotal.toFixed(2),
              shipping: shipping.toFixed(2),
              tax: tax.toFixed(2),
              total: total.toFixed(2)
            },
            orderDate: new Date().toISOString(),
            orderStatus: 'paid'
          };
          sessionStorage.setItem('lastOrder', JSON.stringify(order));
          sessionStorage.setItem('shippingInfo', JSON.stringify(customerInfo));
  
          // Redirect after a short delay
          setTimeout(() => {
            window.location.href = data.redirect_url || '/categories/confirmation?order_id=' + window.orderId;
          }, 2000);
        }
      })
      .catch(error => {
        hideLoadingOverlay();
        showToast('An error occurred while processing your payment. Please try again.', 'error');
        console.error('Payment error:', error);
      });
  }
  
  // Handle "Edit Cart" button
  function handleEditCart() {
    // Redirect to cart page
    window.location.href = '/categories/cart';
  }
  
  // Handle "Edit Address" button
  function handleEditAddress() {
    window.location.href = '/profile.php#address';
  }
  
  // Format card number with spaces
  function formatCardNumber(e) {
    const input = e.target;
    let value = input.value.replace(/\s+/g, '').replace(/[^0-9]/gi, '');
    let formattedValue = '';
    
    for (let i = 0; i < value.length; i++) {
      if (i > 0 && i % 4 === 0) {
        formattedValue += ' ';
      }
      formattedValue += value[i];
    }
    
    input.value = formattedValue;
  }
  
  // Format expiration date
  function formatExpDate(e) {
    const input = e.target;
    let value = input.value.replace(/\D/g, '');
    
    if (value.length > 2) {
      input.value = value.slice(0, 2) + '/' + value.slice(2, 4);
    } else {
      input.value = value;
    }
  }
  
  // Initialize page
  document.addEventListener('DOMContentLoaded', function() {
    const addForm = document.getElementById('addAddressForm');
    if (!addForm) return;

    addForm.addEventListener('submit', e => {
      e.preventDefault();
      // validate fields
      if (!validateAddressForm('addAddressForm')) return;

      // grab all the inputs
      const name          = document.getElementById('customerName').textContent.trim();
      const country       = document.getElementById('country').value.trim();
      const addr1         = document.getElementById('address_line1').value.trim();
      const addr2         = document.getElementById('address_line2').value.trim();
      const city          = document.getElementById('city').value.trim();
      const state         = document.getElementById('state').value.trim();
      const postal_code   = document.getElementById('postal_code').value.trim();

      // build the Address line
      const address = addr1 + (addr2 ? ', ' + addr2 : '');

      // populate the spans in the billing-details list
      document.getElementById('customerAddress').textContent = address;
      document.getElementById('customerCity')   .textContent = city;
      document.getElementById('customerState')  .textContent = state;
      document.getElementById('customerZip')    .textContent = postal_code;
      document.getElementById('customerCountry').textContent = country;

      // persist to sessionStorage for later pages
      sessionStorage.setItem('shippingInfo', JSON.stringify({
        name,
        address,
        city,
        state,
        postal_code,
        country
      }));

      // switch "Create Address" button to "Edit Address"
      const btn = document.getElementById('createAddressBtn');
      btn.textContent = 'Edit Address';
      btn.id = 'editBillingBtn';
      btn.onclick = () => openEditPopup();

      // hide the popup
      closePopup('add-popup');
    });
    
    renderCartPreview();
    renderOrderSummary();
    toggleCreditCardFields();
    
    // Event listeners
    const paymentMethods = document.querySelectorAll('input[name="payment_method"]');
    paymentMethods.forEach(method => {
      method.addEventListener('change', toggleCreditCardFields);
    });
    
    const checkoutForm = document.getElementById('checkoutForm');
    if (checkoutForm) {
      checkoutForm.addEventListener('submit', handleFormSubmission);
    }
    
    const editCartBtn = document.getElementById('editCartBtn');
    if (editCartBtn) {
      editCartBtn.addEventListener('click', handleEditCart);
    }
    
    const editBillingBtn = document.getElementById('editBillingBtn');
    if (editBillingBtn) {
      editBillingBtn.addEventListener('click', handleEditAddress);
    }
    
    // Format card number with spaces
    const cardNumberInput = document.getElementById('cardNumber');
    if (cardNumberInput) {
      cardNumberInput.addEventListener('input', formatCardNumber);
    }
    
    // Format expiration date
    const expDateInput = document.getElementById('expDate');
    if (expDateInput) {
      expDateInput.addEventListener('input', formatExpDate);
    }
    
    // Load customer details
    loadCustomerDetails();
  });
  
  // Load customer details
  function loadCustomerDetails() {
    fetch('/includes/get_customer_details.php', { credentials: 'include' })
      .then(r => r.json())
      .then(data => {
        if (!data.success) return;
  
        const c = data.customer;
  
        const mappings = [
          ['customerName',      c.name],
          ['customerEmail',     c.email],
          ['customerAddress',   [c.address_line1, c.address_line2].filter(Boolean).join(', ')],
          ['customerCity',      c.city],
          ['customerState',     c.state],
          ['customerZip',       c.postal_code],
          ['customerCountry',   c.country],
        ];
  
        mappings.forEach(([id, value]) => {
          const el = document.getElementById(id);
          if (el) el.textContent = value;
        });
      });
  }  
  
  (function() {
    // Use the global variable set in the HTML
    const orderId = window.orderId;
    
    // Set a timer (10 minutes = 600000 ms) to cancel the order automatically.
    const cancelTimeout = setTimeout(() => {
      cancelOrder(orderId);
    }, 600000);
    
    // Listen for page unload. Use the Beacon API to send the cancellation if possible.
    window.addEventListener("beforeunload", function(e) {
      cancelOrder(orderId, true);
    });
    
    function cancelOrder(orderId, useBeacon = false) {
      const url = "/includes/cancel_order.php?order_id=" + encodeURIComponent(orderId);
      if (useBeacon && navigator.sendBeacon) {
        navigator.sendBeacon(url);
      } else {
        fetch(url, { method: "GET" })
          .then(response => response.text())
          .then(result => console.log("Cancellation result:", result))
          .catch(err => console.error("Cancellation error:", err));
      }
    }
    
    // When the user completes the purchase, cancel the timeout.
    window.finalizeOrder = function() {
      clearTimeout(cancelTimeout);
    };
  })();
  
  function showToast(message, type = 'success') {
    const toast = document.getElementById('toast');
    const toastContent = document.getElementById('toastContent');
    const toastContainer = document.getElementById('toastContainer');
    
    toastContent.textContent = message;
    toast.className = 'toast ' + type;
    toastContainer.style.display = 'block';
    
    // Hide after 7 seconds
    setTimeout(() => {
      toastContainer.style.display = 'none';
    }, 7000);
  }
  
  document.getElementById('toastClose').addEventListener('click', function() {
    document.getElementById('toastContainer').style.display = 'none';
  });
  
  function showLoadingOverlay() {
    document.getElementById('loadingOverlay').classList.add('active');
  }
  
  function hideLoadingOverlay() {
    document.getElementById('loadingOverlay').classList.remove('active');
  }  