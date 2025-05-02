document.addEventListener('DOMContentLoaded', function() {
    const contactForm = document.getElementById('contactForm');
    const messageStatus = document.getElementById('messageStatus');

    // Utility: show or clear field error
    function showError(input, msg) {
        const container = input.closest('.form-group');
        container.classList.add('error');
        let err = container.querySelector('.error-message');
        if (!err) {
            err = document.createElement('div');
            err.className = 'error-message';
            err.style.color = 'red';
            container.appendChild(err);
        }
        err.textContent = msg;
    }
    function clearError(input) {
        const container = input.closest('.form-group');
        container.classList.remove('error');
        const err = container.querySelector('.error-message');
        if (err) container.removeChild(err);
    }

    // Validation rules
    function validateField(input) {
        const name = input.name;
        const val = input.value.trim();

        if (name === 'name') {
            if (val.length < 3) {
                showError(input, 'Please enter at least 3 characters.');
                return false;
            }
        }
        if (name === 'email') {
            const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!re.test(val)) {
                showError(input, 'Please enter a valid email address.');
                return false;
            }
        }
        if (name === 'subject') {
            if (!val) {
                showError(input, 'Please select a subject.');
                return false;
            }
        }
        if (name === 'message') {
            if (val.length < 10) {
                showError(input, 'Message must be at least 10 characters.');
                return false;
            }
        }
        return true;
    }

    if (contactForm) {
        // Clear error on focus
        contactForm.querySelectorAll('input, select, textarea').forEach(inp => {
            inp.addEventListener('focus', () => clearError(inp));
        });

        // Form submission
        contactForm.addEventListener('submit', function(e) {
            e.preventDefault();
            let valid = true;
            messageStatus.innerHTML = ''; // clear status

            contactForm.querySelectorAll('input, select, textarea').forEach(inp => {
                clearError(inp);
                if (!validateField(inp)) valid = false;
            });
            if (!valid) {
                return;
            }

            // Proceed with AJAX send
            messageStatus.innerHTML = '<div class="message-loading">Sending your message...</div>';
            const formData = new FormData(contactForm);

            fetch(contactForm.action, { method: 'POST', body: formData })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        messageStatus.innerHTML = '<div class="message-success">' + data.message + '</div>';
                        contactForm.reset();
                        window.scrollTo({ top: messageStatus.offsetTop - 20, behavior: 'smooth' });
                    } else {
                        messageStatus.innerHTML = '<div class="message-error">' + data.message + '</div>';
                    }
                })
                .catch(() => {
                    messageStatus.innerHTML = '<div class="message-error">An unexpected error occurred.</div>';
                });
        });
    }

    // Initialize Google Map if API loaded
    if (typeof google !== 'undefined' && typeof google.maps !== 'undefined') {
        initMap();
    }

    // Handle FAQ toggles
    const faqItems = document.querySelectorAll('.faq-item');
    faqItems.forEach(item => {
        const question = item.querySelector('.faq-question');
        question.addEventListener('click', () => {
            faqItems.forEach(other => {
                if (other !== item) other.classList.remove('active');
            });
            item.classList.toggle('active');
        });
    });
});

// Google Maps initialization
function initMap() {
    const shopLocation = { lat: 0, lng: 0 }; // Replace with actual coordinates
    const map = new google.maps.Map(document.getElementById('googleMap'), {
        zoom: 15,
        center: shopLocation,
        styles: [
            {"featureType":"all","elementType":"labels.text.fill","stylers":[{"saturation":36},{"color":"#333333"},{"lightness":40}]},
            {"featureType":"all","elementType":"labels.text.stroke","stylers":[{"visibility":"on"},{"color":"#ffffff"},{"lightness":16}]},
            {"featureType":"all","elementType":"labels.icon","stylers":[{"visibility":"off"}]},
            {"featureType":"administrative","elementType":"geometry.fill","stylers":[{"color":"#fefefe"},{"lightness":20}]},
            {"featureType":"administrative","elementType":"geometry.stroke","stylers":[{"color":"#fefefe"},{"lightness":17},{"weight":1.2}]},
            {"featureType":"landscape","elementType":"geometry","stylers":[{"color":"#f5f5f5"},{"lightness":20}]},
            {"featureType":"poi","elementType":"geometry","stylers":[{"color":"#f5f5f5"},{"lightness":21}]},
            {"featureType":"poi.park","elementType":"geometry","stylers":[{"color":"#dedede"},{"lightness":21}]},
            {"featureType":"road.highway","elementType":"geometry.fill","stylers":[{"color":"#ffffff"},{"lightness":17}]},
            {"featureType":"road.highway","elementType":"geometry.stroke","stylers":[{"color":"#ffffff"},{"lightness":29},{"weight":0.2}]},
            {"featureType":"road.arterial","elementType":"geometry","stylers":[{"color":"#ffffff"},{"lightness":18}]},
            {"featureType":"road.local","elementType":"geometry","stylers":[{"color":"#ffffff"},{"lightness":16}]},
            {"featureType":"transit","elementType":"geometry","stylers":[{"color":"#f2f2f2"},{"lightness":19}]},
            {"featureType":"water","elementType":"geometry","stylers":[{"color":"#e9e9e9"},{"lightness":17}]}
        ]
    });

    // Add marker for shop location
    const marker = new google.maps.Marker({
        position: shopLocation,
        map: map,
        title: 'TechSavvies Shop',
        animation: google.maps.Animation.DROP,
        icon: {
            path: google.maps.SymbolPath.CIRCLE,
            scale: 10,
            fillColor: "#8d07cc",
            fillOpacity: 0.5,
            strokeWeight: 2,
            strokeColor: "#0117ff"
        }
    });

    // Add info window
    const infoWindow = new google.maps.InfoWindow({
        content: '<div class="map-info-window"><h3>TechSavvies Shop</h3><p>Your one-stop tech shop!</p><p>Sunday - Thursday: 9AM - 6PM<br>Weekend: 10AM - 4PM</p></div>'
    });

    marker.addListener('click', function() {
        infoWindow.open(map, marker);
    });
}