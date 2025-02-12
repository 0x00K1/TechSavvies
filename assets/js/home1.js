document.addEventListener("DOMContentLoaded", function () {
  const shopNowButton = document.querySelector(".hero-content a span");

  shopNowButton.addEventListener("mouseenter", function () {
      shopNowButton.style.opacity = "1"; // Ensure text stays visible
      shopNowButton.style.transform = "translateY(-5px)"; // Slight movement effect
  });

  shopNowButton.addEventListener("mouseleave", function () {
      shopNowButton.style.opacity = "1"; // Keep text visible after hover
      shopNowButton.style.transform = "translateY(0)"; // Reset movement effect
  });

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

  // --- Slider Functionality ---
  const sliderWrapper = document.getElementById("sliderWrapper");
  if (sliderWrapper) {
    let currentSlide = 0;
    const slides = sliderWrapper.getElementsByClassName("slide");
    const totalSlides = slides.length;

    function showSlide(index) {
      if (index >= totalSlides) {
        currentSlide = 0;
      } else if (index < 0) {
        currentSlide = totalSlides - 1;
      } else {
        currentSlide = index;
      }
      sliderWrapper.style.transform = "translateX(" + -currentSlide * 100 + "%)";
    }

    setInterval(() => {
      showSlide(currentSlide + 1);
    }, 3000);
  }

  // --- Contact Form Submission (Simulation) ---
  const contactForm = document.getElementById("contactForm");
  if (contactForm) {
    contactForm.addEventListener("submit", function (e) {
      e.preventDefault();
      alert("Thank you for your message! We will get back to you soon.");
      contactForm.reset();
    });
  }

  // Initialization
  updateAccountLink();
});