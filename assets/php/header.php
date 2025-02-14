<?php
$current_page = $_SERVER['REQUEST_URI'];
$hide_home_on = ['/', '/logout.php']; 
$hide_account_on = ['/logout.php'];
?>
<header>
  <div class="logo">
    <a href="/">
      <img src="/assets/images/LogoName.webp" alt="TechSavvies Logo" />
    </a>
  </div>
  <nav>
    <ul>
      <?php if (!in_array($current_page, $hide_account_on)): ?>
        <li id="accountLink">
        <img src="/assets/images/account.png" alt="Account" id="accountIcon" />
      </li>
      <?php endif; ?>

      <?php 
      if (!in_array($current_page, $hide_home_on)): ?>
      <li id="homelink">
        <a href="/">
          <img src="/assets/icons/home.svg" alt="Home" id="homeIcon">
        </a>
      </li>
      <?php endif; ?>
    </ul>
  </nav>
</header>