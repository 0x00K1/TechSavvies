document.addEventListener('DOMContentLoaded', function() {
    // Initialize Google Map if the API is loaded
    if (typeof google !== 'undefined' && typeof google.maps !== 'undefined') {
        initMap();
    }
    
    // Handle FAQ toggles
    const faqItems = document.querySelectorAll('.faq-item');
    faqItems.forEach(item => {
        const question = item.querySelector('.faq-question');
        question.addEventListener('click', () => {
            // Close other open FAQs
            faqItems.forEach(otherItem => {
                if (otherItem !== item && otherItem.classList.contains('active')) {
                    otherItem.classList.remove('active');
                }
            });
            // Toggle current FAQ
            item.classList.toggle('active');
        });
    });
    
    // Contact Form Submission
    const contactForm = document.getElementById('contactForm');
    const messageStatus = document.getElementById('messageStatus');
    
    if (contactForm) {
        contactForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Create FormData object
            const formData = new FormData(contactForm);
            
            // Show loading message
            messageStatus.innerHTML = '<div class="message-loading">Sending your message...</div>';
            
            // Send form data using fetch
            fetch('../includes/FB_email.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Success message
                    messageStatus.innerHTML = '<div class="message-success">' + data.message + '</div>';
                    
                    // Clear form
                    contactForm.reset();
                    
                    // Scroll to message status
                    messageStatus.scrollIntoView({ behavior: 'smooth', block: 'center' });
                } else {
                    // Error message
                    messageStatus.innerHTML = '<div class="message-error">' + data.message + '</div>';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                messageStatus.innerHTML = '<div class="message-error">An error occurred. Please try again later.</div>';
            });
        });
    }
});

// Google Maps initialization function
function initMap() {
    const shopLocation = { lat: 0, lng: 0 }; // :)
    
    const map = new google.maps.Map(document.getElementById('googleMap'), {
        zoom: 15,
        center: shopLocation,
        styles: [
            {
                "featureType": "all",
                "elementType": "labels.text.fill",
                "stylers": [{"saturation": 36}, {"color": "#333333"}, {"lightness": 40}]
            },
            {
                "featureType": "all",
                "elementType": "labels.text.stroke",
                "stylers": [{"visibility": "on"}, {"color": "#ffffff"}, {"lightness": 16}]
            },
            {
                "featureType": "all",
                "elementType": "labels.icon",
                "stylers": [{"visibility": "off"}]
            },
            {
                "featureType": "administrative",
                "elementType": "geometry.fill",
                "stylers": [{"color": "#fefefe"}, {"lightness": 20}]
            },
            {
                "featureType": "administrative",
                "elementType": "geometry.stroke",
                "stylers": [{"color": "#fefefe"}, {"lightness": 17}, {"weight": 1.2}]
            },
            {
                "featureType": "landscape",
                "elementType": "geometry",
                "stylers": [{"color": "#f5f5f5"}, {"lightness": 20}]
            },
            {
                "featureType": "poi",
                "elementType": "geometry",
                "stylers": [{"color": "#f5f5f5"}, {"lightness": 21}]
            },
            {
                "featureType": "poi.park",
                "elementType": "geometry",
                "stylers": [{"color": "#dedede"}, {"lightness": 21}]
            },
            {
                "featureType": "road.highway",
                "elementType": "geometry.fill",
                "stylers": [{"color": "#ffffff"}, {"lightness": 17}]
            },
            {
                "featureType": "road.highway",
                "elementType": "geometry.stroke",
                "stylers": [{"color": "#ffffff"}, {"lightness": 29}, {"weight": 0.2}]
            },
            {
                "featureType": "road.arterial",
                "elementType": "geometry",
                "stylers": [{"color": "#ffffff"}, {"lightness": 18}]
            },
            {
                "featureType": "road.local",
                "elementType": "geometry",
                "stylers": [{"color": "#ffffff"}, {"lightness": 16}]
            },
            {
                "featureType": "transit",
                "elementType": "geometry",
                "stylers": [{"color": "#f2f2f2"}, {"lightness": 19}]
            },
            {
                "featureType": "water",
                "elementType": "geometry",
                "stylers": [{"color": "#e9e9e9"}, {"lightness": 17}]
            }
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