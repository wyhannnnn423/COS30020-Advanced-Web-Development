<?php
require_once 'includes/auth.php';
require_once 'includes/db.php';
require_once 'includes/community.php';
require_role('user');   // Task 3.2: detail page requires a logged-in user

// Look the contribution up in community_table (approved entries only).
$id   = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$db   = get_db();
$stmt = $db->prepare("SELECT * FROM community_table WHERE id = ? AND status = 'approved'");
$stmt->bind_param('i', $id);
$stmt->execute();
$row  = $stmt->get_result()->fetch_assoc();
$post = $row ? community_post_from_row($row) : null;

$page_title = $post ? $post['title'] . ' - Community' : 'Contribution Detail';
include 'header.php';
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
                <?php if ($post['member'] !== ''): ?>
                    <a class="post-user post-user-link" href="member.php?u=<?= $post['member'] ?>"><?= htmlspecialchars($post['user']) ?></a>
                <?php else: ?>
                    <span class="post-user"><?= htmlspecialchars($post['user']) ?></span>
                <?php endif; ?>
            </div>

            <p class="detail-caption"><?= htmlspecialchars($post['caption']) ?></p>

            <ul class="detail-facts">
                <li>
                    <strong>Related Activity</strong>
                    <span><?= htmlspecialchars($post['related']) ?></span>
                </li>
            </ul>

            <!-- Returns specifically to the origin post card using fragment anchor -->
            <a href="community.php#post-<?= urlencode((string)$id) ?>" class="card-btn">Back to Community</a>
        </div>
    </div>

<?php else: ?>

    <!-- 404 fallback state for non-existent IDs-->
    <div class="detail-not-found">
        <h1>Contribution not found</h1>
        <p>This contribution may have been removed or the link is incorrect.</p>
        <a href="community.php" class="card-btn">Back to Community</a>
    </div>

<?php endif; ?>
</main>

<?php include 'footer.php'; ?>