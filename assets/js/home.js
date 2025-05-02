document.addEventListener("DOMContentLoaded", () => {
    // --- Popular Products Slider ---
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
    // Removed problematic hover effect JavaScript - now handled by CSS
  }
);

// --- Testimonials Slider ---
document.addEventListener("DOMContentLoaded", () => {
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
});

// --- Fix hover effects on document load ---
document.addEventListener("DOMContentLoaded", function() {
  // Fix for Shop Now button
  const shopNowButton = document.querySelector(".hero-content a");
  if (shopNowButton) {
    // Add a class to prevent hover text disappearing
    shopNowButton.classList.add("shop-now-button");
  }
  
  // Fix for View All Orders button
  const viewAllButton = document.querySelector(".view-all-btn");
  if (viewAllButton) {
    // Add a class to prevent hover text disappearing
    viewAllButton.classList.add("view-all-fixed");
  }
  
  // Fix for category navigation buttons
  const categoryButtons = document.querySelectorAll(".category-navigation .buttons");
  categoryButtons.forEach(button => {
    button.classList.add("category-button-fixed");
  });
});