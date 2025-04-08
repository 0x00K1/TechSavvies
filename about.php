<!DOCTYPE html>
<html lang="en">
<head>
  <title>About Us</title>
  <?php require_once __DIR__ . '/assets/php/main.php'; ?>
  <link rel="stylesheet" href="assets/css/main.css" />
  <style>
    body {
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }
    .main-content {
      flex: 1;
      max-width: 800px;
      margin: 140px auto;
      padding: 40px;
      background-color: #ffffff;
      border-radius: 10px;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
      line-height: 1.8;
    }

    .team-section {
      padding: 60px 20px;
      background: var(--light-bg);
      text-align: center;
    }
    .team-section .section-title {
      margin-bottom: 40px;
      font-size: 2em;
      color: var(--primary-color);
    }
    .team-container {
      display: flex;
      justify-content: center;
      gap: 40px;
      flex-wrap: wrap;
      margin-top: 20px;
    }
    .team-member {
      background: #fff;
      padding: 20px;
      border-radius: 12px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
      width: 250px;
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .team-member:hover {
      transform: translateY(-5px);
      box-shadow: 0 4px 15px rgba(0,0,0,0.2);
    }
    .team-member h3 {
      margin-bottom: 10px;
      font-size: 1.5em;
      color: var(--primary-color);
    }
    .team-member p {
      font-size: 1em;
      color: var(--text-color);
    }
    .team-member a {
      display: inline-block;
      color: var(--accent-color);
      text-decoration: none;
      font-weight: bold;
    }
    .team-member a:hover {
      text-decoration: underline;
    }
    /* Styling for the GitHub icon */
    .github-icon {
      width: 24px;
      height: 24px;
      vertical-align: middle;
    }
     
    /* Navigation styling adjustments (if needed) */
    nav ul {
      list-style: none;
      display: flex;
      gap: 20px;
    }
    nav ul li a {
      color: #fff;
      font-weight: 700;
      transition: color 0.3s ease;
    }
    nav ul li a:hover {
      color: var(--accent-color);
    }
  </style>
</head>
<body>
  <!-- Header Section -->
  <?php require_once __DIR__ . '/assets/php/header.php'; ?>

  <!-- Main Content -->
  <div class="main-content document-page">
    <!-- Team Section -->
    <section class="team-section">
      <h2 class="section-title">TechSavvies Team ^.^</h2>
      <div class="team-container">
        <!--#1-->
        <div class="team-member">
          <h3>Muhannad</h3>
          <p>
            <a href="https://github.com/0x00K1" target="_blank" rel="noopener noreferrer">
              <img src="assets/icons/github.svg" alt="GitHub Icon" class="github-icon" />
            </a>
          </p>
        </div>
        <!--#2-->
        <div class="team-member">
          <h3>Abdulrahman</h3>
          <p>
            <a href="https://github.com/AbdulrahmanQht" target="_blank" rel="noopener noreferrer">
              <img src="assets/icons/github.svg" alt="GitHub Icon" class="github-icon" />
            </a>
          </p>
        </div>
        <!--#3-->
        <div class="team-member">
          <h3>Anas</h3>
          <p>
            <a href="https://github.com/Ananas0dev" target="_blank" rel="noopener noreferrer">
              <img src="assets/icons/github.svg" alt="GitHub Icon" class="github-icon" />
            </a>
          </p>
        </div>
        <!--#4-->
        <div class="team-member">
          <h3>Nouh</h3>
          <p>
            <a href="https://github.com/NouhShamea" target="_blank" rel="noopener noreferrer">
              <img src="assets/icons/github.svg" alt="GitHub Icon" class="github-icon" />
            </a>
          </p>
        </div>
        <!--#5-->
        <div class="team-member">
          <h3>Omar</h3>
          <p>
            <a href="https://github.com/OmarAlnasser" target="_blank" rel="noopener noreferrer">
              <img src="assets/icons/github.svg" alt="GitHub Icon" class="github-icon" />
            </a>
          </p>
        </div>
        <!--#6-->
        <div class="team-member">
          <h3>Hameed</h3>
          <p>
            <a href="https://github.com/HameedJHD" target="_blank" rel="noopener noreferrer">
              <img src="assets/icons/github.svg" alt="GitHub Icon" class="github-icon" />
            </a>
          </p>
        </div>
        <!--#7-->
        <div class="team-member">
          <h3>Abdulaziz</h3>
          <p>
            <a href="https://github.com/ivaix" target="_blank" rel="noopener noreferrer">
              <img src="assets/icons/github.svg" alt="GitHub Icon" class="github-icon" />
            </a>
          </p>
        </div>
        <!--#8-->
        <div class="team-member">
          <h3>Abdulaziz</h3>
          <p>
            <a href="https://github.com/one1-zer0" target="_blank" rel="noopener noreferrer">
              <img src="assets/icons/github.svg" alt="GitHub Icon" class="github-icon" />
            </a>
          </p>
        </div>
      </div>
    </section>
  </div>

  <!-- Authentication Modal -->
  <?php require_once __DIR__ . '/assets/php/auth.php'; ?>
  
  <!-- Footer Section -->
  <?php require_once __DIR__ . '/assets/php/footer.php'; ?>
  
  <script src="assets/js/main.js"></script>
</body>
</html>
