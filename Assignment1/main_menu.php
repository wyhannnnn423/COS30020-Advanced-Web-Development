<?php 
$page_title = 'Main Menu'; 
include 'header.php'; 
?>

    <section class="menu-screen">
        <img src="img/banner/Menu.jpg" alt="Banner" class="menu-bg">
        <div class="menu-overlay"></div>    

        <div class="menu-content">
            <div class="menu-col menu-main">
                <span class="menu-label">Menu</span>
                <a href="series.php" class="menu-card">
                    Series Guide
                    <span>Explore Gundam series, games, and kit grades</span>
                </a>
                <a href="activities.php" class="menu-card">
                    Workshops
                    <span>Book a workshop, class, or screening</span>
                </a>
                <a href="community.php" class="menu-card">
                    Community
                    <span>See finished builds from other fans</span>
                </a>
                <a href="#" class="menu-card menu-card-disabled">
                    Kit Recommender
                    <span>Coming in Assignment 2</span>
                </a>
            </div>

           <div class="menu-col menu-info">
                <span class="menu-label">Info</span>
                <a href="about.php">About This Project</a>
                <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true): ?>
                    <a href="profile.php">View Profile</a>
                <?php endif; ?>
            </div>
        </div>

        <div class="menu-wordmark">GUNDAM HUB</div>
        <div class="menu-copyright">
            <p>COS30020 Assignment 1 &mdash; Academic project, non-commercial</p>
            <p>Gundam imagery &copy; Sunrise / Bandai Namco Filmworks Inc.</p>
        </div>
    </section>


