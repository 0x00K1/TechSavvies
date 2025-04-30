document.addEventListener("DOMContentLoaded", function () {
  const addToCartBtn    = document.querySelector(".add-to-cart");
  const productIdField  = document.getElementById("productId");
  const productImageEl  = document.getElementById("productImage");
  const productNameEl   = document.getElementById("productName"); 
  const priceField      = document.getElementById("productPrice");
  const colorField      = document.getElementById("productColor");
  const sizeField       = document.getElementById("productSize");
  const quantityField   = document.getElementById("quantityInput");
  const stockField      = document.getElementById("productStock");

  function showToast(message, type = 'success') {
    const toast       = document.getElementById('toast');
    const toastContent= document.getElementById('toastContent');
    const toastContainer = document.getElementById('toastContainer');
    
    if (!toast || !toastContent || !toastContainer) return;
    
    toastContent.textContent = message;
    toast.className = 'toast ' + type;
    toastContainer.style.display = 'block';
    
    // Hide after 5 seconds
    setTimeout(() => {
      toastContainer.style.display = 'none';
    }, 5000);
  }

  function showLoadingOverlay() {
    const overlay = document.getElementById('loadingOverlay');
    if (overlay) overlay.classList.add('active');
  }

  function hideLoadingOverlay() {
    const overlay = document.getElementById('loadingOverlay');
    if (overlay) overlay.classList.remove('active');
  }

  // Try hooking up the close button for the toast
  const toastCloseBtn = document.getElementById('toastClose');
  if (toastCloseBtn) {
    toastCloseBtn.addEventListener('click', function() {
      const toastContainer = document.getElementById('toastContainer');
      if (toastContainer) toastContainer.style.display = 'none';
    });
  }

  if (window.isRoot === true) {
    if (addToCartBtn) {
      addToCartBtn.addEventListener("click", e => {
            e.preventDefault();
            showToast("Root accounts cannot add items to the cart.", "error");
        });
        addToCartBtn.classList.add("disabled");
    }
    return;          // nothing else should run for roots
  }

  addToCartBtn.addEventListener("click", function () {
    try {
      // 1) Gather input values
      const productId = productIdField.value;
      const quantity  = quantityField ? (parseInt(quantityField.value) || 1) : 1;
      const price     = parseFloat(priceField.textContent) || 0.0;
      const name      = productNameEl ? productNameEl.textContent.trim() : "Unknown Product";
      const imageSrc  = productImageEl ? productImageEl.getAttribute("src") : "";
      const stock = stockField ? parseInt(stockField.value, 10) : 0;
      const color = colorField ? colorField.value : "";
      const size  = sizeField  ? sizeField.value  : "";

      // 2) Validate stock if applicable
      if (stockField) {
        const stock = parseInt(stockField.value) || 0;

        if (stock <= 0) {
          showToast('Out of stock.', 'error');
          return;
        } else if (quantity > stock) {
          showToast(`Only ${stock} item(s) in stock.`, 'error');
          return;
        }
      }      

      // 3) Show loading
      showLoadingOverlay();

      // 4) Add item to cart cookie
      const success = addItemToCartCookie({
        productId: productId,
        name: name,
        image: imageSrc,
        quantity: quantity,
        price: price,
        color: color,
        size: size,
        stock: stock
      });

      // 5) Hide overlay and show success toast
      hideLoadingOverlay();

      if (success) {
        showToast('Product added to cart successfully!', 'success');
        if (typeof updateCartBadgeNow === "function") {
          // 6) Optionally update badge
          updateCartBadgeNow();
        }
      } else {
        showToast(`Only ${stock} item(s) in stock.`, 'error');
        return;
      }
    } catch (err) {
      console.error("Error adding to cart:", err);
      hideLoadingOverlay();
      showToast('Something went wrong. Please try again.', 'error');
    }
  });

  function addItemToCartCookie(item) {
    const cart = getCartFromCookie();
  
    // 1) See if item is already in cart:
    let existingItem = cart.find(ci =>
      ci.productId === item.productId &&
      ci.color === item.color &&
      ci.size === item.size
    );
  
    // 2) If it exists, compute the new quantity
    if (existingItem) {
      const newQty = existingItem.quantity + item.quantity;
  
      // 3) If newQty > item.stock, show error or clamp
      if (newQty > item.stock) {
        if (typeof showToast === "function") {
          showToast(`Cannot exceed ${item.stock} in stock. You already have ${existingItem.quantity} in cart.`, 'error');
        }
        return false; // indicate failure or partial
      }
  
      // OK – update quantity
      existingItem.quantity = newQty;
    } else {
      // Not in cart yet. Check if item.quantity itself is <= stock
      if (item.quantity > item.stock) {
        if (typeof showToast === "function") {
          showToast(`Cannot exceed ${item.stock} in stock.`, 'error');
        }
        return false;
      }
      // Insert as a new cart item
      cart.push(item);
    }
  
    // 4) Save to cookie
    saveCartToCookie(cart);
    return true; // success
  }  

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
});