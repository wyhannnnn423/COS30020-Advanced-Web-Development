<?php 
$page_title = 'Contribution Detail'; 
include 'header.php'; 
require_once 'data_community.php'; // Include centralized community data

// Retrieve and validate target record parameter
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$post = $contributions[$id] ?? null;

// Dynamically set page title when post exists
if ($post) {
    $page_title = $post['title'] . ' - Community';
}
?>

<main class="detail-page">
<?php if ($post): ?>

    <div class="detail-container">
        <div class="detail-photo">
            <img src="<?= htmlspecialchars($post['image']) ?>" alt="<?= htmlspecialchars($post['title']) ?>">
        </div>

        <div class="detail-info">
            <span class="post-tag"><?= htmlspecialchars($post['tag']) ?></span>
            <h1><?= htmlspecialchars($post['title']) ?></h1>

            <div class="post-meta">
                <div class="post-avatar"><?= htmlspecialchars($post['initial']) ?></div>
                <span class="post-user"><?= htmlspecialchars($post['user']) ?></span>
            </div>

            <p class="detail-caption"><?= htmlspecialchars($post['caption']) ?></p>

            <ul class="detail-facts">
                <li>
                    <strong>Related Activity</strong>
                    <span><?= htmlspecialchars($post['related']) ?></span>
                </li>
            </ul>

            <div class="post-stats">
                <span><?= htmlspecialchars((string)$post['likes']) ?> Likes</span>
                <span><?= htmlspecialchars((string)$post['comments']) ?> Comments</span>
            </div>

            <!-- Returns specifically to the origin post card using fragment anchor -->
            <a href="community.php#post-<?= urlencode((string)$id) ?>" class="card-btn">Back to Community</a>
        </div>
    </div>

<?php else: ?>

    <!-- 404 fallback state for non-existent IDs[cite: 5] -->
    <div class="detail-not-found">
        <h1>Contribution not found</h1>
        <p>This contribution may have been removed or the link is incorrect.</p>
        <a href="community.php" class="card-btn">Back to Community</a>
    </div>

<?php endif; ?>
</main>

<?php include 'footer.php'; ?>