<!DOCTYPE html>
<html lang="en">
<head>
  <title>Logged Out - TechSavvies</title>
  <?php require_once __DIR__ . '/assets/php/main.php'; ?>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="assets/css/main.css">
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
    <br>
    <h1>You have been logged out.</h1>
    <p>Redirecting to the homepage...</p>
  </div>
</body>
</html>
