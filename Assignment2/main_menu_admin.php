<?php
require_once 'includes/auth.php';
require_role('admin');   // Req-1: must be logged in with type "admin", otherwise redirect to login.php

$page_title = 'Admin Menu';
include 'header_admin.php';
?>

    <section class="menu-screen menu-screen-admin">
        <img src="img/banner/Menu.jpg" alt="Banner" class="menu-bg">
        <div class="menu-overlay"></div>

        <div class="menu-content">
            <div class="menu-col menu-main">
                <span class="menu-label">Admin Menu</span>
                <a href="manage_accounts.php" class="menu-card">
                    Manage Users' Accounts
                    <span>Add, edit and delete user accounts</span>
                </a>
                <a href="manage_community.php" class="menu-card">
                    Manage Community Contributions
                    <span>Approve, reject, edit or delete community posts</span>
                </a>
                <a href="manage_activities.php" class="menu-card">
                    Manage Activity Registrations
                    <span>Approve, reject, edit or delete bookings</span>
                </a>
                <a href="manage_smart_features.php" class="menu-card">
                    Kit Submissions
                    <span>Approve or reject kits for the Kit Recommender</span>
                </a>
                <a href="manage_products.php" class="menu-card">
                    Products &amp; Stock
                    <span>Set stock levels or switch products on and off</span>
                </a>
                <a href="manage_orders.php" class="menu-card">
                    Orders
                    <span>Verify payments, pack and ship orders</span>
                </a>
            </div>

            <div class="menu-col menu-info">
                <span class="menu-label">Info</span>
                <a href="index.php">View Website</a>
                <a href="about.php">About This Project</a>
                <a href="logout.php">Log Out</a>
            </div>
        </div>

        <div class="menu-wordmark">GUNDAM HUB</div>
        <div class="menu-copyright">
            <p>COS30020 Assignment 2 &mdash; Academic project, non-commercial</p>
            <p>Gundam imagery &copy; Sunrise / Bandai Namco Filmworks Inc.</p>
        </div>
    </section>

<?php include 'footer.php'; ?>
