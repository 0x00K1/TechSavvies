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
});