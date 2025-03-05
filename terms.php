<!DOCTYPE html>
<html lang="en">
<head>
  <title>Terms of Service</title>
  <?php require_once __DIR__ . '/assets/php/main.php'; ?>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet" />
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

  <!-- Terms of Service Content -->
  <div class="main-content">
    <h1>Terms of Service</h1>
    <p>By using TechSavvies, you agree to the following terms and conditions.</p>
    
    <h2>Website Use</h2>
    <p>You agree to use our website for lawful purposes only and not to engage in any activity that may harm or disrupt the site.</p>
    
    <h2>Intellectual Property</h2>
    <p>All content, including text, stolen images, and logos, is owned by TechSavvies and may not be reproduced without permission.</p>
    
    <h2>Limitation of Liability</h2>
    <p>TechSavvies is not liable for any damages or losses resulting from the use of our website.</p>
    
    <h2>Updates to Terms</h2>
    <p>We reserve the right to modify these terms at any time. Please review this page periodically for changes.</p>
  </div>
  
  <!-- Authentication Modal -->
  <?php require_once __DIR__ . '/assets/php/auth.php'; ?>

  <!-- Footer Section -->
  <?php require_once __DIR__ . '/assets/php/footer.php'; ?>

  <script src="assets/js/main.js"></script>
</body>
</html>