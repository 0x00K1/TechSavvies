<?php
require_once __DIR__ . '/../../includes/secure_session.php';
$current_page = $_SERVER['REQUEST_URI'];
$hide_home_on = ['/', '/logout.php']; 
$hide_account_on = ['/logout.php'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '', ENT_QUOTES) ?>">
  <link rel="icon" href="assets/icons/Logo.ico" sizes="64x64" />
</head>
<body>

<!-- Header Section -->
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

<!-- Authentication Modal -->
<div class="auth-modal" id="authModal">
  <div class="auth-modal-content">
    <a class="close" id="closeModal">&times;</a>
    
    <!-- Step 1: Enter Email -->
    <div class="auth-step" id="authStep1">
      <img src="/assets/images/logo.png" alt="Site Logo" />
      <p>Sign in/up for savvy shopping</p>
      <input type="email" id="authEmail" placeholder="Email Address" />
      <button id="sendOtpBtn">Send OTP</button>
    </div>

    <!-- Step 2: Enter OTP -->
    <div class="auth-step" id="authStep2" style="display: none;">
      <h2>Verify It's You</h2>
      <p>We've sent a code to your email</p>
      <input type="text" id="authOTP" maxlength="6" />
      <button id="verifyOtpBtn">Verify & Continue</button>
    </div>
  </div>
</div>