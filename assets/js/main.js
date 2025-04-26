// 1) Global authentication state
var isAuthenticated = false;

// 2) Global references to modal elements (may be null if not on page)
let authModal       = null;
let authStep1       = null;
let authStep2       = null;
let authEmailInput  = null;
let authOTPInput    = null;
let closeModalBtn   = null;
let sendOtpBtn      = null;
let verifyOtpBtn    = null;

/**
 * Opens the auth modal if present.
 */
window.openAuthModal = function() {
  if (!authModal || !authStep1 || !authStep2 || !authEmailInput || !authOTPInput) {
    console.warn("Auth modal elements not found on this page.");
    return;
  }
  // Clear inputs
  authEmailInput.value = "";
  authOTPInput.value   = "";
  // Show step 1
  authStep1.style.display = "block";
  authStep2.style.display = "none";
  // Display modal
  authModal.style.display  = "flex";
};

/**
 * Closes the auth modal if present.
 */
window.closeAuthModal = function() {
  if (authModal) {
    authModal.style.display = "none";
  }
};

document.addEventListener("DOMContentLoaded", function () {
  // --- Back-to-Top Button ---
  function createBackToTopButton() {
    const btn = document.createElement("button");
    btn.id = "backToTop";
    btn.textContent = "↑";
    btn.style.position = "fixed";
    btn.style.bottom = "30px";
    btn.style.right = "30px";
    btn.style.width = "50px";
    btn.style.height = "50px";
    btn.style.borderRadius = "50%";
    btn.style.border = "none";
    btn.style.background = "var(--primary-color)";
    btn.style.color = "#fff";
    btn.style.fontSize = "24px";
    btn.style.cursor = "pointer";
    btn.style.boxShadow = "0 4px 8px rgba(0,0,0,0.3)";
    btn.style.display = "none";
    btn.style.zIndex = "1000";
    document.body.appendChild(btn);

    // Smooth scroll to top on click
    btn.addEventListener("click", function () {
      window.scrollTo({ top: 0, behavior: "smooth" });
    });

    // Show/hide button based on scroll position
    window.addEventListener("scroll", function () {
      const scrollY = window.pageYOffset;
      const windowHeight = window.innerHeight;
      const documentHeight = document.documentElement.scrollHeight;

      if (scrollY > 300 && scrollY + windowHeight < documentHeight - 50) {
        btn.style.display = "block";
      } else {
        btn.style.display = "none";
      }
    });
  }
  createBackToTopButton();

  // --- Animate Elements on Scroll ---
  const animateElements = document.querySelectorAll(".animate-on-scroll");
  if ("IntersectionObserver" in window) {
    const observer = new IntersectionObserver(
      (entries, observerInstance) => {
        entries.forEach((entry) => {
          if (entry.isIntersecting) {
            entry.target.classList.add("animated");
            observerInstance.unobserve(entry.target);
          }
        });
      },
      { threshold: 0.1 }
    );
    animateElements.forEach((el) => observer.observe(el));
  } else {
    // Fallback for browsers without IntersectionObserver
    animateElements.forEach((el) => el.classList.add("animated"));
  }

  // --- Pull references to auth modal elements if present ---
  authModal      = document.getElementById("authModal");
  authStep1      = document.getElementById("authStep1");
  authStep2      = document.getElementById("authStep2");
  authEmailInput = document.getElementById("authEmail");
  authOTPInput   = document.getElementById("authOTP");
  closeModalBtn  = document.getElementById("closeModal");
  sendOtpBtn     = document.getElementById("sendOtpBtn");
  verifyOtpBtn   = document.getElementById("verifyOtpBtn");
  const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

  // Track OTP verification failures
  let otpFailureCount = 0;

  // --- Check session to set isAuthenticated ---
  fetch("/includes/check_session.php", {
    credentials: "include"
  })
    .then(response => response.json())
    .then(data => {
      if (data.loggedIn) {
        isAuthenticated = true;
        // Possibly display data.user info in the UI
      } else {
        isAuthenticated = false;
      }
      updateAccountLink();
    })
    .catch(error => console.error("Session check error:", error));

  // --- If the modal elements exist, attach event listeners ---
  if (authModal && authStep1 && authStep2 && authEmailInput && authOTPInput) {
    // Close modal button
    if (closeModalBtn) {
      closeModalBtn.addEventListener("click", closeAuthModal);
    }

    // Close modal if clicking outside
    window.addEventListener("click", function (event) {
      if (event.target === authModal) {
        closeAuthModal();
      }
    });

    // Send OTP
    if (sendOtpBtn) {
      sendOtpBtn.addEventListener("click", function () {
        const email = authEmailInput.value.trim();
        if (!email) {
          alert("Please enter a valid email.");
          return;
        }

        // Show spinner
        toggleButtonLoading(sendOtpBtn, true);

        fetch("/includes/send_otp.php", {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify({ email: email, csrf_token: csrfToken }),
        })
          .then((response) => response.json())
          .then((data) => {
            toggleButtonLoading(sendOtpBtn, false);
            if (data.success) {
              authStep1.style.display = "none";
              authStep2.style.display = "block";
            } else {
              alert("Error: " + data.error);
            }
          })
          .catch((error) => {
            toggleButtonLoading(sendOtpBtn, false);
            console.error("Error sending OTP:", error);
            alert("Failed to send OTP. Please try again.");
          });
      });
    }

    // Verify OTP
    if (verifyOtpBtn) {
      verifyOtpBtn.addEventListener("click", function () {
        const email = authEmailInput.value.trim();
        const otp   = authOTPInput.value.trim();
        if (!otp) {
          alert("Please enter the OTP.");
          return;
        }

        toggleButtonLoading(verifyOtpBtn, true);

        fetch("/includes/verify_otp.php", {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify({
            email: email,
            otp: otp,
            csrf_token: csrfToken
          }),
          credentials: 'include'
        })
          .then((response) => response.json())
          .then((data) => {
            toggleButtonLoading(verifyOtpBtn, false);
            if (data.success) {
              // OTP verification succeeded
              otpFailureCount = 0;
              isAuthenticated = true;
              updateAccountLink();
              closeAuthModal();
              window.location.reload();
            } else {
              // OTP verification failed
              otpFailureCount++;
              alert("Verification failed: " + data.error);
              if (otpFailureCount >= 3) {
                alert("Too many attempts!");
                closeAuthModal();
                otpFailureCount = 0; 
              }
            }
          })
          .catch((error) => {
            toggleButtonLoading(verifyOtpBtn, false);
            console.error("Error verifying OTP:", error);
            alert("OTP verification failed. Please try again.");
          });
      });
    }

    // Clicking account icon logic
    document.addEventListener("click", function (e) {
      const accountLink = document.getElementById("accountLink");
      const accountIcon = document.getElementById("accountIcon");
      const accountDropdown = document.getElementById("accountDropdown");
      if (accountIcon && accountIcon.contains(e.target)) {
        if (isAuthenticated) {
          accountLink.classList.toggle("show-dropdown");
        } else {
          openAuthModal();
        }
      } else {
        if (accountDropdown && !accountDropdown.contains(e.target)) {
          accountLink.classList.remove("show-dropdown");
        }
      }
    });
  }

  // --- Build or attach cart sidebar ---
  const cartSidebarHTML = `
  <div id="cartSidebar">
    <button id="closeCart">×</button>
    <h2>Your Shopping Cart</h2>
    <div id="cartContent">
      <p>Your cart is currently empty.</p>
    </div>
  </div>
  `;
  document.body.insertAdjacentHTML("beforeend", cartSidebarHTML);

  // Set up event listeners for cart sidebar
  const cartLink     = document.getElementById("cartLink");
  const cartSidebar  = document.getElementById("cartSidebar");
  const closeCartBtn = document.getElementById("closeCart");

  if (cartLink && cartSidebar && closeCartBtn) {
  cartLink.addEventListener("click", function (e) {
    e.preventDefault();
    cartSidebar.classList.add("active");
  });

  closeCartBtn.addEventListener("click", function () {
    cartSidebar.classList.remove("active");
  });

  document.addEventListener("mousedown", function(e) {
    const sidebarClicked = cartSidebar.contains(e.target);
    const cartLinkClicked = cartLink.contains(e.target);

    // If it's neither the sidebar nor the link, close it
    if (!sidebarClicked && !cartLinkClicked) {
      cartSidebar.classList.remove("active");
    }
  });
  }

  // Create a small "badge" element in the cart icon
  const cartIconWrapper = document.getElementById("cartIconWrapper");
  let cartBadge = null;
  if (cartIconWrapper) {
  cartBadge = document.createElement("span");
  cartBadge.id = "cartBadge";
  cartBadge.style.position = "absolute";
  cartBadge.style.top = "-8px";
  cartBadge.style.right = "-8px";
  cartBadge.style.backgroundColor = "#ff3e3e";
  cartBadge.style.color = "#fff";
  cartBadge.style.padding = "2px 6px";
  cartBadge.style.borderRadius = "50%";
  cartBadge.style.fontSize = "12px";
  cartBadge.style.fontWeight = "bold";
  cartBadge.style.display = "none"; // hidden by default
  cartBadge.style.boxShadow = "0 2px 4px rgba(0,0,0,0.2)";
  cartIconWrapper.style.position = "relative";
  cartIconWrapper.appendChild(cartBadge);
  }

  // We'll attach a function to read + render the cart
  function renderCartItems() {
  let cart = getCartFromCookie();
  updateCartBadge(cart);

  const cartContent = document.getElementById("cartContent");
  if (!cartContent) return;

  if (!cart.length) {
    cartContent.innerHTML = `
      <div class="empty-cart">
       <svg width="82px" height="82px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" stroke="#141414"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M7.5 18C8.32843 18 9 18.6716 9 19.5C9 20.3284 8.32843 21 7.5 21C6.67157 21 6 20.3284 6 19.5C6 18.6716 6.67157 18 7.5 18Z" stroke="#6e6e6e" stroke-width="1.5"></path> <path d="M16.5 18.0001C17.3284 18.0001 18 18.6716 18 19.5001C18 20.3285 17.3284 21.0001 16.5 21.0001C15.6716 21.0001 15 20.3285 15 19.5001C15 18.6716 15.6716 18.0001 16.5 18.0001Z" stroke="#6e6e6e" stroke-width="1.5"></path> <path d="M11.5 12.5L14.5 9.5M14.5 12.5L11.5 9.5" stroke="#6e6e6e" stroke-width="1.5" stroke-linecap="round"></path> <path d="M2 3L2.26121 3.09184C3.5628 3.54945 4.2136 3.77826 4.58584 4.32298C4.95808 4.86771 4.95808 5.59126 4.95808 7.03836V9.76C4.95808 12.7016 5.02132 13.6723 5.88772 14.5862C6.75412 15.5 8.14857 15.5 10.9375 15.5H12M16.2404 15.5C17.8014 15.5 18.5819 15.5 19.1336 15.0504C19.6853 14.6008 19.8429 13.8364 20.158 12.3075L20.6578 9.88275C21.0049 8.14369 21.1784 7.27417 20.7345 6.69708C20.2906 6.12 18.7738 6.12 17.0888 6.12H11.0235M4.95808 6.12H7" stroke="#6e6e6e" stroke-width="1.5" stroke-linecap="round"></path> </g></svg>
        <p>Your cart is currently empty.</p>
        <button id="continueShopping" class="continue-btn">Continue Shopping</button>
      </div>
    `;
    
    const continueBtn = document.getElementById("continueShopping");
    if (continueBtn) {
      continueBtn.addEventListener("click", function() {
        const currentPath = window.location.pathname;
        if (currentPath === "/categories" || currentPath === "/categories/products/") {
          // If on the categories pages, just hide the sidebar.
          cartSidebar.classList.remove("active");
        } else {
          // Otherwise, redirect to the shop section.
          window.location.href = "/#shop";
        }
      });
    }    
    return;
  }

  let html = `<div class="cart-items-container">`;
  let subtotal = 0;

  cart.forEach((item, index) => {
    const itemTotal = item.price * item.quantity;
    subtotal += itemTotal;

    html += `
      <div class="cart-item">
        <!-- Right-aligned image -->
        <div class="cart-item-image">
          <img src="${item.image}" alt="${item.name}" loading="lazy">
        </div>
        
        <!-- Left-aligned content -->
        <div class="cart-item-details">
          <div class="item-header">${item.name}</div>
          ${item.color ? `<div class="item-attribute">Color: ${item.color}</div>` : ""}
          ${item.size ? `<div class="item-attribute">Size: ${item.size}</div>` : ""}
          <div class="item-price">$${item.price.toFixed(2)}</div>
          
          <div class="quantity-controls">
            <button type="button" class="qty-btn" data-index="${index}" data-action="decrement">−</button>
            <input
              type="text"
              value="${item.quantity}"
              readonly
              class="qty-input"
            />
            <button type="button" class="qty-btn" data-index="${index}" data-action="increment">+</button>
          </div>
          
          <div class="item-total">
            Item Total: <span>$${itemTotal.toFixed(2)}</span>
          </div>
        </div>
      </div>
      <div class="cart-item-divider"></div>
    `;
  });

  html += `
    </div>
    <div class="cart-footer">
      <div class="cart-subtotal">
        <span>Subtotal:</span>
        <span>$${subtotal.toFixed(2)}</span>
      </div>
      <button id="checkoutBtn">Proceed to Checkout</button>
      <a id="continue-shopping-a" class="continue-shopping-a" href="#">Continue Shopping</a>
      <form id="checkoutForm" action="/includes/checkout_process.php" method="POST" style="display: none;"></form>
      <button id="emptyCartBtn" class="empty-cart-btn" aria-label="Empty Cart">
      <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
        <g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M20.5001 6H3.5" stroke="#000000" stroke-width="1.5" stroke-linecap="round"></path> <path d="M6.5 6C6.55588 6 6.58382 6 6.60915 5.99936C7.43259 5.97849 8.15902 5.45491 8.43922 4.68032C8.44784 4.65649 8.45667 4.62999 8.47434 4.57697L8.57143 4.28571C8.65431 4.03708 8.69575 3.91276 8.75071 3.8072C8.97001 3.38607 9.37574 3.09364 9.84461 3.01877C9.96213 3 10.0932 3 10.3553 3H13.6447C13.9068 3 14.0379 3 14.1554 3.01877C14.6243 3.09364 15.03 3.38607 15.2493 3.8072C15.3043 3.91276 15.3457 4.03708 15.4286 4.28571L15.5257 4.57697C15.5433 4.62992 15.5522 4.65651 15.5608 4.68032C15.841 5.45491 16.5674 5.97849 17.3909 5.99936C17.4162 6 17.4441 6 17.5 6" stroke="#000000" stroke-width="1.5"></path> <path d="M18.3735 15.3991C18.1965 18.054 18.108 19.3815 17.243 20.1907C16.378 21 15.0476 21 12.3868 21H11.6134C8.9526 21 7.6222 21 6.75719 20.1907C5.89218 19.3815 5.80368 18.054 5.62669 15.3991L5.16675 8.5M18.8334 8.5L18.6334 11.5" stroke="#000000" stroke-width="1.5" stroke-linecap="round"></path> </g>
      </svg>
    </button>
    </div>
  `;

  cartContent.innerHTML = html;

  const continueA = document.getElementById("continue-shopping-a");
  if (continueA) {
    const currentPath = window.location.pathname;

    if (currentPath === "/categories/cart" || currentPath === "/categories/checkouts") {
      continueA.innerText = "Continue Shopping";
      continueA.href = "/#shop";
    } else {
      continueA.innerText = "Cart";
      continueA.href = "/categories/cart";
    }

    continueA.addEventListener("click", function (e) {
      e.preventDefault(); // prevent default navigation to use href set above
      window.location.href = continueA.href;
    });
  }

  // Re-attach event listeners to the +/- buttons
  const qtyButtons = cartContent.querySelectorAll(".qty-btn");
  qtyButtons.forEach(btn => {
    btn.addEventListener("click", function(e) {
      const idx = parseInt(e.target.getAttribute("data-index"));
      const action = e.target.getAttribute("data-action");
      updateItemQuantity(idx, action);
    });
  });

  // Checkout button
  const checkoutBtn = document.getElementById("checkoutBtn");
  const checkoutForm = document.getElementById("checkoutForm");
  if (checkoutBtn) {
    checkoutBtn.addEventListener("click", function () {
      const cart = getCartFromCookie();
      if (cart.length === 0) {
        showToast('Your cart is empty. Please add items before checkout.', 'error');
        return;
      }
  
      if (!isAuthenticated) {
        // Prompt user to log in if not authenticated
        openAuthModal();
        return;
      }
  
      checkoutForm.submit();
    });
  }


  const emptyCartBtn = document.getElementById('emptyCartBtn');
  if (emptyCartBtn) {
    emptyCartBtn.addEventListener('click', () => {
      // 1) Clear the cart
      saveCartToCookie([]);
      // 2) Update UI
      renderCartItems();
      updateCartBadgeNow();
      // 3) Optional: show a toast/alert
      showToast('Your cart has been emptied.', 'success');
    });
  }
} 

  function updateItemQuantity(index, action) {
    let cart = getCartFromCookie();
    if (!cart[index]) return;
  
    let item = cart[index];
    let newQty = item.quantity;
  
    if (action === "increment") {
      newQty++;
    } else if (action === "decrement") {
      newQty--;
    }
  
    if (newQty < 1) {
      cart.splice(index, 1);
    }
  
    // Check stock limit
    if (newQty > item.stock) {
      showToast(`Only ${item.stock} in stock for "${item.name}".`, 'error');
      newQty = item.stock;  // Revert to item.quantity and return
    }
  
    item.quantity = newQty;
    saveCartToCookie(cart);
    renderCartItems(); // or renderCart();
  
    // Maybe show a success toast
    // showToast('Cart updated successfully!', 'success');
  }  

  // Update the cartBadge
  function updateCartBadge(cart) {
  if (!cartBadge) return;
  let totalItems = 0;
  cart.forEach(item => {
    totalItems += item.quantity;
  });
  if (totalItems > 0) {
    cartBadge.textContent = totalItems;
    cartBadge.style.display = "inline-block";
  } else {
    cartBadge.style.display = "none";
  }
  }

  window.updateCartBadgeNow = function() {
  let cart = getCartFromCookie(); 
  updateCartBadge(cart);
  };

  // Add a call to "renderCartItems" each time the cart sidebar is opened
  if (cartLink && cartSidebar) {
  cartLink.addEventListener("click", function (e) {
    e.preventDefault();
    // 1) Render items
    renderCartItems();
    // 2) Show the sidebar
    cartSidebar.classList.add("active");
  });
  }

  // Also call renderCartItems() once on page load to keep the badge correct:
  renderCartItems();

  /*** Reuse your getCartFromCookie, saveCartToCookie, readCookie ***/
  // If you keep them in addtocart.js, you can either copy them here or put them in a shared file
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

  function saveCartToCookie(cartArray) {
    const cartStr = JSON.stringify(cartArray);
    const expires = new Date(Date.now() + 7 * 24 * 60 * 60 * 1000).toUTCString();
    document.cookie = `cartItems=${encodeURIComponent(cartStr)}; expires=${expires}; path=/`;
  }

  function readCookie(name) {
    let match = document.cookie.match(new RegExp('(^| )' + name + '=([^;]+)'));
    return match ? decodeURIComponent(match[2]) : null;
  }

  /**
   * Toggles a modern loading state for a button using CSS spinner.
   * @param {HTMLButtonElement} button - The button element.
   * @param {boolean} isLoading - Whether to show or hide the loading state.
   */
  function toggleButtonLoading(button, isLoading) {
    if (!button) return;

    if (isLoading) {
      if (!button.dataset.originalText) {
        button.dataset.originalText = button.textContent.trim();
      }
      button.disabled = true;
      button.classList.add("button-spinner");
    } else {
      button.disabled = false;
      if (button.dataset.originalText) {
        button.textContent = button.dataset.originalText;
      }
      button.classList.remove("button-spinner");
    }
  }

  login.addEventListener("click", function (e) {
    if (!isAuthenticated) {
        openAuthModal();
    }
  });

  // 3) This function is invoked in the fetch callback to reflect user status
  function updateAccountLink() {
    const accountLink = document.getElementById("accountLink");
    if (!accountLink) return;
    if (isAuthenticated) {
      // If logged in, show dropdown
      accountLink.innerHTML = `
        <div class="account-control" id="accountIconWrapper">
          <svg id="accountIcon" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" fill="#ffffff" stroke="#ffffff">
            <circle cx="12" cy="7.25" r="5.73" style="fill:none;stroke:#fff;stroke-miterlimit:10;stroke-width:1.91px;"></circle>
            <path d="M1.5,23.48l.37-2.05A10.3,10.3,0,0,1,12,13h0a10.3,10.3,0,0,1,10.13,8.45l.37,2.05" style="fill:none;stroke:#fff;stroke-miterlimit:10;stroke-width:1.91px;"></path>
          </svg>
        </div>
        <div class="account-dropdown" id="accountDropdown">
          <a href="/profile.php">
            <i class="fa fa-user-circle"></i>
            Profile
          </a>
          <a href="/includes/cls.php">
            <i class="fa fa-sign-out-alt"></i>
            Log Out
          </a>
        </div>
      `;
    } else {
      // If not logged in, simple icon
      accountLink.innerHTML = `
        <div class="account-control" id="accountIconWrapper">
          <svg id="accountIcon" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" fill="#ffffff" stroke="#ffffff">
            <circle cx="12" cy="7.25" r="5.73" style="fill:none;stroke:#fff;stroke-miterlimit:10;stroke-width:1.91px;"></circle>
            <path d="M1.5,23.48l.37-2.05A10.3,10.3,0,0,1,12,13h0a10.3,10.3,0,0,1,10.13,8.45l.37,2.05" style="fill:none;stroke:#fff;stroke-miterlimit:10;stroke-width:1.91px;"></path>
          </svg>
        </div>
      `;
    }
  }
});
