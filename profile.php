<!DOCTYPE html>
<html lang="en">
<head>
  <title>Profile - TechSavvies</title>
  <?php require_once __DIR__ . '/assets/php/main.php'; ?>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="assets/css/main.css" />
</head>
<body>
  <!-- Header Section -->
  <?php require_once __DIR__ . '/assets/php/header.php'; ?>

  <!-- Profile Content (require logic)-->
  <div class="main-content">
    <h1>Your Profile</h1>
    <p>Welcome, [Username]! Here you can update your information, review your orders, and manage your account settings.</p>
    <!-- Additional profile details can be added here -->
  </div>

  <!-- Authentication Modal -->
  <?php require_once __DIR__ . '/assets/php/auth.php'; ?>
  
  <script src="assets/js/main.js"></script>
</body>
</html>
