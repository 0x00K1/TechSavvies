<?php
require_once __DIR__ . '/../../includes/secure_session.php';
?>
<meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '', ENT_QUOTES) ?>">
  <link rel="icon" href="assets/icons/Logo.ico" sizes="64x64" />