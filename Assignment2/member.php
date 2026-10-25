<?php
require_once 'includes/auth.php';
require_once 'includes/db.php';
require_once 'includes/accounts.php';
require_role('user');   // member pages are for logged-in users, like the community detail page

// A member is addressed by a token (see member_token), never by email.
$token  = (string)($_GET['u'] ?? '');
$member = null;
$posts  = [];

if (preg_match('/^[0-9a-f]{64}$/', $token)) {
    $db   = get_db();
    $stmt = $db->prepare(
        "SELECT u.email, u.first_name, u.last_name, u.gender, u.profile_image, u.resume_path
         FROM user_table u JOIN account_table a ON a.email = u.email
         WHERE a.type = 'user' AND SHA2(LOWER(u.email), 256) = ?"
    );
    $stmt->bind_param('s', $token);
    $stmt->execute();
    $member = $stmt->get_result()->fetch_assoc();

    if ($member) {
        // Only approved posts are public.
        $stmt = $db->prepare(
            "SELECT id, title, related_activity, tag, contribution_image
             FROM community_table WHERE email = ? AND status = 'approved' ORDER BY id DESC"
        );
        $stmt->bind_param('s', $member['email']);
        $stmt->execute();
        $posts = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
}

$photo = 'img/avatars/female-default.jpg';
if ($member) {
    $photo = (!empty($member['profile_image']) && is_file($member['profile_image']))
        ? $member['profile_image']
        : (($member['gender'] === 'Male') ? 'img/avatars/male-default.jpg' : 'img/avatars/female-default.jpg');
}
$has_portfolio = $member && !empty($member['resume_path']) && is_file($member['resume_path']);

$page_title = $member ? $member['first_name'] . ' ' . $member['last_name'] : 'Member';
include 'header.php';
?>

<main class="page member-page">
<?php if ($member): ?>
    <div class="member-head">
        <img src="<?= htmlspecialchars($photo) ?>" alt="" class="member-photo">
        <div>
            <h1><?= htmlspecialchars($member['first_name'] . ' ' . $member['last_name']) ?></h1>
            <p class="member-sub"><?= count($posts) ?> community post<?= count($posts) === 1 ? '' : 's' ?></p>
        </div>
    </div>

    <?php if ($has_portfolio): ?>
    <section class="profile-side member-portfolio">
        <h2>Build Portfolio</h2>
        <object data="<?= htmlspecialchars($member['resume_path']) ?>#toolbar=0" type="application/pdf" class="portfolio-frame">
            <a href="<?= htmlspecialchars($member['resume_path']) ?>" target="_blank">Open the portfolio (PDF)</a>
        </object>
    </section>
    <?php endif; ?>

    <h2 class="member-heading">Posts</h2>
    <?php if (empty($posts)): ?>
        <p class="bookings-empty">No approved posts yet.</p>
    <?php else: ?>
    <div class="smart-grid">
        <?php foreach ($posts as $p): ?>
        <article class="smart-card">
            <?php if (!empty($p['contribution_image']) && is_file($p['contribution_image'])): ?>
                <img src="<?= htmlspecialchars($p['contribution_image']) ?>" alt="<?= htmlspecialchars($p['title'] ?? '') ?>">
            <?php endif; ?>
            <div class="smart-card-body">
                <h3><?= htmlspecialchars($p['title'] ?: $p['related_activity']) ?></h3>
                <span class="post-tag"><?= htmlspecialchars($p['tag'] ?? 'Community') ?></span>
                <a href="community_detail.php?id=<?= (int)$p['id'] ?>" class="card-btn">View post</a>
            </div>
        </article>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <p class="smart-contribute"><a href="community.php">&larr; Back to Community</a></p>
<?php else: ?>
    <div class="detail-not-found">
        <h1>Member not found</h1>
        <p>This page may have been removed or the link is incorrect.</p>
        <a href="community.php" class="card-btn">Back to Community</a>
    </div>
<?php endif; ?>
</main>

<?php include 'footer.php'; ?>
