<?php
// includes/auth.php
// Session helpers shared by every page.
//
// Session variables set at login:
//   $_SESSION['email']      the account's email
//   $_SESSION['type']       "user" or "admin"
//   $_SESSION['first_name'] shown in the navigation bar
//   $_SESSION['logged_in']  true (kept so Assignment 1 pages keep working)

function start_session_once(): void
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
}

function is_logged_in(): bool
{
    start_session_once();
    return isset($_SESSION['email'], $_SESSION['type']);
}

// Call at the very top of a page, BEFORE any HTML or include 'header.php'.
// Sends anyone who is not logged in as $type to login.php.
function require_role(string $type): void
{
    start_session_once();

    if (!is_logged_in() || $_SESSION['type'] !== $type) {
        $target = 'login.php';
        if ($type === 'user') {
            // Send the user back here after logging in.
            $target .= '?redirect=' . urlencode(basename($_SERVER['REQUEST_URI']));
        }
        header('Location: ' . $target);
        exit;
    }
}

// Stores the login in the session. Regenerating the id prevents session fixation.
function login_session(string $email, string $type, string $first_name): void
{
    start_session_once();
    session_regenerate_id(true);
    $_SESSION['email']      = $email;
    $_SESSION['type']       = $type;
    $_SESSION['first_name'] = $first_name;
    $_SESSION['logged_in']  = true;
}

// Only allows redirecting to a page inside this site, e.g. "activities.php?x=1#top".
function safe_redirect_target(string $target, string $fallback): string
{
    $target = urldecode($target);
    // Pages link with "from=/COS30020/activities.php#x": keep only the file name onward,
    // so the result is always a relative link.
    $target = preg_replace('#^.*/(?=[A-Za-z0-9_\-]+\.php)#', '', $target);
    if (preg_match('/^[A-Za-z0-9_\-]+\.php(\?[A-Za-z0-9_=&%.\-]*)?(#[A-Za-z0-9_\-]*)?$/', $target)) {
        return $target;
    }
    return $fallback;
}

// Adds ?flag=1 to a URL, keeping any #fragment at the end.
function add_flag(string $url, string $flag): string
{
    $fragment = '';
    $pos = strpos($url, '#');
    if ($pos !== false) {
        $fragment = substr($url, $pos);
        $url = substr($url, 0, $pos);
    }
    $sep = (strpos($url, '?') !== false) ? '&' : '?';
    return $url . $sep . $flag . '=1' . $fragment;
}
