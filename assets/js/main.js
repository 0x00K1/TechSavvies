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
  // Global authentication state
  let isAuthenticated = false;
  // Track OTP verification failures
  let otpFailureCount = 0;

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

  // Update account link based on authentication state
  function updateAccountLink() {
    const accountLink = document.getElementById("accountLink");
    if (!accountLink) return;

    if (isAuthenticated) {
      // Enhanced
      accountLink.innerHTML = `
        <div class="account-control" id="accountIconWrapper">
          <svg id="accountIcon" viewBox="0 0 24 24" id="Layer_1" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" fill="#ffffff" stroke="#ffffff"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"><defs><style>.cls-1{fill:none;stroke:#ffffff;stroke-miterlimit:10;stroke-width:1.91px;}</style></defs><circle class="cls-1" cx="12" cy="7.25" r="5.73"></circle><path class="cls-1" d="M1.5,23.48l.37-2.05A10.3,10.3,0,0,1,12,13h0a10.3,10.3,0,0,1,10.13,8.45l.37,2.05"></path></g></svg>
          <i class="fa fa-caret-down"></i>
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
      // Show just the account icon if user is not authenticated
      accountLink.innerHTML = `
        <div class="account-control" id="accountIconWrapper">
          <svg id="accountIcon" viewBox="0 0 24 24" id="Layer_1" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" fill="#ffffff" stroke="#ffffff"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"><defs><style>.cls-1{fill:none;stroke:#ffffff;stroke-miterlimit:10;stroke-width:1.91px;}</style></defs><circle class="cls-1" cx="12" cy="7.25" r="5.73"></circle><path class="cls-1" d="M1.5,23.48l.37-2.05A10.3,10.3,0,0,1,12,13h0a10.3,10.3,0,0,1,10.13,8.45l.37,2.05"></path></g></svg>
        </div>
      `;
    }
  }

  // --- Authentication Modal (OTP Flow) ---
  const authModal = document.getElementById("authModal");
  const closeModalBtn = document.getElementById("closeModal");
  const authStep1 = document.getElementById("authStep1");
  const authStep2 = document.getElementById("authStep2");
  const authEmailInput = document.getElementById("authEmail");
  const authOTPInput = document.getElementById("authOTP");
  const sendOtpBtn = document.getElementById("sendOtpBtn");
  const verifyOtpBtn = document.getElementById("verifyOtpBtn");
  const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

  if (authModal && authStep1 && authStep2 && authEmailInput && authOTPInput) {
    function openAuthModal() {
      // Clear inputs and show step 1
      authEmailInput.value = "";
      authOTPInput.value = "";
      authStep1.style.display = "block";
      authStep2.style.display = "none";
      authModal.style.display = "flex";
    }

    function closeAuthModal() {
      authModal.style.display = "none";
    }

    if (closeModalBtn) {
      closeModalBtn.addEventListener("click", closeAuthModal);
    }

    // Close modal if clicking outside the modal content
    window.addEventListener("click", function (event) {
      if (event.target === authModal) {
        closeAuthModal();
      }
    });

    if (sendOtpBtn) {
      sendOtpBtn.addEventListener("click", function () {
        const email = authEmailInput.value.trim();
        if (!email) {
          alert("Please enter a valid email.");
          return;
        }
    
        // 1) Show loading state (spinner)
        toggleButtonLoading(sendOtpBtn, true);
    
        fetch("/TechSavvies/includes/send_otp.php", {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify({ email: email, csrf_token: csrfToken }),
        })
          .then((response) => response.json())
          .then((data) => {
            // 2) Hide loading state
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
    
    if (verifyOtpBtn) {
      verifyOtpBtn.addEventListener("click", function () {
        const email = authEmailInput.value.trim();
        const otp = authOTPInput.value.trim();
        if (!otp) {
          alert("Please enter the OTP.");
          return;
        }
    
        // 1) Show loading state (spinner)
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
            // 2) Hide loading state
            toggleButtonLoading(verifyOtpBtn, false);
    
            if (data.success) {
              // OTP verification succeeded
              otpFailureCount = 0;        // Reset failures
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
                otpFailureCount = 0; // Reset for future attempts
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

    // Toggle account dropdown or open modal if not authenticated
    document.addEventListener("click", function (e) {
      const accountLink = document.getElementById("accountLink");
      const accountIcon = document.getElementById("accountIcon");
      const accountDropdown = document.getElementById("accountDropdown");
      if (accountIcon && accountIcon.contains(e.target)) {
        if (isAuthenticated) {
          accountLink.classList.toggle("show-dropdown");
        } else { /* We'll inject the HTML here [LATER] */
          openAuthModal();
        }
      } else {
        if (accountDropdown && !accountDropdown.contains(e.target)) {
          accountLink.classList.remove("show-dropdown");
        }
      }
    });
  }

  const cartSidebarHTML = `
    <div id="cartSidebar">
      <button id="closeCart">×</button>
      <h2>Your Cart</h2>
      <div id="cartContent">
        <p>Your cart is currently empty.</p>
      </div>
    </div>
  `;
  document.body.insertAdjacentHTML("beforeend", cartSidebarHTML);

  // Set up event listeners for toggling the sidebar
  const cartLink = document.getElementById("cartLink");
  const cartSidebar = document.getElementById("cartSidebar");
  const closeCartBtn = document.getElementById("closeCart");

  if (cartLink && cartSidebar && closeCartBtn) {
    cartLink.addEventListener("click", function (e) {
      e.preventDefault();
      cartSidebar.classList.add("active");
    });
    
    closeCartBtn.addEventListener("click", function () {
      cartSidebar.classList.remove("active");
    });
    
    // Optional: hide sidebar when clicking outside it
    document.addEventListener("click", function (e) {
      if (!cartSidebar.contains(e.target) && !cartLink.contains(e.target)) {
        cartSidebar.classList.remove("active");
      }
    });
  }

  /**
   * Toggles a modern loading state for a button using CSS spinner.
   * @param {HTMLButtonElement} button - The button element.
   * @param {boolean} isLoading - Whether to show or hide the loading state.
   */
  function toggleButtonLoading(button, isLoading) {
    if (!button) return;

    if (isLoading) {
      // Save original text if not already saved
      if (!button.dataset.originalText) {
        button.dataset.originalText = button.textContent.trim();
      }
      // Disable button
      button.disabled = true;
      
      // Add spinner class to show rotating circle
      button.classList.add("button-spinner");
    } else {
      // Re-enable button
      button.disabled = false;
      // Restore original text
      if (button.dataset.originalText) {
        button.textContent = button.dataset.originalText;
      }
      // Remove spinner class
      button.classList.remove("button-spinner");
    }
  }

  // Initialization
  updateAccountLink();
});
