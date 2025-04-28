<?php
require_once __DIR__.'/secure_session.php';
require_once __DIR__.'/db.php';

/* 0.  Non-root → 403 */
if (empty($_SESSION['is_root'])) {
    http_response_code(403);
    exit;
}

/* 1.  Already verified for dashboard */
if (isset($_SESSION['root_id'])) {
    return;
}

/* 2.  Root, but not yet verified -> check password once */
$rootId = $_SESSION['pending_root_id'] ?? null;
if (!$rootId) { http_response_code(403); exit; }

/* --- brute-force bookkeeping (same keys used in auth file) --- */
$ip   = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
$key  = 'root_pw_fails_' . md5($ip);
$_SESSION[$key] = $_SESSION[$key] ?? ['cnt'=>0,'time'=>0];
$too_many = ($_SESSION[$key]['cnt'] >= 5) &&
            (time() - $_SESSION[$key]['time'] < 900);

/* --- handle POST ------------------------------------------------ */
$error = '';
if ($_SERVER['REQUEST_METHOD']==='POST' && !$too_many && isset($_POST['pass'])) {

    $stmt = $pdo->prepare("SELECT password FROM roots WHERE root_id = ?");
    $stmt->execute([$rootId]);
    $hash = $stmt->fetchColumn();

    if ($hash && password_verify($_POST['pass'],$hash)) {
        // success -> elevate
        $_SESSION['root_id'] = $rootId;
        unset($_SESSION['pending_root_id']);
        $_SESSION[$key] = ['cnt'=>0,'time'=>0];          // reset
        header('Location: '.$_SERVER['REQUEST_URI']);
        exit;
    }

    // fail
    $_SESSION[$key]['cnt']++;
    $_SESSION[$key]['time']=time();
    $error='Incorrect password';
    http_response_code(403);
}

/* 3.  Display root auth */
define('__ROOT_AUTH__',true);
require_once $_SERVER['DOCUMENT_ROOT'].'/assets/php/root_php/root_auth.php';