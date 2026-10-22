<?php
require_once 'includes/auth.php';
start_session_once();

// Clear the session data and the session cookie, then destroy the session.
$_SESSION = [];
if (ini_get('session.use_cookies')) {
    $p = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000, $p['path'], $p['domain'], $p['secure'], $p['httponly']);
}
session_destroy();

header('Location: index.php?logged_out=1');
exit;
