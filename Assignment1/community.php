<?php 
$page_title = 'Community'; 
include 'header.php'; 
require_once 'data_community.php'; // Include centralized community data
?>

<header class="hero catalog-hero">
    <div class="hero-slider">
        <img src="img/banner/community.jpg" alt="Community Background" class="hero-bg">
        <img src="img/banner/community2.jpg" alt="Community Background" class="hero-bg">
        <img src="img/banner/community3.jpg" alt="Community Background" class="hero-bg">
    </div>
    <div class="hero-overlay"></div>
    <div class="hero-content">
        <span class="hero-tag">Channels</span>
        <h1>Community Builds &amp; Discussions</h1>
    </div>
    <div class="hero-credit">
        <p>COS30020 Assignment 1 &mdash; Academic project, non-commercial</p>
        <p>Gundam imagery &copy; Sunrise / Bandai Namco Filmworks Inc.</p>
    </div>
</header>

<!-- Interactive category filter buttons -->
<div class="filter-bar">
    <div class="filter-tabs">
        <button class="filter-tab active" data-category="all">All</button>
        <button class="filter-tab" data-category="Workshop">Workshop</button>
        <button class="filter-tab" data-category="Gunpla Build">Gunpla Build</button>
        <button class="filter-tab" data-category="Anime Discussion">Anime Discussion</button>
        <button class="filter-tab" data-category="Game Highlight">Game Highlight</button>
    </div>
<!-- Upload contribution feature reserved for Assignment 2 -->
    <button type="button" 
            class="card-btn btn-disabled" 
            disabled 
            title="Upload feature coming in Assignment 2">
        + Add Contribution (Assignment 2)
    </button></div>

<!-- Community post dynamic grid -->
<section class="post-grid" id="postGrid">
    <?php foreach ($contributions as $post_id => $post): ?>
        <!-- Card anchor target enabled via unique post ID -->
        <a href="community_detail.php?id=<?= urlencode((string)$post_id) ?>" 
           id="post-<?= htmlspecialchars((string)$post_id) ?>" 
           class="post-card" 
           data-category="<?= htmlspecialchars($post['tag']) ?>">
            <img src="<?= htmlspecialchars($post['image']) ?>" alt="<?= htmlspecialchars($post['title']) ?>">
            <div class="post-card-body">
                <div class="post-meta">
                    <div class="post-avatar"><?= htmlspecialchars($post['initial']) ?></div>
                    <span class="post-user"><?= htmlspecialchars($post['user']) ?></span>
                    <span class="post-tag"><?= htmlspecialchars($post['tag']) ?></span>
                </div>
                <h3 class="post-title"><?= htmlspecialchars($post['title']) ?></h3>
                <p class="post-caption"><?= htmlspecialchars($post['caption']) ?></p>
                <div class="post-stats">
                    <span><?= htmlspecialchars((string)$post['likes']) ?> Likes</span>
                    <span><?= htmlspecialchars((string)$post['comments']) ?> Comments</span>
                </div>
            </div>
        </a>
    <?php endforeach; ?>
</section>

<!-- Empty state feedback when category filters yield zero results -->
<div class="empty-state" id="emptyState">
    <p>No posts in this category yet.</p>
</div>

<?php include 'footer.php'; ?>