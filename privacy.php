<!DOCTYPE html>
<html lang="en">
<head>
  <title>Privacy Policy</title>
  <?php require_once __DIR__ . '/assets/php/main.php'; ?>
  <link rel="stylesheet" href="assets/css/main.css" />
  <style>
    .main-content {
      max-width: 800px;
      margin: 140px auto;
      padding: 40px;
      background-color: #ffffff;
      border-radius: 10px;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
      line-height: 1.8;
    }
    .main-content h1 {
      font-size: 2.5em;
      margin-bottom: 20px;
      text-align: center;
      color: var(--primary-color);
      position: relative;
    }
    .main-content h1::after {
      content: '';
      display: block;
      width: 60px;
      height: 3px;
      background: linear-gradient(to right, #0117ff, #8d07cc, #d42d2d);
      margin: 10px auto 0;
      border-radius: 2px;
    }

    .main-content h2 {
      font-size: 1.8em;
      margin-top: 40px;
      margin-bottom: 15px;
      color: var(--primary-color);
      border-bottom: 3px solid;
      padding-bottom: 5px;
      border-image: linear-gradient(to right, #0117ff, #8d07cc, #d42d2d);
      border-image-slice: 1;
    }
    .main-content p {
      font-size: 1.1em;
      margin-bottom: 20px;
      color: var(--text-color);
    }
    .main-content a {
      color: var(--accent-color);
      text-decoration: none;
    }
    .main-content a:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>
  <!-- Header Section -->
  <?php require_once __DIR__ . '/assets/php/header.php'; ?>

  <!-- Privacy Policy Content -->
  <div class="main-content">
    <h1>Privacy Policy</h1>
    <p>Your privacy is important to us at TechSavvies. This policy explains what information we collect, how we use it, and the choices you have regarding your data.</p>
    
    <h2>Information We Collect</h2>
    <p>We may collect details such as your name, email address, and browsing activity when you interact with our website.</p>
    
    <h2>How We Use Your Information</h2>
    <p>Your data helps us enhance your shopping experience, process orders, and provide you with personalized content and offers.</p>
    
    <h2>Data Security</h2>
    <p>We employ a variety of security measures to protect your personal information, though no transmission method over the Internet is completely secure.</p>
    
    <h2>Your Rights</h2>
    <p>You have the right to access, update, or delete your personal information. Contact our support team if you have any concerns.</p>
  </div>
  
  <!-- Authentication Modal -->
  <?php require_once __DIR__ . '/assets/php/auth.php'; ?>

  <!-- Footer Section -->
  <?php require_once __DIR__ . '/assets/php/footer.php'; ?>
  
  <script src="assets/js/main.js"></script>
</body>
</html>