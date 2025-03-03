document.addEventListener("DOMContentLoaded", () => {
	const popularSlider = document.querySelector('.popular-products .slider-wrapper');
	const prevButton = document.querySelector('.popular-products .prev');
	const nextButton = document.querySelector('.popular-products .next');
	
	if (popularSlider && prevButton && nextButton) {
	  let currentSlide = 0;
	  const slides = popularSlider.querySelectorAll('.slide');
	  const totalSlides = slides.length;
	  const visibleSlides = 3; // Number of slides visible at once (based on CSS)
	  const maxSlideIndex = Math.max(0, totalSlides - visibleSlides);
	  
	  // Enhanced smooth transition
	  popularSlider.style.transition = "transform 0.8s cubic-bezier(0.25, 1, 0.5, 1)";
	  
	  // Set initial position
	  function updateSliderPosition(animate = true) {
		// Calculate the percentage to translate based on slide width
		const slideWidth = 100 / visibleSlides;
		
		// Apply smoother animation when requested
		if (!animate) {
		  popularSlider.style.transition = "none";
		  requestAnimationFrame(() => {
			popularSlider.style.transform = `translateX(-${currentSlide * slideWidth}%)`;
			// Re-enable transitions after the transform is applied
			requestAnimationFrame(() => {
			  popularSlider.style.transition = "transform 0.8s cubic-bezier(0.25, 1, 0.5, 1)";
			});
		  });
		} else {
		  popularSlider.style.transform = `translateX(-${currentSlide * slideWidth}%)`;
		}
	  }
	  
	  function showSlide(index) {
		// Ensure index is within bounds
		if (index > maxSlideIndex) {
		  // For smooth infinite loop - first jump without animation to the start
		  currentSlide = 0;
		  updateSliderPosition(true);
		} else if (index < 0) {
		  // For smooth infinite loop - first jump without animation to the end
		  currentSlide = maxSlideIndex;
		  updateSliderPosition(true);
		} else {
		  currentSlide = index;
		  updateSliderPosition(true);
		}
	  }
	  
	  // Event listeners for buttons with smooth transitions
	  prevButton.addEventListener('click', () => {
		showSlide(currentSlide - 1);
		// Reset the interval timer when manually navigating
		resetAutoSlideTimer();
	  });
	  
	  nextButton.addEventListener('click', () => {
		showSlide(currentSlide + 1);
		// Reset the interval timer when manually navigating
		resetAutoSlideTimer();
	  });
	  
	  // Function for automatic sliding
	  function autoSlide() {
		showSlide(currentSlide + 1);
	  }
	  
	  // Set up automatic sliding every 4 seconds
	  let autoSlideInterval = setInterval(autoSlide, 5000);
	  
	  function resetAutoSlideTimer() {
		clearInterval(autoSlideInterval);
		autoSlideInterval = setInterval(autoSlide, 5000);
	  }
	  
	  // Pause automatic sliding when hovering over the slider
	  const sliderContainer = document.querySelector('.popular-products .slider-container');
	  if (sliderContainer) {
		sliderContainer.addEventListener('mouseenter', () => {
		  clearInterval(autoSlideInterval);
		});
		
		sliderContainer.addEventListener('mouseleave', () => {
		  autoSlideInterval = setInterval(autoSlide, 5000);
		});
	  }
	  
	  // Add touch support for mobile users
	  let touchStartX = 0;
	  let touchEndX = 0;
	  
	  popularSlider.addEventListener('touchstart', (e) => {
		touchStartX = e.changedTouches[0].screenX;
		// Pause the slider while touching
		clearInterval(autoSlideInterval);
	  }, { passive: true });
	  
	  popularSlider.addEventListener('touchend', (e) => {
		touchEndX = e.changedTouches[0].screenX;
		handleSwipe();
		// Resume auto sliding
		resetAutoSlideTimer();
	  }, { passive: true });
	  
	  function handleSwipe() {
		const swipeThreshold = 50; // Minimum pixels for swipe detection
		if (touchEndX < touchStartX - swipeThreshold) {
		  // Swipe left - show next slide
		  showSlide(currentSlide + 1);
		} else if (touchEndX > touchStartX + swipeThreshold) {
		  // Swipe right - show previous slide
		  showSlide(currentSlide - 1);
		}
	  }
	  
	  // Initialize the slider position
	  updateSliderPosition(false);
	}
	
	// --- Hero Slider ---
	const heroSliderWrapper = document.getElementById("sliderWrapper");
	if (heroSliderWrapper) {
	  heroSliderWrapper.style.transition = "transform 0.5s ease-in-out";
	  let currentSlide = 0;
	  const slides = heroSliderWrapper.getElementsByClassName("slide");
	  const totalSlides = slides.length;
  
	  function showSlide(index) {
		if (index >= totalSlides) {
		  currentSlide = 0;
		} else if (index < 0) {
		  currentSlide = totalSlides - 1;
		} else {
		  currentSlide = index;
		}
		heroSliderWrapper.style.transform = "translateX(" + -currentSlide * 100 + "%)";
	  }
  
	  // Change slide every 3 seconds
	  setInterval(() => {
		showSlide(currentSlide + 1);
	  }, 3000);
	}
  
	// --- Shop Now Button Hover ---
	const shopNowButton = document.querySelector(".hero-content a span");
	if (shopNowButton) {
	  shopNowButton.style.transition = "transform 0.3s ease";
	  shopNowButton.addEventListener("mouseenter", function () {
		shopNowButton.style.opacity = "1";
		shopNowButton.style.transform = "translateY(-5px)";
	  });
	  shopNowButton.addEventListener("mouseleave", function () {
		shopNowButton.style.opacity = "1";
		shopNowButton.style.transform = "translateY(0)";
	  });
	}
  
	// --- Contact Form Functionality ---
	function showPopupMessage(success, message) {
	  const popup = document.createElement("div");
	  popup.style.position = "fixed";
	  popup.style.top = "20px";
	  popup.style.right = "20px";
	  popup.style.padding = "20px";
	  popup.style.background = "#fff";
	  popup.style.border = "2px solid " + (success ? "#8d07cc" : "#d42d2d");
	  popup.style.zIndex = "9999";
	  popup.style.boxShadow = "0 4px 20px rgba(0,0,0,0.2)";
	  popup.style.borderRadius = "8px";
	  popup.style.minWidth = "300px";
  
	  const icon = document.createElement("span");
	  icon.innerHTML = success ? "✓" : "✕";
	  icon.style.fontSize = "24px";
	  icon.style.display = "block";
	  icon.style.marginBottom = "10px";
	  icon.style.color = success ? "#8d07cc" : "#d42d2d";
  
	  const title = document.createElement("h3");
	  title.textContent = success ? "Success!" : "Error";
	  title.style.margin = "0 0 10px 0";
  
	  const text = document.createElement("p");
	  text.textContent = message;
	  text.style.margin = "0";
  
	  popup.appendChild(icon);
	  popup.appendChild(title);
	  popup.appendChild(text);
  
	  popup.style.animation = "slideIn 0.3s ease-out";
	  const style = document.createElement("style");
	  style.textContent = `
		@keyframes slideIn {
		  from { transform: translateX(100%); opacity: 0; }
		  to { transform: translateX(0); opacity: 1; }
		}
		@keyframes slideOut {
		  from { transform: translateX(0); opacity: 1; }
		  to { transform: translateX(100%); opacity: 0; }
		}
	  `;
	  document.head.appendChild(style);
	  document.body.appendChild(popup);
  
	  setTimeout(() => {
		popup.style.animation = "slideOut 0.3s ease-out";
		setTimeout(() => {
		  popup.remove();
		  style.remove();
		}, 300);
	  }, 3000);
	}
  
	const contactForm = document.getElementById("contactForm");
	if (contactForm) {
	  contactForm.addEventListener("submit", function (e) {
		e.preventDefault();
		const formData = new FormData(this);
		const submitButton = this.querySelector('button[type="submit"]');
		submitButton.disabled = true;
		fetch("assets/php/send_email.php", {
		  method: "POST",
		  headers: {
			Accept: "application/json"
		  },
		  body: formData,
		})
		  .then((response) => {
			if (!response.ok) {
			  throw new Error("Network response was not ok");
			}
			return response.json();
		  })
		  .then((data) => {
			showPopupMessage(data.success, data.message);
			if (data.success) {
			  this.reset();
			}
		  })
		  .catch((error) => {
			console.error("Error:", error);
			showPopupMessage(false, "An error occurred. Please try again.");
		  })
		  .finally(() => {
			submitButton.disabled = false;
		  });
	  });
	}
  }
);

// --- Testimonials Slider ---
const testimonialsSlider = document.querySelector('.testimonial-track');
if (testimonialsSlider) {
  let isAutoScrolling = true;

  // Function to start/restart auto scrolling (retriggering animation)
  function startAutoScroll() {
    if (!isAutoScrolling) return;
    // Reset animation by removing and re-adding the class or inline style
    testimonialsSlider.style.animation = 'none';
    void testimonialsSlider.offsetWidth; // force reflow
    testimonialsSlider.style.animation = 'scroll 30s linear infinite';
  }

  // Pause on hover
  const testimonialsSection = document.querySelector('.testimonials');
  if (testimonialsSection) {
    testimonialsSection.addEventListener('mouseenter', () => {
      isAutoScrolling = false;
      testimonialsSlider.style.animationPlayState = 'paused';
    });
    testimonialsSection.addEventListener('mouseleave', () => {
      isAutoScrolling = true;
      testimonialsSlider.style.animationPlayState = 'running';
    });
  }

  // Add touch support for mobile users
  let touchStartX = 0;
  let touchEndX = 0;
  let sliderPosition = 0;

  testimonialsSlider.addEventListener('touchstart', (e) => {
    touchStartX = e.changedTouches[0].screenX;
    testimonialsSlider.style.animationPlayState = 'paused';
    const computedStyle = window.getComputedStyle(testimonialsSlider);
    const matrix = new WebKitCSSMatrix(computedStyle.transform);
    sliderPosition = matrix.m41;
  }, { passive: true });

  testimonialsSlider.addEventListener('touchend', (e) => {
    touchEndX = e.changedTouches[0].screenX;
    handleSwipe();
  }, { passive: true });

  function handleSwipe() {
    const swipeThreshold = 50;
    const swipeDistance = touchEndX - touchStartX;
    if (Math.abs(swipeDistance) > swipeThreshold) {
      testimonialsSlider.style.animation = 'none';
      const moveAmount = swipeDistance;
      const newPosition = sliderPosition + moveAmount;
      testimonialsSlider.style.transition = 'transform 0.5s ease-out';
      testimonialsSlider.style.transform = `translateX(${newPosition}px)`;
      setTimeout(() => {
        // After transition, reset inline styles and restart the infinite scroll animation
        testimonialsSlider.style.transition = '';
        testimonialsSlider.style.transform = '';
        startAutoScroll();
      }, 500);
    } else {
      testimonialsSlider.style.animationPlayState = 'running';
    }
  }

  // Clone testimonials for continuous scrolling
  function setupInfiniteScroll() {
    const testimonials = testimonialsSlider.querySelectorAll('.testimonial');
    const initialWidth = testimonialsSlider.scrollWidth;
    const viewportWidth = window.innerWidth;
    const requiredWidth = viewportWidth * 3; // Ensuring enough content for seamless scrolling

    if (initialWidth < requiredWidth) {
      const originalTestimonials = Array.from(testimonials);
      let currentWidth = initialWidth;
      while (currentWidth < requiredWidth) {
        originalTestimonials.forEach(item => {
          const clone = item.cloneNode(true);
          testimonialsSlider.appendChild(clone);
          currentWidth += item.offsetWidth;
        });
      }
    }
  }

  // Initialize slider
  setupInfiniteScroll();
  startAutoScroll();

  // Restart the animation on window resize
  window.addEventListener('resize', () => {
    startAutoScroll();
  });
}