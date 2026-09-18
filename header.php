<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gundam Hub<?= isset($page_title) ? ' - ' . htmlspecialchars($page_title) : ''; ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@500;600;700&family=Inter:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style/main.css">
</head>

<body>
    <?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }


    $current_page = basename($_SERVER['PHP_SELF']); ?>

    <nav class="topnav">
        <div class="nav-left">
            <a href="index.php" class="brand-logo">GUNDAM HUB</a>
        </div>

        <!-- Mobile hamburger menu toggle button -->
        <button class="hamburger-btn" id="hamburger-btn" aria-label="Toggle navigation">
            <span class="bar"></span>
            <span class="bar"></span>
            <span class="bar"></span>
        </button>

        <!-- Top navigation links container -->
        <div class="nav-links" id="nav-links">
            <a href="index.php" class="<?= ($current_page === 'index.php') ? 'active' : ''; ?>">Home</a>
            <a href="main_menu.php" class="<?= ($current_page === 'main_menu.php') ? 'active' : ''; ?>">Main Menu</a>

            <!-- Series Guide dropdown menu-->
            <div class="nav-item-dropdown">
                <a href="series.php" class="dropdown-trigger <?= in_array($current_page, ['series.php', 'series_detail.php'], true) ? 'active' : ''; ?>">
                    Series Guide <span class="arrow-indicator">▾</span>
                </a>
                <div class="dropdown-menu">
                    <a href="series.php#gundam-animation" class="dropdown-link">Animation</a>
                    <a href="series.php#cat-collectibles" class="dropdown-link">Collectibles</a>
                    <a href="series.php#cat-games" class="dropdown-link">Video Games</a>
                </div>
            </div>

           <!-- Activities dropdown menu -->
            <div class="nav-item-dropdown">
                <a href="activities.php" class="dropdown-trigger <?= ($current_page === 'activities.php') ? 'active' : ''; ?>">
                    Activities <span class="arrow-indicator">▾</span>
                </a>
                <div class="dropdown-menu">
                    <a href="activities.php#workshop" class="dropdown-link">Workshops</a>
                    <a href="activities.php#competition" class="dropdown-link">Competitions</a>
                </div>
            </div>
            
            <a href="community.php" class="<?= in_array($current_page, ['community.php', 'community_detail.php'], true) ? 'active' : ''; ?>">Community</a>

            <!-- Sliding underline indicator -->
            <span class="nav-indicator"></span>
        </div>

        <div class="nav-right">
            <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true): ?>
                <a href="profile.php" class="nav-user">
                    Hi, <?php echo htmlspecialchars($_SESSION['first_name']); ?>
                </a>
                <a href="logout.php" class="nav-cta">Log Out</a>
            <?php else: ?>
                <a href="login.php?redirect=<?php echo urlencode($_SERVER['REQUEST_URI']); ?>">Log In</a>
                <a href="registration.php" class="nav-cta">Register</a>
            <?php endif; ?>
        </div>
    </nav>

    <?php if (isset($_GET['booked'])): ?>
    <div class="toast-alert" id="toastAlert">
        <span class="toast-icon">✓</span>
        <span>You're registered! We'll see you there.</span>
    </div>
    <?php elseif (isset($_GET['login_success'])): ?>
    <div class="toast-alert" id="toastAlert">
        <span class="toast-icon">✓</span>
        <span>Logged in successfully.</span>
    </div>
    <?php elseif (isset($_GET['registered'])): ?>
    <div class="toast-alert" id="toastAlert">
        <span class="toast-icon">✓</span>
        <span>Account created! Please log in.</span>
    </div>
    <?php endif; ?>

</body>