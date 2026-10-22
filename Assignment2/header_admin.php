<?php
// header_admin.php: shared header for every admin page (darker navigation bar).
// The page must call require_role('admin') BEFORE including this file.
require_once __DIR__ . '/includes/auth.php';
start_session_once();

$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gundam Hub Admin<?= isset($page_title) ? ' - ' . htmlspecialchars($page_title) : ''; ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@500;600;700&family=Inter:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style/main.css?v=<?= filemtime(__DIR__ . '/style/main.css') ?>">
    <link rel="stylesheet" href="style/admin.css?v=<?= filemtime(__DIR__ . '/style/admin.css') ?>">
    <script src="js/admin.js?v=<?= filemtime(__DIR__ . '/js/admin.js') ?>" defer></script>
</head>

<body class="admin-body">
    <nav class="topnav topnav-static topnav-admin">
        <div class="nav-left">
            <a href="main_menu_admin.php" class="brand-logo">GUNDAM HUB <span class="admin-badge">ADMIN</span></a>
        </div>

        <button class="hamburger-btn" id="hamburger-btn" aria-label="Toggle navigation">
            <span class="bar"></span>
            <span class="bar"></span>
            <span class="bar"></span>
        </button>

        <div class="nav-links" id="nav-links">
            <a href="main_menu_admin.php" class="<?= ($current_page === 'main_menu_admin.php') ? 'active' : ''; ?>">Home</a>
            <a href="manage_accounts.php" class="<?= ($current_page === 'manage_accounts.php') ? 'active' : ''; ?>">Accounts</a>
            <a href="manage_community.php" class="<?= ($current_page === 'manage_community.php') ? 'active' : ''; ?>">Community</a>
            <a href="manage_activities.php" class="<?= ($current_page === 'manage_activities.php') ? 'active' : ''; ?>">Activities</a>
            <a href="manage_smart_features.php" class="<?= ($current_page === 'manage_smart_features.php') ? 'active' : ''; ?>">Kits</a>
            <a href="manage_products.php" class="<?= ($current_page === 'manage_products.php') ? 'active' : ''; ?>">Products</a>
            <a href="manage_orders.php" class="<?= ($current_page === 'manage_orders.php') ? 'active' : ''; ?>">Orders</a>
            <a href="index.php" class="<?= ($current_page === 'index.php') ? 'active' : ''; ?>">View Website</a>

            <span class="nav-indicator"></span>

            <!-- Logout inside the mobile drawer -->
            <div class="nav-mobile-auth">
                <a href="logout.php" class="nav-cta">Log Out</a>
            </div>
        </div>

        <!-- Logout button (desktop) -->
        <div class="nav-right">
            <span class="admin-name"><?= htmlspecialchars($_SESSION['first_name'] ?? 'Admin'); ?></span>
            <a href="logout.php" class="nav-cta">Log Out</a>
        </div>
    </nav>
