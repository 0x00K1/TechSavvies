<!DOCTYPE html>
<html lang="en">
<head>
  <title>Logged Out</title>
  <?php require_once __DIR__ . '/assets/php/main.php'; ?>
  <link rel="stylesheet" href="assets/css/main.css">

  <!-- Auto-redirect after 3 seconds -->
  <script>
    document.addEventListener("DOMContentLoaded", function() {
      console.log("User logged out.");
      setTimeout(function() {
        window.location.href = "/";
      }, 3000);
    });
  </script>
</head>
<body>
  <!-- Header Section -->
  <?php require_once __DIR__ . '/assets/php/header.php'; ?>

  <!-- Logout Message -->
  <div class="main-content" style="text-align: center; padding: 50px;">
    <img
      src="/assets/images/logout.png"
      alt="Logged Out"
      style="max-width:200px; margin-top:100px; margin-bottom:20px; display:block; margin-left:auto; margin-right:auto;"
    />
    <h1>You have been logged out.</h1>
    <p>Redirecting to the homepage...</p>
  </div>
</body>
</html>
