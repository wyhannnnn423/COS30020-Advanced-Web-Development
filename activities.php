<?php 
$page_title = 'Activities'; 
include 'header.php'; 

// Centralized activity dataset: separated into Workshops and Competitions
$workshops = [
    'ws-basic-assembly' => [
        'title'        => 'Basic Assembly: Clean Builds Without Paint',
        'image'        => 'img/workshop/basic-assembly.jpg',
        'level'        => 'Beginner',
        'desc'         => 'Learn nub removal, seam checking, and part-fitting techniques to get a polished result straight out of the box.',
        'next_session' => 'Sat 6 Dec, 10:00 AM - 12:00 PM',
        'fee'          => 'RM 50',
        'venue'        => 'Gundam Hub Studio, Room A'
    ],
    'ws-panel-lining' => [
        'title'        => 'Panel Lining: Tools & Technique',
        'image'        => 'img/workshop/panel-lining.jpg',
        'level'        => 'Beginner',
        'desc'         => 'Learn how to choose the right lining tool and apply clean, even panel lines without smudging.',
        'next_session' => 'Sat 13 Dec, 10:00 AM - 12:00 PM',
        'fee'          => 'RM 50',
        'venue'        => 'Gundam Hub Studio, Room A'
    ],
    'ws-decal' => [
        'title'        => 'Decal & Sticker Application',
        'image'        => 'img/workshop/decal.jpg',
        'level'        => 'Beginner',
        'desc'         => 'Covers dry transfers, water slide decals, and how to blend them seamlessly into the kit\'s surface.',
        'next_session' => 'Sat 20 Dec, 10:00 AM - 12:00 PM',
        'fee'          => 'RM 50',
        'venue'        => 'Gundam Hub Studio, Room B'
    ],
    'ws-marker-touchup' => [
        'title'        => 'Basic Touch-Up with Gundam Markers',
        'image'        => 'img/workshop/marker.jpg',
        'level'        => 'Beginner',
        'desc'         => 'Learn how to use Gundam Markers to fix nub marks, scuffs, and small paint chips without needing an airbrush.',
        'next_session' => 'Sat 27 Dec, 10:00 AM - 12:00 PM',
        'fee'          => 'RM 50',
        'venue'        => 'Gundam Hub Studio, Room A'
    ]
];

$competitions = [
    'gbwc-13-my' => [
        'title'        => 'GBWC 13th Tournament - Malaysia',
        'image'        => 'img/competitions/gbwc-13-my.jpg',
        'level'        => 'All Levels',
        'desc'         => 'Entries exhibited and judged on-site, with course winners advancing to the World Final in Tokyo.',
        'registration' => '18 Apr - 31 Aug 2025',
        'fee'          => 'Free entry',
        'venue'        => 'MyTOWN Shopping Centre, Kuala Lumpur',
        'status'       => 'Ended'
    ],
    'gbwc-12-my' => [
        'title'        => 'GBWC 12th Tournament - Malaysia',
        'image'        => 'img/competitions/gbwc-12-my.jpg',
        'level'        => 'All Levels',
        'desc'         => 'Entries exhibited and judged on-site, with course winners advancing to the World Final in Tokyo.',
        'registration' => '3 Jun - 15 Sep 2024',
        'fee'          => 'Free entry',
        'venue'        => 'MyTOWN Shopping Centre, Kuala Lumpur',
        'status'       => 'Ended'
    ],
    'gbwc-11-my' => [
        'title'        => 'GBWC 11th Tournament - Malaysia',
        'image'        => 'img/competitions/gbwc-11-my.jpg',
        'level'        => 'All Levels',
        'desc'         => 'Entries exhibited and judged on-site, with course winners advancing to the World Final in Tokyo.',
        'registration' => '1 Jul - 15 Aug 2023',
        'fee'          => 'Free entry',
        'venue'        => 'MyTOWN Shopping Centre, Kuala Lumpur',
        'status'       => 'Ended'
    ],
    'gbwc-10-my' => [
        'title'        => 'GBWC 10th Tournament - Malaysia',
        'image'        => 'img/competitions/gbwc-10-my.jpg',
        'level'        => 'All Levels',
        'desc'         => 'Held mainly online worldwide due to COVID-19 restrictions, with course winners advancing to the World Final.',
        'registration' => '1 Jun - 30 Sep 2022',
        'fee'          => 'Free entry',
        'venue'        => 'Online submission',
        'status'       => 'Ended'
    ]
];
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
            <p>COS30020 Assignment 1 &mdash; Academic project, non-commercial</p>
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
            <div class="nav-group-title"><a href="#workshop">Workshops</a></div>
            <div class="nav-group-title"><a href="#competition">Competition</a></div>
        </aside>

        <div class="catalog-content">

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
                                <!-- Dynamically pass workshop ID to registration handler -->
                                <a href="activity_reg.php?workshop=<?= urlencode($ws_id) ?>" class="card-btn">Book This Slot</a>
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