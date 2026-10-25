<?php
require_once 'includes/auth.php';
start_session_once();
$page_title = 'About This Project';
include 'header.php';
?>

<main class="page about-page">
    <div class="about-container">
        <h1>About Gundam Hub</h1>
        <section class="about-section">
            <h2>What problem does this project solve, and why did I choose it?</h2>
            <div class="about-with-photo">
                <p>
                    Entering the Gundam franchise is often intimidating for newcomers, given the decades of series across disparate continuities, an overwhelming array of kit grades, and the absence of a centralized, accessible guide. 
                    Gundam Hub addresses these challenges by consolidating beginner-friendly anime and kit guides, interactive workshops, and a community gallery. 
                    I selected this topic due to my nine-year personal investment in the hobby, which sustained my motivation throughout the development of over 70 catalogue entries and the platform's core features.
                </p>
                <div class="about-gallery">
                    <div class="gallery-item">
                        <img src="img/about/my-gundam.jpg" alt="My Gunpla build 1">
                    </div>
                    <div class="gallery-item">
                        <img src="img/about/my-gundam2.jpg" alt="My Gunpla build 2">
                    </div>
            </div>
        </section>

        <section class="about-section">
            <h2>What PHP version was used?</h2>
            <p>
                <strong><?php echo phpversion(); ?></strong>
                (generated dynamically using PHP's function)
            </p>
        </section>

        <section class="about-section">
            <h2>What tasks have I completed?</h2>
            <p class="marker-note">Quick links for marker. Log in as <strong>admin@swin.edu.my / admin</strong> for the admin pages, or as <strong>yanhanwong@swin.edu.my / User@1234</strong> for the user pages.</p>
            <ul class="about-list">
                <li>Part 1 — Database and five tables with dummy data, created automatically (<a href="includes/setup.php">includes/setup.php</a>, run through <a href="includes/db.php">includes/db.php</a>)</li>
                <li>Task 2.1 — Registration (<a href="registration.php">registration.php</a>)</li>
                <li>Task 2.2 — Login (<a href="login.php">login.php</a>)</li>
                <li>Task 3.1 — Main Menu (<a href="main_menu.php">main_menu.php</a>)</li>
                <li>Task 3.2 — Database-driven pages (<a href="activities.php">activities.php</a>, <a href="community_detail.php?id=1">community_detail.php</a>, <a href="profile.php">profile.php</a>)</li>
                <li>Task 3.3 — Update Profile (<a href="update_profile.php">update_profile.php</a>)</li>
                <li>Task 3.4 — Smart Feature Contribution (<a href="smart_feature_add.php">smart_feature_add.php</a>)</li>
                <li>Task 4.1 — Admin Main Menu (<a href="main_menu_admin.php">main_menu_admin.php</a>)</li>
                <li>Task 4.2 — Manage Users' Accounts (<a href="manage_accounts.php">manage_accounts.php</a>)</li>
                <li>Task 4.3 — Manage Community Contributions (<a href="manage_community.php">manage_community.php</a>)</li>
                <li>Task 4.4 — Manage Activity Registrations (<a href="manage_activities.php">manage_activities.php</a>)</li>
                <li>Task 4.5 — About Page (<a href="about.php">about.php</a>)</li>
                <li>Task 5.1 — Smart Feature: Kit Recommender (<a href="smart_feature.php">smart_feature.php</a>)</li>
                <li>Extra — Shop checkout with QR payment, proof upload and order tracking (<a href="order_track.php">order_track.php</a>, <a href="manage_orders.php">manage_orders.php</a>)</li>
                <li>Extra — Community contributions from users, approved by an admin (<a href="community_add.php">community_add.php</a>)</li>
                <li>Assignment 1 pages carried over: <a href="index.php">index.php</a>, <a href="series.php">series.php</a>, <a href="community.php">community.php</a>, <a href="activity_reg.php">activity_reg.php</a></li>
            </ul>
        </section>

        <section class="about-section">
            <h2>What tasks, if any, have I not attempted or not completed?</h2>
            <ul class="about-list">
                <li>A real online payment gateway is not implemented, because it needs an external payment API. Instead the checkout is simulated: the customer pays by scanning a QR code, uploads proof of payment, and an admin checks the proof by hand and updates the order.</li>
            </ul>
        </section>

        <section class="about-section">
            <h2>Which parts did I have the most trouble with?</h2>
            <ul class="about-list">
                <li>The database functions in Assignment 2 were the hardest part for me overall.</li>
                <li>Keeping several tables in step was tricky: a new account has to be written to user_table and account_table together, and an email change has to carry through to both.</li>
                <li>When an older version of my database already existed, the new columns were missing and pages crashed with "Unknown column" errors, so I had to add a step that checks for missing columns and adds them.</li>
            </ul>
        </section>

        <section class="about-section">
            <h2>What would I do better next time?</h2>
            <ul class="about-list">
                <li>I would make every product page much more detailed, for example with the Gunpla manual and reviews.</li>
                <li>For anime and games, I would let visitors really watch or play them on the website instead of only reading about them.</li>
            </ul>
        </section>

        <section class="about-section">
            <h2>What extension features or extra challenges did I attempt?</h2>
            <ul class="about-list">
                <li>A shop checkout: delivery details, a payment QR code, proof-of-payment upload, an order tracking timeline for the customer, and an admin page to verify payments and ship orders.</li>
                <li>A Build Planner in the Kit Recommender: from skill level, finish and budget it works out suitable kits, build time, supplies needed (markers, primer, paint, topcoat in ml) and an estimated total cost.</li>
                <li>Stock control for the shop: an admin sets how many of each product are in stock or switches a product off, the shop shows "Sold out" or "Only 2 left", and ordering takes stock off automatically (and puts it back when an unshipped order is deleted).</li>
                <li>Users can contribute community posts and kits, and an admin approves or rejects them before they go live.</li>
                <li>Security: passwords and security answers are hashed, every query uses prepared statements, every admin action carries a CSRF token, and uploads are checked on the server by their real file content, not just their name.</li>
                <li>AJAX approve and reject: in the admin tables the buttons update the status and the button states without reloading the page (js/admin.js), and still work as normal forms if JavaScript is off.</li>
                <li>Search, filter by status or account type, and pagination on every admin table.</li>
                <li>Responsive admin pages that stay usable on a phone.</li>
                <li>Database transactions with rollback, so a failed registration or account deletion never leaves one table half-written.</li>
                <li>A setup step that creates the database, tables and dummy data automatically, and adds any missing columns to older databases.</li>
            </ul>
        </section>

        <section class="about-section">
            <h2>What frameworks or third-party libraries did I use?</h2>
            <ul class="about-list">
                <li>Google Fonts (Rajdhani, Inter) — web fonts, loaded via CDN link</li>
                <li>No CSS or JavaScript framework — all styling and interactivity is hand-written CSS3 and vanilla JavaScript (no jQuery, Bootstrap, React, etc.)</li>
            </ul>
        </section>

        <section class="about-section">
            <h2>Video Presentation</h2>
            <p>Link: <a href="https://youtu.be/SO1gt2uGhgc" target="_blank" rel="noopener">[EDIT: replace with your Assignment 2 YouTube link]</a></p>
            
            <iframe 
                width="560" 
                height="315" 
                src="https://www.youtube.com/embed/SO1gt2uGhgc?cc_load_policy=0&iv_load_policy=3&rel=0" 
                title="Demo Video" 
                frameborder="0" 
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                allowfullscreen>
            </iframe>
        </section>

        <section class="about-section credit-section">
            <p class="page-credit">
                COS30020 Assignment 2 &mdash; Academic project, non-commercial.<br>
                Gundam imagery &copy; Sunrise / Bandai Namco Filmworks Inc. Used for illustrative,
                non-commercial academic purposes only.
            </p>
        </section>

        <a href="index.php" class="card-btn">Back to Home</a>
    </div>
</main>

<?php include 'footer.php'; ?>