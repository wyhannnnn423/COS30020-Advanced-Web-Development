<?php
require_once 'includes/auth.php';
require_once 'includes/db.php';
require_once 'includes/bookings.php';
require_role('user');   // Task 3.2: activities requires a logged-in user

// Task 3.2: this user's registrations come from activity_table
$my_bookings = fetch_my_bookings(get_db(), $_SESSION['email']);

$page_title = 'Activities';
include 'header.php';

// Workshop and competition catalogue data (also used by activity_reg.php)
require_once 'data_activities.php'; // loads $workshops and $competitions
?>

    <header class="hero catalog-hero">
        <div class="hero-slider">
            <img src="img/banner/Activities.jpg" alt="Activities Background" class="hero-bg">
            <img src="img/banner/Activities2.jpg" alt="Activities Background" class="hero-bg">
            <img src="img/banner/Activities3.jpg" alt="Activities Background" class="hero-bg">
        </div>
        <div class="hero-overlay"></div>
        <div class="hero-content">
            <span class="hero-tag">Activities</span>
            <h1>Book a Workshop</h1>
        </div>
        <div class="hero-credit">
            <p>COS30020 Assignment 2 &mdash; Academic project, non-commercial</p>
            <p>Gundam imagery &copy; Sunrise / Bandai Namco Filmworks Inc.</p>
        </div>
    </header>
    
    <!-- Operational guidelines and complimentary tool borrowing notice -->
    <aside class="workshop-tips-banner">
        <div class="tips-badge">OPERATIONAL NOTICE</div>
        <div class="tips-body">
            <h4>All Materials &amp; Tools Provided On-Site</h4>
            <p>
                You don't need to bring heavy equipment! Standard building tools (single-blade nippers, glass sanding files, panel lining pens, and cutting mats) are available for complimentary on-site loan. Consumables such as sanding sponges, cotton swabs, and practice runners are fully included in the entry fee.
            </p>
        </div>
    </aside>

    <main class="page catalog-layout">
    
        <!-- Sticky left-hand navigation sidebar -->
        <aside class="quick-jump">
            <h3>Quick Jump</h3>
            <div class="nav-group-title"><a href="#my-registrations">My Registrations</a></div>
            <div class="nav-group-title"><a href="#workshop">Workshops</a></div>
            <div class="nav-group-title"><a href="#competition">Competition</a></div>
        </aside>

        <div class="catalog-content">

            <!-- My registrations (from activity_table) -->
            <section class="catalog-category">
                <h1 id="my-registrations">My Registrations</h1>
                <p class="category-desc">Your bookings and their approval status</p>
                <?php render_bookings_table($my_bookings); ?>
            </section>

            <!-- Section 1: Practical Workshops -->
            <section class="catalog-category">
                <h1 id="workshop">Workshops</h1>
                <p class="category-desc">Hands-on Gunpla building sessions</p>

                <div class="catalog-grid">
                    <?php foreach ($workshops as $ws_id => $ws): ?>
                        <div class="catalog-card" id="<?= htmlspecialchars($ws_id) ?>">
                            <img src="<?= htmlspecialchars($ws['image']) ?>" alt="<?= htmlspecialchars($ws['title']) ?>">
                            <div class="catalog-card-body">
                                <h3><?= htmlspecialchars($ws['title']) ?></h3>
                                <div class="meta-row">
                                    <span class="meta format">Workshop</span>
                                    <span class="meta"><?= htmlspecialchars($ws['level']) ?></span>
                                </div>
                                <p><?= htmlspecialchars($ws['desc']) ?></p>
                                <ul class="activity-facts">
                                    <li>
                                        <strong>Next Session</strong>
                                        <span><?= htmlspecialchars($ws['next_session']) ?></span>
                                    </li>
                                    <li>
                                        <strong>Entry Fee</strong>
                                        <span class="price-tag"><?= htmlspecialchars($ws['fee']) ?></span>
                                    </li>
                                    <li>
                                        <strong>Venue</strong>
                                        <span><?= htmlspecialchars($ws['venue']) ?></span>
                                    </li>
                                </ul>
                                <!--
                                    "from" tells activity_reg.php exactly which page to
                                    return to (Cancel button / after successful booking),
                                    instead of relying on the unreliable HTTP_REFERER.
                                -->
                                <a href="activity_reg.php?workshop=<?= urlencode($ws_id) ?>&from=<?= urlencode($_SERVER['REQUEST_URI'] . '#' . $ws_id) ?>" class="card-btn">Book This Slot</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>

            <!-- Section 2: Global Competitions -->
            <section class="catalog-category">
                <h1 id="competition">Competition</h1>
                <p class="category-desc">
                    The Gunpla Builders World Cup (GBWC) is BANDAI SPIRITS' official worldwide Gunpla building contest, held annually since 2011.
                    Malaysia is one of 16 participating regions: local entries are judged at a regional exhibition, and the top winner from
                    each age course (U-14, U-20, OVER-21) advances to represent Malaysia at the World Final in Tokyo. The 10th Tournament
                    was held mainly online worldwide due to COVID-19 restrictions.
                </p>

                <div class="catalog-grid">
                    <?php foreach ($competitions as $comp_id => $comp): ?>
                        <div class="catalog-card" id="<?= htmlspecialchars($comp_id) ?>">
                            <img src="<?= htmlspecialchars($comp['image']) ?>" 
                                 alt="<?= htmlspecialchars($comp['title']) ?>">
                            <div class="catalog-card-body">
                                <h3><?= htmlspecialchars($comp['title']) ?></h3>
                                <div class="meta-row">
                                    <span class="meta format">Competition</span>
                                    <span class="meta"><?= htmlspecialchars($comp['level']) ?></span>
                                </div>
                                <p><?= htmlspecialchars($comp['desc']) ?></p>
                                <ul class="activity-facts">
                                    <li>
                                        <strong>Registration</strong>
                                        <span><?= htmlspecialchars($comp['registration']) ?></span>
                                    </li>
                                    <li>
                                        <strong>Price</strong>
                                        <span class="price-tag"><?= htmlspecialchars($comp['fee']) ?></span>
                                    </li>
                                    <li>
                                        <strong>Venue</strong>
                                        <span><?= htmlspecialchars($comp['venue']) ?></span>
                                    </li>
                                </ul>
                                <span class="card-btn status-ended"><?= htmlspecialchars($comp['status']) ?></span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>
            
            <p class="page-credit">The images displayed are for illustrative purposes only and are used strictly for non-commercial academic research</p>

        </div>
    </main>

<?php include 'footer.php'; ?>