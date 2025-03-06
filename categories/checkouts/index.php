<!DOCTYPE html>
<html lang="en">
<head>
  <title>Checkout</title>
  <?php require_once __DIR__ . '/../../assets/php/main.php'; ?>
  <link rel="stylesheet" href="../../assets/css/main.css">
  <style>
    :root {
      --secondary-color: #f4f4f4;
      --button-bg: linear-gradient(135deg, #0117ff, #8d07cc, #d42d2d);
    }
    
    .checkout-container {
      max-width: 1200px;
      margin: 50px auto;
      padding: 0 20px;
    }

    .checkout-header {
      text-align: center;
      margin-bottom: 40px;
    }
    .checkout-header h1 {
      font-size: 2rem;
      margin-top: 80px;
      margin-bottom: 10px;
    }

    .checkout-content {
      display: grid;
      grid-template-columns: 1fr 1fr;
      grid-gap: 40px;
    }
    .left-section {
      display: flex;
      flex-direction: column;
      gap: 30px;
    }

    /* ========== Billing Box ========== */
    .billing-box {
      background: var(--secondary-color);
      border-radius: 8px;
      padding: 20px;
    }
    .billing-box h2 {
      margin-bottom: 15px;
      font-size: 1.4rem;
    }
    .billing-details {
      list-style: none;
      padding: 0;
      margin: 0 0 20px;
    }
    .billing-details li {
      margin-bottom: 8px;
      font-size: 0.95rem;
      line-height: 1.4rem;
    }
    .billing-details li strong {
      width: 120px;
      display: inline-block;
    }
    .edit-billing-btn {
      display: block;
      width: 100%;
      text-align: center;
      padding: 12px;
      border: none;
      border-radius: 30px;
      font-weight: bold;
      background: var(--button-bg);
      color: #fff;
      cursor: pointer;
      transition: background 0.3s ease;
    }
    .edit-billing-btn:hover {
      filter: brightness(1.1);
    }

    /* ========== Payment Info ========== */
    .payment-info {
      background: var(--secondary-color);
      border-radius: 8px;
      padding: 20px;
    }
    .payment-info h2 {
      margin-bottom: 20px;
      font-size: 1.4rem;
    }

    /* ========== Radio-as-Image for Payment Methods ========== */
    .payment-methods-container {
      margin-bottom: 20px;
    }
    .payment-options {
      display: flex;
      gap: 15px;
      flex-wrap: wrap;
    }
    .payment-options input[type="radio"] {
      display: none; 
    }
    .payment-option-label {
      cursor: pointer;
      border: 2px solid transparent;
      border-radius: 8px;
      padding: 8px;
      margin: 37px;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      transition: border-color 0.3s ease;
    }
    .payment-option-label svg {
      width: 70px; 
      height: auto;
      display: block;
    }

    input[type="radio"]:checked + .payment-option-label {
      border-color: #8d07cc; /* A highlight color */
    }

    /* ========== Credit Card Fields ========== */
    .cc-fields {
      display: none;
      margin-top: 10px;
    }
    .cc-fields .form-group {
      margin-bottom: 15px;
    }
    .cc-fields .form-group label {
      display: block;
      font-weight: bold;
      margin-bottom: 5px;
    }
    .cc-fields .form-group input {
      width: 100%;
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 5px;
    }

    /* ========== Order Summary ========== */
    .order-summary {
      background: var(--secondary-color);
      padding: 20px;
      border-radius: 8px;
      height: fit-content;
    }
    .order-summary h2 {
      margin-bottom: 20px;
      font-size: 1.4rem;
    }
    .cart-item {
      display: flex;
      gap: 15px;
      margin-bottom: 20px;
      border-bottom: 1px solid #ccc;
      padding-bottom: 15px;
    }
    .cart-item img {
      width: 80px;
      height: 80px;
      object-fit: cover;
      border-radius: 8px;
    }
    .cart-item-info {
      display: flex;
      flex-direction: column;
      justify-content: center;
    }
    .cart-item-info p {
      margin-bottom: 5px;
    }
    .totals {
      margin-top: 20px;
      border-top: 1px solid #ccc;
      padding-top: 20px;
    }
    .totals p {
      display: flex;
      justify-content: space-between;
      margin-bottom: 10px;
      font-weight: 500;
    }
    .place-order {
      display: block;
      width: 100%;
      text-align: center;
      padding: 12px 20px;
      color: #fff;
      font-weight: bold;
      border: none;
      border-radius: 30px;
      cursor: pointer;
      background: var(--button-bg);
      transition: background 0.3s ease;
    }
    .place-order:hover {
      filter: brightness(1.1);
    }

    /* ========== Responsive ========== */
    @media (max-width: 768px) {
      .checkout-content {
        grid-template-columns: 1fr;
      }
      .left-section {
        gap: 20px;
      }
    }
  </style>
</head>
<body>

  <!-- Header Section -->
  <?php require_once __DIR__ . '/../../assets/php/header.php'; ?>

  <!-- Checkout Container -->
  <div class="checkout-container">
    <div class="checkout-header">
      <h1>Checkout</h1>
      <p>Complete your purchase of the items in your cart</p>
    </div>

    <div class="checkout-content">
      <!-- LEFT COLUMN: Billing Info & Payment Info -->
      <div class="left-section">
        
        <!-- ========== BILLING BOX (VIEW-ONLY) ========== -->
        <div class="billing-box">
          <h2>Addresses</h2>
          <ul class="billing-details">
            <li><strong>Address Name:</strong> Home</li>
          </ul>
          <button 
            class="edit-billing-btn" 
            onclick="alert('Open edit modal or go to edit page...');"
          >
            Edit
          </button>
        </div>

        <!-- ========== PAYMENT INFO (FORM) ========== -->
        <div class="payment-info">
          <h2>Payment Information</h2>

          <form id="checkoutForm">
            <div class="payment-methods-container">
              <div class="payment-options">

                <!-- credit_card -->
                <input
                  type="radio"
                  id="creditCard"
                  name="payment_method"
                  value="credit_card"
                  checked
                />
                <label for="creditCard" class="payment-option-label">
                  <!-- Card SVG -->
                  <svg version="1.0" id="Layer_1"
                       xmlns="http://www.w3.org/2000/svg"
                       xmlns:xlink="http://www.w3.org/1999/xlink"
                       viewBox="0 0 64 64"
                       xml:space="preserve"
                       fill="#000000">
                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                    <g id="SVGRepo_iconCarrier">
                      <g>
                        <path fill="#231F20" d="M0,32v20c0,2.211,1.789,4,4,4h56c2.211,0,4-1.789,4-4V32H0z M24,44h-8c-2.211,0-4-1.789-4-4
                          s1.789-4,4-4h8c2.211,0,4,1.789,4,4S26.211,44,24,44z"></path>
                        <path fill="#231F20" d="M64,24V12c0-2.211-1.789-4-4-4H4c-2.211,0-4,1.789-4,4v12H64z"></path>
                      </g>
                    </g>
                  </svg>
                </label>

                <!-- paypal -->
                <input
                  type="radio"
                  id="paypal"
                  name="payment_method"
                  value="paypal"
                />
                <label for="paypal" class="payment-option-label">
                  <!-- PayPal SVG -->
                  <svg viewBox="-1.5 0 20 20" version="1.1"
                       xmlns="http://www.w3.org/2000/svg"
                       fill="#000000">
                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                    <g id="SVGRepo_iconCarrier">
                      <title>paypal [#140]</title>
                      <desc>Created with Sketch.</desc>
                      <g fill="none" fill-rule="evenodd">
                        <g fill="#000000">
                          <path d="M182.475463,7404.9 C181.260804,7410.117 177.555645,7411 172.578656,7411 L171.078137,7419 L173.825411,7419 C174.325918,7419 174.53555,7418.659 174.627828,7418.179 C175.312891,7413.848 175.216601,7414.557 175.278788,7413.879 C175.337966,7413.501 175.664951,7413 176.049108,7413 C179.698098,7413 182.118387,7411.945 182.857614,7408.158 C183.120405,7406.811 183.034145,7405.772 182.475463,7404.9 M171.134306,7410.86 L170.011926,7417 L166.535456,7417 C166.206465,7417 165.954707,7416.598 166.006864,7416.274 L168.602682,7399.751 C168.670887,7399.319 169.045014,7399 169.484337,7399 L175.718111,7399 C179.409228,7399 181.894714,7400.401 181.319983,7404.054 C180.313953,7410.56 174.737157,7410 172.199514,7410 C171.760191,7410 171.203515,7410.428 171.134306,7410.86" transform="translate(-222 -7559) translate(56 160)"></path>
                        </g>
                      </g>
                    </g>
                  </svg>
                </label>

                <!-- cash_on_delivery -->
                <input
                  type="radio"
                  id="cod"
                  name="payment_method"
                  value="cash_on_delivery"
                />
                <label for="cod" class="payment-option-label">
                  <!-- Cash SVG -->
                  <svg fill="#000000" height="200px" width="200px"
                       version="1.1" id="Capa_1"
                       xmlns="http://www.w3.org/2000/svg"
                       xmlns:xlink="http://www.w3.org/1999/xlink"
                       viewBox="0 0 462.085 462.085"
                       xml:space="preserve">
                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                    <g id="SVGRepo_iconCarrier">
                      <g>
                        <path d="M452.585,98.818H58.465c-5.247,0-9.5,4.253-9.5,9.5v14.98h-14.98c-5.247,0-9.5,4.253-9.5,9.5v14.979H9.5
                          c-5.247,0-9.5,4.253-9.5,9.5v196.49c0,5.247,4.253,9.5,9.5,9.5h394.117c5.247,0,9.5-4.253,9.5-9.5v-14.98h14.978
                          c5.247,0,9.5-4.253,9.5-9.5v-14.98h14.99c5.247,0,9.5-4.253,9.5-9.5v-196.49C462.085,103.071,457.832,98.818,452.585,98.818z
                          M46.808,166.777C43.341,180.385,32.607,191.118,19,194.586v-27.809H46.808z M394.117,194.586
                          c-13.609-3.467-24.343-14.201-27.811-27.809h27.811V194.586z M346.917,166.777c4.027,24.105,23.095,43.173,47.201,47.199v83.09
                          c-24.107,4.026-43.175,23.095-47.201,47.201H66.198C62.172,320.162,43.105,301.094,19,297.067v-83.091
                          c24.105-4.028,43.171-23.094,47.198-47.199H346.917z M19,316.457c13.608,3.468,24.342,14.202,27.808,27.811H19V316.457z
                          M366.306,344.267c3.467-13.609,14.202-24.344,27.811-27.811v27.811H366.306z M418.595,319.787h-5.478v-162.51
                          c0-5.247-4.253-9.5-9.5-9.5H43.484v-5.479h375.11V319.787z M443.085,295.307h-5.49v-162.51c0-5.247-4.253-9.5-9.5-9.5H67.965v-5.48
                          h375.12V295.307z"></path>
                        <path d="M161.564,311.56c11.817,15.934,27.797,24.708,44.994,24.708c17.198,0,33.178-8.775,44.995-24.708
                          c11.187-15.083,17.347-34.984,17.347-56.038c0-21.055-6.16-40.956-17.347-56.038c-11.817-15.934-27.797-24.708-44.995-24.708
                          c-17.197,0-33.177,8.775-44.994,24.708c-11.187,15.082-17.347,34.983-17.347,56.038
                          C144.218,276.576,150.378,296.478,161.564,311.56z M206.559,317.268c-9.053,0-17.463-3.981-24.423-10.77
                          c1.486-12.161,11.868-21.612,24.423-21.612c12.556,0,22.938,9.451,24.424,21.612C224.022,313.287,215.612,317.268,206.559,317.268z
                          M206.562,265.49c-8.022,0-14.549-6.526-14.549-14.548c0-8.022,6.526-14.549,14.549-14.549
                          c8.021,0,14.548,6.526,14.548,14.549C221.109,258.963,214.583,265.49,206.562,265.49z M206.559,193.776
                          c23.898,0,43.342,27.699,43.342,61.747c0,11.458-2.209,22.189-6.042,31.396c-3.208-5.279-7.508-9.82-12.579-13.322
                          c5.479-5.973,8.83-13.929,8.83-22.655c0-18.499-15.05-33.549-33.548-33.549c-18.499,0-33.549,15.05-33.549,33.549
                          c0,8.725,3.35,16.68,8.829,22.653c-5.072,3.502-9.374,8.044-12.582,13.324c-3.833-9.206-6.042-19.938-6.042-31.396
                          C163.218,221.475,182.66,193.776,206.559,193.776z"></path>
                      </g>
                    </g>
                  </svg>
                </label>
              </div>
            </div>

            <!-- 2) Credit Card Fields (hidden unless credit_card is chosen) -->
            <div class="cc-fields" id="creditCardFields">
              <div class="form-group">
                <label for="cardNumber">Card Number</label>
                <input
                  type="text"
                  id="cardNumber"
                  name="cardNumber"
                  placeholder="1234 5678 9123 4567"
                />
              </div>
              <div class="form-group">
                <label for="expDate">Expiration Date (MM/YY)</label>
                <input
                  type="text"
                  id="expDate"
                  name="expDate"
                  placeholder="MM/YY"
                />
              </div>
              <div class="form-group">
                <label for="cvv">CVV</label>
                <input
                  type="text"
                  id="cvv"
                  name="cvv"
                  placeholder="123"
                />
              </div>
            </div>
          </form>
        </div>
      </div>

      <!-- RIGHT COLUMN: ORDER SUMMARY -->
      <div class="order-summary">
        <h2>Your Order</h2>

        <div class="cart-item">
          <img
            src="../assets/images/Products/Brand/T-shirt/Front.png"
            alt="Black T-shirt with Logo"
          />
          <div class="cart-item-info">
            <p><strong>Black T-shirt with Logo</strong></p>
            <p>Price: $100.00</p>
          </div>
        </div>
        
        <div class="totals">
          <p><span>Subtotal</span> <span>$100.00</span></p>
          <p><span>Shipping</span> <span>$0.00</span></p>
          <p><span>Tax</span> <span>$0.00</span></p>
          <p><strong>Total</strong> <strong>$100.00</strong></p>
        </div>

        <button type="submit" form="checkoutForm" class="place-order">
          Pay now
        </button>
      </div>
    </div>
  </div>
  <!-- Showing/hiding credit card fields -->
  <script>
    const paymentRadios = document.querySelectorAll('input[name="payment_method"]');
    const creditCardFields = document.getElementById('creditCardFields');

    function updateCCFields() {
      const selected = document.querySelector('input[name="payment_method"]:checked');
      creditCardFields.style.display = (selected.value === 'credit_card') ? 'block' : 'none';
    }
    updateCCFields();

    paymentRadios.forEach(radio => {
      radio.addEventListener('change', updateCCFields);
    });
  </script>

  <!-- Authentication Modal -->
  <?php require_once __DIR__ . '/../../assets/php/auth.php'; ?>
  
  <script src="../../assets/js/main.js"></script>
</body>
</html>