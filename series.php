<?php 
$page_title = 'Series Guide'; 
include 'header.php'; 
require_once 'data_series.php'; 

// Trending
$trending_keys = [
    'xarx-zero', 
    'gundam-hathaway2', 
    'seed-freedom', 
    'gquuuuuux-tv', 
];

//  1. Animation card rendering function
function render_catalog_card($key, $items, $trending_keys) {
    if (!isset($items[$key])) return;
    $entry = $items[$key];
    $is_trending = in_array($key, $trending_keys);
    ?>
    <div class="catalog-card <?= $is_trending ? 'featured-card' : '' ?>" id="<?= htmlspecialchars($key) ?>">
        <img src="<?= htmlspecialchars($entry['image']) ?>" alt="<?= htmlspecialchars($entry['title']) ?>">
        <div class="catalog-card-body">
            <h3><?= htmlspecialchars($entry['title']) ?></h3>
            <div class="meta-row">
                <span class="meta format"><?= htmlspecialchars($entry['format']) ?></span>
                <span class="meta"><?= htmlspecialchars($entry['year']) ?></span>
                <span class="meta"><?= htmlspecialchars($entry['era']) ?></span>
                <?php if ($is_trending): ?>
                    <span class="meta meta-trending-badge">Trending</span>
                <?php endif; ?>
            </div>
            <p><?= htmlspecialchars($entry['desc']) ?></p>
            <a href="series_detail.php?id=<?= urlencode($key) ?>" class="card-btn">Watch</a>
        </div>
    </div>
    <?php
}

// 2.   Rendering function for gunpla and figure 
function render_product_card($key, $items, $btn_text = 'Explore') {
    if (!isset($items[$key])) return;
    $prod = $items[$key];
    ?>
    <div class="catalog-card" id="<?= htmlspecialchars($key) ?>">
        <img src="<?= htmlspecialchars($prod['image']) ?>" alt="<?= htmlspecialchars($prod['title']) ?>">
        <div class="catalog-card-body">
            <h3><?= htmlspecialchars($prod['title']) ?></h3>
            <span class="tag"><?= htmlspecialchars($prod['tag']) ?></span>
            <p><?= htmlspecialchars($prod['desc']) ?></p>
            <a href="product_list.php?type=<?= urlencode($key) ?>" class="card-btn"><?= htmlspecialchars($btn_text) ?></a>
        </div>
    </div>
    <?php
}
?>

    <header class="hero catalog-hero">
        <div class="hero-slider">
            <img src="img/banner/GundamSeries.jpg" alt="Series Guide Background" class="hero-bg">
            <img src="img/banner/GundamSeries2.jpg" alt="Series Guide Background" class="hero-bg">
            <img src="img/banner/GundamSeries3.jpg" alt="Series Guide Background" class="hero-bg">
        </div>
        <div class="hero-overlay"></div>
        <div class="hero-content">
            <span class="hero-tag">Database</span>
            <h1>The Gundam Franchise: Multimedia & Collectibles</h1>
        </div>
        <div class="hero-credit">
            <p>COS30020 Assignment 1 &mdash; Academic project, non-commercial</p>
            <p>Gundam imagery &copy; Sunrise / Bandai Namco Filmworks Inc.</p>
        </div>
    </header>

    <main class="page catalog-layout">

        <aside class="quick-jump">
            <h3>Quick Jump</h3>

            <div class="nav-group-title-trending"><a href="#trending-releases">Trending Releases</a></div>

            <div class="nav-group-title"><a href="#gundam-animation">Gundam Animation</a></div>
            <ul>
                <li>
                    <a href="#uni-uc">Universal Century</a>
                    <ul class="sub-jump">
                        <li><a href="#uc-mainline">Mainline</a></li>
                        <li><a href="#uc-origin">The Origin</a></li>
                        <li><a href="#uc-thunderbolt">Thunderbolt</a></li>
                        <li><a href="#uc-gquuuuuux">GQuuuuuuX</a></li>
                    </ul>
                </li>
                <li><a href="#uni-ce">Cosmic Era</a></li>
                <li><a href="#uni-ac">After Colony</a></li>
                <li><a href="#uni-fc">Future Century</a></li>
                <li><a href="#uni-aw">After War</a></li>
                <li><a href="#uni-cc">Correct Century</a></li>
                <li><a href="#uni-ad">Anno Domini</a></li>
                <li><a href="#uni-ag">Advanced Generation</a></li>
                <li><a href="#uni-pd">Post Disaster</a></li>
                <li><a href="#uni-rc">Regild Century</a></li>
                <li><a href="#uni-as">Ad Stella</a></li>
                <li><a href="#uni-aa">After Apocalypse</a></li>
                <li><a href="#uni-build">Build Series</a></li>
                <li><a href="#uni-sd">SD Gundam Universes</a></li>
            </ul>

            <div class="nav-group-title"><a href="#cat-collectibles">Gundam Collectibles</a></div>
            <ul>
                <li><a href="#cat-plastic-models">Plastic Models</a></li>
                <li><a href="#cat-collector-figures">Collector Figures</a></li>
            </ul>
            <div class="nav-group-title"><a href="#cat-games">Video Games</a></div>
        </aside>

        <div class="catalog-content">

            <!-- 1. TRENDING -->
            <section id="trending-releases" class="catalog-category trending-section">
                <div class="trending-header">
                    <h2 class="trending-title">
                        <span class="trending-badge-text">TRENDING</span> & RECENT HIGHLIGHTS
                    </h2>
                </div>
                <p class="category-desc">Catch up with the latest theatrical blockbusters, alternate timelines, and upcoming era reveals.</p>
                <div class="catalog-grid">
                    <?php 
                    foreach ($trending_keys as $t_key) {
                        render_catalog_card($t_key, $items, $trending_keys);
                    }
                    ?>
                </div>
            </section>

            <!-- 2. GUNDAM ANIMATION -->
            <section class="catalog-category">
                <h1 id="gundam-animation">Gundam Animation</h1>
                <p class="category-desc">TV series, OVAs and theatrical films grouped into their official continuities with dedicated release timelines.</p>

                <!-- Universal Century -->
                <h3 id="uni-uc" class="universe-heading master-universe">Universal Century (UC)</h3>

                <!-- UC Sub: Mainline -->
                <h3 id="uc-mainline" class="universe-heading sub-universe">Mainline</h3>
                <div class="catalog-grid">
                    <?php
                    $uc_keys = [
                        'mobile-suit-gundam-1979', 'gundam-movie-1', 'gundam-movie-2', 'gundam-movie-3',
                        '0080-war-in-the-pocket', '08th-ms-team', '08th-ms-team-millers-report',
                        'ms-igloo-1', 'ms-igloo-2', 'ms-igloo-3', '08th-ms-team-last-resort',
                        '0083-stardust-memory', '0083-afterglow-of-zeon', 'zeta-gundam',
                        'zeta-movie-1', 'zeta-movie-2', 'zeta-movie-3', 'gundam-zz',
                        'chars-counterattack', 'gundam-unicorn', 'twilight-axis', 'silver-phantom',
                        'gundam-narrative', 'gundam-hathaway', 'gundam-hathaway2', 'gundam-f91', 'victory-gundam'
                    ];
                    foreach ($uc_keys as $k) { render_catalog_card($k, $items, $trending_keys); }
                    ?>
                </div>

                <!-- UC Sub: The Origin -->
                <h3 id="uc-origin" class="universe-heading sub-universe">The Origin Continuity</h3>
                <div class="catalog-grid">
                    <?php
                    $gto_keys = ['gto-1', 'gto-2', 'gto-3', 'gto-4', 'gto-5', 'gto-6', 'advent-of-red-comet', 'cucuruz-doans-island'];
                    foreach ($gto_keys as $k) { render_catalog_card($k, $items, $trending_keys); }
                    ?>
                </div>

                <!-- UC Sub: Thunderbolt -->
                <h3 id="uc-thunderbolt" class="universe-heading sub-universe">Thunderbolt Continuity</h3>
                <div class="catalog-grid">
                    <?php
                    $tb_keys = ['gundam-thunderbolt', 'thunderbolt-december-sky', 'thunderbolt-bandit-flower'];
                    foreach ($tb_keys as $k) { render_catalog_card($k, $items, $trending_keys); }
                    ?>
                </div>

                <!-- UC Sub: GQuuuuuuX -->
                <h3 id="uc-gquuuuuux" class="universe-heading sub-universe">GQuuuuuuX Continuity</h3>
                <div class="catalog-grid">
                    <?php
                    $gqx_keys = ['gquuuuuux-tv', 'gquuuuuux'];
                    foreach ($gqx_keys as $k) { render_catalog_card($k, $items, $trending_keys); }
                    ?>
                </div>

                <!-- Cosmic Era -->
                <h3 id="uni-ce" class="universe-heading master-universe">Cosmic Era <span>(CE)</span></h3>
                <div class="catalog-grid">
                    <?php
                    $ce_keys = ['gundam-seed', 'seed-msv-astray', 'seed-destiny', 'stargazer', 'seed-freedom'];
                    foreach ($ce_keys as $k) { render_catalog_card($k, $items, $trending_keys); }
                    ?>
                </div>

                <!-- After Colony -->
                <h3 id="uni-ac" class="universe-heading master-universe">After Colony <span>(AC)</span></h3>
                <div class="catalog-grid">
                    <?php
                    $ac_keys = ['gundam-wing', 'gundam-wing-operation-meteor', 'endless-waltz'];
                    foreach ($ac_keys as $k) { render_catalog_card($k, $items, $trending_keys); }
                    ?>
                </div>

                <!-- Future Century -->
                <h3 id="uni-fc" class="universe-heading master-universe">Future Century <span>(FC)</span></h3>
                <div class="catalog-grid">
                    <?php render_catalog_card('g-gundam', $items, $trending_keys); ?>
                </div>

                <!-- After War -->
                <h3 id="uni-aw" class="universe-heading master-universe">After War <span>(AW)</span></h3>
                <div class="catalog-grid">
                    <?php render_catalog_card('gundam-x', $items, $trending_keys); ?>
                </div>

                <!-- Correct Century -->
                <h3 id="uni-cc" class="universe-heading master-universe">Correct Century <span>(CC)</span></h3>
                <div class="catalog-grid">
                    <?php
                    $cc_keys = ['turn-a-gundam', 'turn-a-earth-light', 'turn-a-moonlight-butterfly'];
                    foreach ($cc_keys as $k) { render_catalog_card($k, $items, $trending_keys); }
                    ?>
                </div>

                <!-- Anno Domini -->
                <h3 id="uni-ad" class="universe-heading master-universe">Anno Domini <span>(AD)</span></h3>
                <div class="catalog-grid">
                    <?php
                    $ad_keys = ['gundam-00', 'gundam-00-2', 'gundam-00-movie'];
                    foreach ($ad_keys as $k) { render_catalog_card($k, $items, $trending_keys); }
                    ?>
                </div>

                <!-- Advanced Generation -->
                <h3 id="uni-ag" class="universe-heading master-universe">Advanced Generation <span>(AG)</span></h3>
                <div class="catalog-grid">
                    <?php
                    $ag_keys = ['gundam-age', 'age-memory-of-eden'];
                    foreach ($ag_keys as $k) { render_catalog_card($k, $items, $trending_keys); }
                    ?>
                </div>

                <!-- Post Disaster -->
                <h3 id="uni-pd" class="universe-heading master-universe">Post Disaster <span>(PD)</span></h3>
                <div class="catalog-grid">
                    <?php
                    $pd_keys = ['iron-blooded-orphans', 'iron-blooded-orphans2'];
                    foreach ($pd_keys as $k) { render_catalog_card($k, $items, $trending_keys); }
                    ?>
                </div>

                <!-- Regild Century -->
                <h3 id="uni-rc" class="universe-heading master-universe">Regild Century <span>(RC)</span></h3>
                <div class="catalog-grid">
                    <?php
                    $rc_keys = ['reconguista-in-g', 'g-reco-movie-1', 'g-reco-movie-2', 'g-reco-movie-3', 'g-reco-movie-4', 'g-reco-movie-5'];
                    foreach ($rc_keys as $k) { render_catalog_card($k, $items, $trending_keys); }
                    ?>
                </div>

                <!-- Ad Stella -->
                <h3 id="uni-as" class="universe-heading master-universe">Ad Stella <span>(AS)</span></h3>
                <div class="catalog-grid">
                    <?php
                    $as_keys = ['witch-from-mercury', 'witch-from-mercury2'];
                    foreach ($as_keys as $k) { render_catalog_card($k, $items, $trending_keys); }
                    ?>
                </div>

                <!-- After Apocalypse -->
                <h3 id="uni-aa" class="universe-heading master-universe">After Apocalypse <span>(AA)</span></h3>
                <div class="catalog-grid">
                    <?php render_catalog_card('xarx-zero', $items, $trending_keys); ?>
                </div>

                <!-- Build Series -->
                <h3 id="uni-build" class="universe-heading master-universe">Build Series <span>(Present / VR)</span></h3>
                <div class="catalog-grid">
                    <?php
                    $build_keys = ['beginning-g', 'build-fighters', 'build-fighters-try', 'try-island-wars', 'gms-counterattack', 'battlogue', 'build-divers', 'build-divers-rerise', 'build-divers-rerise2', 'breaker-battlogue', 'build-metaverse'];
                    foreach ($build_keys as $k) { render_catalog_card($k, $items, $trending_keys); }
                    ?>
                </div>

                <!-- SD Gundam Universes -->
                <h3 id="uni-sd" class="universe-heading master-universe">SD Gundam Universes <span>(SD)</span></h3>
                <div class="catalog-grid">
                    <?php
                    $sd_keys = ['sd-gundam-gaiden', 'sd-gundam-force', 'sangoku-brave-battle-warriors', 'sangoku-soketsuden', 'sd-world-heroes'];
                    foreach ($sd_keys as $k) { render_catalog_card($k, $items, $trending_keys); }
                    ?>
                </div>
            </section>

            <!-- 3. GUNPLA & FIGURES  -->
            <section class="catalog-category">
                <h1 id="cat-collectibles">Gundam Collectibles</h1>
                <p class="category-desc">Kit grades and finished figure lines explained &mdash; what each one offers in material, price, and mechanical detail.</p>

                <!-- Sub-Section: Plastic Models -->
                <h2 id="cat-plastic-models" class="universe-heading sub-universe">Plastic Models</h2>
                <div class="catalog-grid logo-grid">
                    <?php
                    $gunpla_keys = ['eg', 'hg', 'rg', 're100', 'fm', 'mg', 'pg', 'sd'];
                    foreach ($gunpla_keys as $gk) {
                        render_product_card($gk, $items, 'Explore');
                    }
                    ?>
                </div> 

                <!-- Sub-Section: Collector Figures -->
                <h2 id="cat-collector-figures" class="universe-heading sub-universe">Collector Figures</h2>
                <div class="catalog-grid logo-grid">
                    <?php
                    $figure_keys = ['robot-spirits', 'metal-robot-spirits', 'metal-build', 'nxedge-style', 'gundam-universe', 'gffmc'];
                    foreach ($figure_keys as $fk) {
                        render_product_card($fk, $items, 'View Line');
                    }
                    ?>
                </div>
            </section>

            <!-- 4. VIDEO GAMES -->
            <section class="catalog-category">
                <h1 id="cat-games">Video Games</h1>
                <p class="category-desc">Gundam beyond the screen &mdash; playable entries across a range of genres.</p>

                <div class="catalog-grid">
                    <?php
                    $game_keys = ['gundam-versus', 'g-generation', 'gundam-breaker', 'gundam-battle-operation-2', 'dynasty-warriors-gundam', 'gundam-evolution'];
                    foreach ($game_keys as $k) {
                        if (!isset($items[$k])) continue;
                        $game = $items[$k];
                        ?>
                        <div class="catalog-card" id="<?= htmlspecialchars($k) ?>">
                            <img src="<?= htmlspecialchars($game['image']) ?>" alt="<?= htmlspecialchars($game['title']) ?>">
                            <div class="catalog-card-body">
                                <h3><?= htmlspecialchars($game['title']) ?></h3>
                                <span class="tag"><?= htmlspecialchars($game['genre']) ?></span>
                                <p><?= htmlspecialchars($game['desc']) ?></p>
                                <a href="series_detail.php?id=<?= urlencode($k) ?>" class="card-btn">Play</a>
                            </div>
                        </div>
                        <?php
                    }
                    ?>
                </div>
            </section>

            <p class="page-credit">The images displayed are for illustrative purposes only and are used strictly for non-commercial academic research</p>
        </div>
    </main>

<?php include 'footer.php'; ?>