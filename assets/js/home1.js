document.addEventListener("DOMContentLoaded", function () {
  const shopNowButton = document.querySelector(".hero-content a span");

  shopNowButton.addEventListener("mouseenter", function () {
      shopNowButton.style.opacity = "1";
      shopNowButton.style.transform = "translateY(-5px)";
  });

  shopNowButton.addEventListener("mouseleave", function () {
      shopNowButton.style.opacity = "1";
      shopNowButton.style.transform = "translateY(0)";
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

  // --- Contact Form Functionality ---
  function showPopupMessage(success, message) {
    console.log(`Showing popup - Success: ${success}, Message: ${message}`);
    
    const popup = document.createElement('div');
    popup.style.position = 'fixed';
    popup.style.top = '20px';
    popup.style.right = '20px';
    popup.style.padding = '20px';
    popup.style.background = '#fff';
    popup.style.border = '2px solid ' + (success ? '#8d07cc' : '#d42d2d');
    popup.style.zIndex = '9999';
    popup.style.boxShadow = '0 4px 20px rgba(0,0,0,0.2)';
    popup.style.borderRadius = '8px';
    popup.style.minWidth = '300px';
    
    const icon = document.createElement('span');
    icon.innerHTML = success ? '✓' : '✕';
    icon.style.fontSize = '24px';
    icon.style.display = 'block';
    icon.style.marginBottom = '10px';
    icon.style.color = success ? '#8d07cc' : '#d42d2d';
    
    const title = document.createElement('h3');
    title.textContent = success ? 'Success!' : 'Error';
    title.style.margin = '0 0 10px 0';
    
    const text = document.createElement('p');
    text.textContent = message;
    text.style.margin = '0';
    
    popup.appendChild(icon);
    popup.appendChild(title);
    popup.appendChild(text);
    
    popup.style.animation = 'slideIn 0.3s ease-out';
    const style = document.createElement('style');
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
        popup.style.animation = 'slideOut 0.3s ease-out';
        setTimeout(() => {
            popup.remove();
            style.remove();
        }, 300);
    }, 3000);
  }

  const contactForm = document.getElementById('contactForm');
  if (contactForm) {
    contactForm.addEventListener('submit', function(e) {
      e.preventDefault();
      
      const formData = new FormData(this);
      const submitButton = this.querySelector('button[type="submit"]');
      submitButton.disabled = true;
      
      fetch('assets/php/send_email.php', {
          method: 'POST',
          headers: {
              'Accept': 'application/json'  // Add this line to explicitly request JSON response
          },
          body: formData
      })
      .then(response => {
          if (!response.ok) {
              throw new Error('Network response was not ok');
          }
          return response.json();
      })
      .then(data => {
          showPopupMessage(data.success, data.message);
          if(data.success) {
              this.reset();
          }
      })
      .catch(error => {
          console.error('Error:', error);
          showPopupMessage(false, 'An error occurred. Please try again.');
      })
      .finally(() => {
          submitButton.disabled = false;
      });
    });
  }
});
