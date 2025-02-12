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

  // Update account link based on authentication state
  function updateAccountLink() {
    const accountLink = document.getElementById("accountLink");
    if (!accountLink) return;
    if (isAuthenticated) {
      accountLink.innerHTML = `
        <img src="assets/images/account.png" alt="Account" id="accountIcon" />
        <div class="account-dropdown" id="accountDropdown">
          <a href="profile.html">Profile</a>
          <a href="logout.html">Log Out</a>
        </div>
      `;
    } else {
      accountLink.innerHTML = `<img src="assets/images/account.png" alt="Account" id="accountIcon" />`;
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
        // Send AJAX request to send_otp.php
        fetch("../includes/send_otp.php", {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify({ email: email }),
        })
          .then((response) => response.json())
          .then((data) => {
            if (data.success) {
              authStep1.style.display = "none";
              authStep2.style.display = "block";
            } else {
              alert("Error: " + data.error);
            }
          })
          .catch((error) => {
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
        // Send AJAX request to verify_otp.php
        fetch("../includes/verify_otp.php", {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify({ email: email, otp: otp }),
        })
          .then((response) => response.json())
          .then((data) => {
            if (data.success) {
              isAuthenticated = true;
              updateAccountLink();
              closeAuthModal();
            } else {
              alert("Verification failed: " + data.error);
            }
          })
          .catch((error) => {
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

  // Initialization
  updateAccountLink();
});