<?php
/* ---------------------------------------------------------
   Root dashboard – secondary password prompt
   Included by  /includes/root_guard.php   when the root
   account has been identified but not yet elevated.
   --------------------------------------------------------- */

if (!defined('__ROOT_AUTH__')) {            // guard against direct hits
    http_response_code(403);
    exit;
}

/*  ─── Tiny brute-force throttle ──────────────────────────
    · after 5 wrong tries block the form for 15 minutes
    · per-session AND per-IP (quick & simple)
   ------------------------------------------------------- */
$ip   = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
$key  = 'root_pw_fails_' . md5($ip);
$fail = $_SESSION[$key]['cnt']  ?? 0;
$when = $_SESSION[$key]['time'] ?? 0;
$wait = max(0, ($when + 900) - time());   // 15 min => 900 s

// tell the guard whether it should bail out
$too_many = ($fail >= 3) && ($wait > 0);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<?php require_once __DIR__ . '/../main.php'; ?>
<title>Root</title>
<link rel="stylesheet" href="/assets/css/main.css?v=<?=filemtime($_SERVER['DOCUMENT_ROOT'].'/assets/css/main.css')?>">
<style>
:root {
  --primary-color: #1a1a2e;
  --secondary-color: #e0e0e0;
  --accent-color: #4169e1;
  --light-bg: #f8f9fa;
  --text-color: #333333;
  --header-bg: #1a1a2e;
  --gradient: linear-gradient(135deg, #d42d2d, #8d07cc, #0117ff);
  --success-color: #28a745;
  --danger-color: #dc3545;
  --warning-color: #ffc107;
  --info-color: #17a2b8;
  --process-color: #0099ff;
  --border-color: #dee2e6;
  --border-radius: 6px;
  --box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  --transition: all 0.3s ease;
}

body {
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
    background: var(--light-bg);
    font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
    margin: 0;
    padding: 0;
}

.auth-container {
    width: 100%;
    max-width: 400px;
    padding: 20px;
}

.root-auth-card {
    background: #ffffff;
    padding: 32px 40px;
    border-radius: var(--border-radius);
    box-shadow: var(--box-shadow);
    width: 100%;
    text-align: center;
    position: relative;
    overflow: hidden;
}

.root-auth-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: var(--gradient);
}

.logo-container {
    margin-bottom: 24px;
}

.logo-container img {
    height: 150px;
    width: auto;
}

.root-auth-card h1 {
    font-size: 1.25rem;
    margin: 0 0 24px;
    color: var(--primary-color);
    font-weight: 600;
}

.input-group {
    position: relative;
    margin-bottom: 20px;
}

.input-group input {
    width: 100%;
    padding: 12px 12px;
    font-size: 0.95rem;
    border: 1px solid var(--border-color);
    border-radius: var(--border-radius);
    transition: var(--transition);
}

.input-group input:focus {
    outline: none;
    border-color: var(--accent-color);
    box-shadow: 0 0 0 3px rgba(65, 105, 225, 0.2);
}

.root-auth-card button {
    width: 100%;
    padding: 12px 0;
    font-size: 0.95rem;
    background: linear-gradient(135deg, #4169e1, #9370db);
    color: #fff;
    border: 0;
    border-radius: var(--border-radius);
    cursor: pointer;
    transition: var(--transition);
    font-weight: 500;
}

.root-auth-card button:hover {
    background: linear-gradient(135deg, #9370db, #4169e1);
    transform: translateY(-1px);
}

.root-auth-card button:active {
    transform: translateY(0);
}

.root-auth-card .err {
    color: var(--danger-color);
    font-size: 0.875rem;
    margin-top: 16px;
    padding: 8px;
    background-color: rgba(220, 53, 69, 0.1);
    border-radius: var(--border-radius);
}

.root-auth-card .lock {
    color: var(--text-color);
    font-size: 0.9rem;
    margin-top: 16px;
    padding: 16px;
    background-color: rgba(255, 193, 7, 0.1);
    border-radius: var(--border-radius);
    border-left: 3px solid var(--warning-color);
}

.lock-icon {
    display: block;
    margin: 10px auto;
    font-size: 32px;
    color: var(--warning-color);
}
</style>
</head>
<body>
    <div class="auth-container">
        <form class="root-auth-card" method="post">
            <div class="logo-container">
                <img src="/assets/images/logo.webp" alt="Company Logo">
            </div>
            
            <h1>Root Access</h1>

            <?php if(!$too_many): ?>
                <div class="input-group">
                    <input  type="password"
                            name="pass"
                            placeholder="Enter root password"
                            autocomplete="current-password"
                            required
                    >
                </div>
                <button type="submit">Authenticate</button>
                <?php if(!empty($error)) echo '<div class="err">'.$error.'</div>'; ?>
            <?php else: ?>
                <div class="lock">
                    <span class="lock-icon">&#128274;</span>
                    <strong>Access temporarily blocked</strong><br>
                    Too many failed attempts.<br>
                    Please try again in <?=ceil($wait/60)?> minutes.
                </div>
            <?php endif; ?>
        </form>
    </div>
</body>
</html>
<?php die(); ?>