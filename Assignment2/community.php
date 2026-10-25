<?php
require_once 'includes/auth.php';
require_once 'includes/db.php';
require_once 'includes/community.php';

// Only approved contributions are shown to visitors.
$db   = get_db();
$res  = $db->query("SELECT * FROM community_table WHERE status = 'approved' ORDER BY id");
$contributions = [];
while ($row = $res->fetch_assoc()) {
    $contributions[$row['id']] = community_post_from_row($row);
}

$page_title = 'Community';
include 'header.php';
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
        <p>COS30020 Assignment 2 &mdash; Academic project, non-commercial</p>
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
    <a href="community_add.php" class="card-btn filter-add">+ Add Contribution</a>
</div>

<!-- Community post dynamic grid -->
<section class="post-grid" id="postGrid">
    <?php foreach ($contributions as $post_id => $post): ?>
        <!-- Card anchor target enabled via unique post ID -->
        <div id="post-<?= htmlspecialchars((string)$post_id) ?>"
             class="post-card"
             data-category="<?= htmlspecialchars($post['tag']) ?>">
            <!-- Invisible link that covers the whole card, so the whole card opens the post -->
            <a class="post-card-link" href="community_detail.php?id=<?= urlencode((string)$post_id) ?>"
               aria-label="<?= htmlspecialchars($post['title']) ?>"></a>
            <img src="<?= htmlspecialchars($post['image']) ?>" alt="<?= htmlspecialchars($post['title']) ?>">
            <div class="post-card-body">
                <div class="post-meta">
                    <div class="post-avatar"><?= htmlspecialchars($post['initial']) ?></div>
                    <?php if ($post['member'] !== ''): ?>
                        <a class="post-user post-user-link" href="member.php?u=<?= $post['member'] ?>"><?= htmlspecialchars($post['user']) ?></a>
                    <?php else: ?>
                        <span class="post-user"><?= htmlspecialchars($post['user']) ?></span>
                    <?php endif; ?>
                    <span class="post-tag"><?= htmlspecialchars($post['tag']) ?></span>
                </div>
                <h3 class="post-title"><?= htmlspecialchars($post['title']) ?></h3>
                <p class="post-caption"><?= htmlspecialchars($post['caption']) ?></p>
            </div>
        </div>
    <?php endforeach; ?>
</section>

<!-- Empty state feedback when category filters yield zero results -->
<div class="empty-state" id="emptyState">
    <p>No posts in this category yet.</p>
</div>

<?php include 'footer.php'; ?>