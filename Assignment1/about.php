<?php 
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
            <p class="marker-note">Quick links for marker — click any page below to open it directly.</p>
            <ul class="about-list">
                <li>Task 1 — Home Page (<a href="index.php">index.php</a>)</li>
                <li>Task 2 — Main Menu Page (<a href="main_menu.php">main_menu.php</a>)</li>
                <li>Task 3 — Catalogue / Service Page (<a href="series.php">series.php</a>)</li>
                <li>Task 4 — Activity Page (<a href="activities.php">activities.php</a>)</li>
                <li>Task 5 — Community / Showcase Page (<a href="community.php">community.php</a>)</li>
                <li>Task 6 — Contribution Detail Page (<a href="community_detail.php?id=1">community_detail.php</a>)</li>
                <li>Task 7 — Profile Page (<a href="profile.php">profile.php</a>)</li>
                <li>Task 8 — Update Profile Page (<a href="update_profile.php">update_profile.php</a>)</li>
                <li>Task 9 — Account Registration Page (<a href="registration.php">registration.php</a>)</li>
                <li>Task 10 — Process Registration</li>
                <li>Task 11 — Activity Registration Page (<a href="activity_reg.php">activity_reg.php</a>)</li>
                <li>Task 12 — Login Page (<a href="login.php">login.php</a>)</li>
                <li>Task 13 — About Page (<a href="about.php">about.php</a>)</li>
            </ul>
        </section>

        <section class="about-section">
            <h2>What tasks, if any, have I not attempted or not completed?</h2>
            <ul class="about-list">
                <li>The upload feature on the Community page is scoped for Assignment 2, as stated in the brief.</li>
                <li>The Smart Feature (Task 2's fourth main menu option) is reserved for Assignment 2.</li>
                <li>The order feature on the product page is scoped for Assignment</li>             
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
                COS30020 Assignment 1 &mdash; Academic project, non-commercial.<br>
                Gundam imagery &copy; Sunrise / Bandai Namco Filmworks Inc. Used for illustrative,
                non-commercial academic purposes only.
            </p>
        </section>

        <a href="index.php" class="card-btn">Back to Home</a>
    </div>
</main>

<?php include 'footer.php'; ?>