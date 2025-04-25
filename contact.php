<?php
require_once __DIR__ . '/includes/secure_session.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Contact Us - TechSavvies</title>
  <?php require_once __DIR__ . '/assets/php/main.php'; ?>
  <link rel="stylesheet" href="assets/css/main.css">
  <link rel="stylesheet" href="assets/css/contact.css">
  <!-- Google Maps API -->
  <script src="https://maps.googleapis.com/maps/api/js?key=<LATER>&callback=initMap" async defer></script>
</head>
<body>
  <!-- Header Section -->
  <?php require_once __DIR__ . '/assets/php/header.php'; ?>

  <!-- Contact Hero Section -->
  <section class="contact-hero">
    <div class="contact-hero-content">
      <h1>Get in Touch</h1>
      <p>We'd love to hear from you! Send us a message and we'll respond as soon as possible.</p>
    </div>
  </section>

  <!-- Main Content -->
  <div class="main-content">
    <div class="contact-container">
      <!-- Contact Information -->
      <section class="contact-info">
        <h2>Contact Information</h2>
        <div class="info-card">
          <div class="info-item">
            <div class="info-icon">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
            </div>
            <div class="info-text">
              <h3>Phone</h3>
              <p>+966 501010101</p>
            </div>
          </div>
          <div class="info-item">
            <div class="info-icon">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
            </div>
            <div class="info-text">
              <h3>Email</h3>
              <p>support@techsavvies.shop</p>
            </div>
          </div>
          <div class="info-item">
            <div class="info-icon">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
            </div>
            <div class="info-text">
              <h3>Address</h3>
              <p>01 TechSavvies, Tech City, TC 10101</p>
            </div>
          </div>
          <div class="info-item">
            <div class="info-icon">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
            </div>
            <div class="info-text">
              <h3>Hours</h3>
              <p>Sunday - Thursday: 9AM - 6PM<br>Weekend: 10AM - 4PM</p>
            </div>
          </div>
        </div>
        <div class="social-links">
          <h3>Connect With Us</h3>
          <div class="social-icons">
            <a href="#" class="social-icon" aria-label="Facebook">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path></svg>
            </a>
            <a href="#" class="social-icon" aria-label="Twitter">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M23 3a10.9 10.9 0 0 1-3.14 1.53 4.48 4.48 0 0 0-7.86 3v1A10.66 10.66 0 0 1 3 4s-4 9 5 13a11.64 11.64 0 0 1-7 2c9 5 20 0 20-11.5a4.5 4.5 0 0 0-.08-.83A7.72 7.72 0 0 0 23 3z"></path></svg>
            </a>
            <a href="#" class="social-icon" aria-label="Instagram">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line></svg>
            </a>
            <a href="#" class="social-icon" aria-label="LinkedIn">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"></path><rect x="2" y="9" width="4" height="12"></rect><circle cx="4" cy="4" r="2"></circle></svg>
            </a>
          </div>
        </div>
      </section>

      <!-- Contact Form -->
      <section class="contact-form-section">
        <h2>Send Us a Message</h2>
        <form id="contactForm" action="/includes/FB_email.php" method="POST">
          <div class="form-group">
            <label for="name">Your Name</label>
            <input type="text" id="name" name="name" placeholder="Full Name" required />
          </div>
          <div class="form-group">
            <label for="email">Your Email</label>
            <input type="email" id="email" name="email" placeholder="email@example.com" required />
          </div>
          <div class="form-group">
            <label for="subject">Subject</label>
            <select id="subject" name="subject" required>
              <option value="" disabled selected>Select a subject</option>
              <option value="general">General Inquiry</option>
              <option value="support">Product Support</option>
              <option value="feedback">Website Feedback</option>
              <option value="order">Order Status</option>
              <option value="other">Other</option>
            </select>
          </div>
          <div class="form-group">
            <label for="message">Your Message</label>
            <textarea id="message" name="message" rows="5" placeholder="How can we help you?" required></textarea>
          </div>
          <button type="submit">Send Message</button>
        </form>
        <div id="messageStatus"></div>
      </section>
    </div>

    <!-- Map Section -->
    <section class="map-section">
      <h2>Find Us</h2>
      <div class="map-container">
        <div id="googleMap"></div>
        <div class="map-address">
          <h3>TechSavvies Shop</h3>
          <p>01 TechSavvies, Tech City, TC 10101</p>
          <p>Open Monday - Friday: 9AM - 6PM<br>Weekend: 10AM - 4PM</p>
          <a href="https://maps.google.com/?q=TechSavvies" target="_blank" class="directions-btn">Get Directions</a>
        </div>
      </div>
    </section>

    <!-- FAQ Section -->
    <section class="faq-section">
      <h2>Frequently Asked Questions</h2>
      <div class="faq-container">
        <div class="faq-item">
          <div class="faq-question">
            <h3>How long does shipping take?</h3>
            <span class="faq-toggle">+</span>
          </div>
          <div class="faq-answer">
            <p>Standard shipping typically takes 3-5 business days. Express shipping options are available at checkout for 1-2 business day delivery.</p>
          </div>
        </div>
        <div class="faq-item">
          <div class="faq-question">
            <h3>What is your return policy?</h3>
            <span class="faq-toggle">+</span>
          </div>
          <div class="faq-answer">
            <p>We offer a 30-day return policy for most products. Items must be unopened and in their original packaging to be eligible for a full refund.</p>
          </div>
        </div>
        <div class="faq-item">
          <div class="faq-question">
            <h3>Do you ship internationally?</h3>
            <span class="faq-toggle">+</span>
          </div>
          <div class="faq-answer">
            <p>Yes, we ship to most countries worldwide. International shipping rates and delivery times vary depending on the destination.</p>
          </div>
        </div>
        <div class="faq-item">
          <div class="faq-question">
            <h3>How can I track my order?</h3>
            <span class="faq-toggle">+</span>
          </div>
          <div class="faq-answer">
            <p>Once your order ships, you'll receive a tracking number via email. You can also check your order status by logging into your account on our website.</p>
          </div>
        </div>
      </div>
    </section>
  </div>

  <!-- Footer Section -->
  <?php require_once __DIR__ . '/assets/php/footer.php'; ?>

  <script src="/assets/js/main.js"></script>
  <script src="/assets/js/contact.js"></script>
</body>
</html>