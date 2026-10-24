<?php
require_once 'includes/auth.php';
require_once 'includes/db.php';
require_role('user');   // logged-in users only, otherwise redirect to login.php

// Task 3.2: the logged-in user's own details come from user_table
$db   = get_db();
$stmt = $db->prepare('SELECT first_name, last_name, dob, gender, hometown, profile_image, resume_path FROM user_table WHERE email = ?');
$stmt->bind_param('s', $_SESSION['email']);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

require_once 'includes/bookings.php';
require_once 'includes/orders.php';
$my_bookings = fetch_my_bookings($db, $_SESSION['email']);

// This user's orders (from order_table), newest first
$stmt = $db->prepare('SELECT id, product_name, quantity, unit_price, status FROM order_table WHERE email = ? ORDER BY id DESC');
$stmt->bind_param('s', $_SESSION['email']);
$stmt->execute();
$my_orders = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// This user's own community posts (any status), newest first
$stmt = $db->prepare('SELECT id, title, related_activity, tag, status FROM community_table WHERE email = ? ORDER BY id DESC');
$stmt->bind_param('s', $_SESSION['email']);
$stmt->execute();
$my_posts = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

$photo = 'img/avatars/female-default.jpg';
if ($user) {
    $photo = !empty($user['profile_image']) && is_file($user['profile_image'])
        ? $user['profile_image']
        : (($user['gender'] === 'Male') ? 'img/avatars/male-default.jpg' : 'img/avatars/female-default.jpg');
}

$page_title = 'Profile';
include 'header.php';
?>

<main class="page profile-page">
<?php if ($user): ?>
    <div class="profile-layout">

        <!-- Left: who you are -->
        <div class="profile-card">
            <img src="<?= htmlspecialchars($photo) ?>" alt="Profile photo" class="profile-photo">
            <h1><?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) ?></h1>

            <ul class="profile-facts">
                <li><strong>Email</strong><span><?= htmlspecialchars($_SESSION['email']) ?></span></li>
                <li><strong>Date of Birth</strong><span><?= $user['dob'] ? htmlspecialchars($user['dob']) : 'Not set' ?></span></li>
                <li><strong>Gender</strong><span><?= htmlspecialchars($user['gender']) ?></span></li>
                <li><strong>Hometown</strong><span><?= htmlspecialchars($user['hometown']) ?></span></li>
                <?php if (!empty($user['resume_path']) && is_file($user['resume_path'])): ?>
                <li><strong>Build Portfolio</strong><span><a href="<?= htmlspecialchars($user['resume_path']) ?>" target="_blank">View PDF</a></span></li>
                <?php endif; ?>
            </ul>

            <div class="profile-links">
                <a href="update_profile.php" class="card-btn">Edit Profile</a>
                <a href="main_menu.php" class="card-btn secondary-btn">Main Menu</a>
            </div>
        </div>

        <!-- Right: what you have booked and ordered (each box scrolls when it gets long) -->
        <div class="profile-right">
            <section class="profile-side" id="my-registrations">
                <h2>My Registrations</h2>
                <p class="profile-side-desc">Your workshop and screening bookings, and whether they have been approved.</p>
                <?php render_bookings_table($my_bookings, true); ?>
            </section>

            <section class="profile-side" id="my-orders">
                <h2>My Orders</h2>
                <p class="profile-side-desc">Your Gunpla orders and where they are now.</p>
                <?php if (empty($my_orders)): ?>
                    <p class="bookings-empty">You have not ordered anything yet. <a href="series.php">Browse kits</a> to get started.</p>
                <?php else: ?>
                <div class="table-wrap table-scroll">
                    <table class="data-table">
                        <thead>
                            <tr><th>Order</th><th>Item</th><th>Total</th><th>Status</th><th></th></tr>
                        </thead>
                        <tbody>
                            <?php foreach ($my_orders as $o): ?>
                            <tr>
                                <td><?= htmlspecialchars(order_ref((int)$o['id'])) ?></td>
                                <td><?= htmlspecialchars($o['product_name']) ?> &times; <?= (int)$o['quantity'] ?></td>
                                <td><?= money(order_total($o)) ?></td>
                                <td><?= order_badge($o['status']) ?></td>
                                <td><a href="order_track.php?id=<?= (int)$o['id'] ?>" class="btn-sm">Track</a></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php endif; ?>
            </section>

            <section class="profile-side" id="my-posts">
                <h2>My Community Posts</h2>
                <p class="profile-side-desc">What you shared with the community. New posts stay pending until an admin approves them.</p>
                <?php if (empty($my_posts)): ?>
                    <p class="bookings-empty">You have not posted anything yet. <a href="community_add.php">Share a build</a>.</p>
                <?php else: ?>
                <div class="table-wrap table-scroll">
                    <table class="data-table">
                        <thead>
                            <tr><th>Post</th><th>Category</th><th>Status</th><th></th></tr>
                        </thead>
                        <tbody>
                            <?php foreach ($my_posts as $post): ?>
                            <tr>
                                <td><?= htmlspecialchars($post['title'] ?: $post['related_activity']) ?></td>
                                <td><?= htmlspecialchars($post['tag'] ?? '-') ?></td>
                                <td><span class="status-badge status-<?= htmlspecialchars($post['status']) ?>"><?= htmlspecialchars(ucfirst($post['status'])) ?></span></td>
                                <td>
                                    <?php if ($post['status'] === 'approved'): ?>
                                        <a href="community_detail.php?id=<?= (int)$post['id'] ?>" class="btn-sm">View</a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <p class="profile-side-desc"><a href="community_add.php">+ Add another post</a></p>
                <?php endif; ?>
            </section>
        </div>

        <!-- Full width: the declaration, collapsed so it does not push everything down -->
        <details class="integrity-declaration profile-declaration">
            <summary>Academic Integrity Declaration</summary>
            <p>I/we have not impersonated, or allowed myself/ourselves to be impersonated by any person for the purposes of this assessment. This assessment is my/our original work and no part of it has been copied from any other source except where due acknowledgement is made. No part of this assessment has been written for me/us by any other person except where such collaboration has been authorised by the lecturer/teacher concerned. I/we have not previously submitted this work for this or any other course/unit. I/we give permission for my/our assessment response to be reproduced, communicated, compared and archived for plagiarism detection, benchmarking or educational purposes.</p>
            
            <p>I/we understand that: Plagiarism is the presentation of another person's work as though it is your own. It is a form of cheating and is a very serious academic offence that may lead to exclusion from the university. Plarjarised material may be drawn from published and unpublished written documents, interpretations, computer software, designs, music, sounds, images, photographs, and ideas or ideological frameworks gained through working with another person or in a group. Plagiarised material can be drawn from, and presented in, written, graphic and visual form, including electronic data and oral presentations. Plagiarism occurs when the origin of the material used is not appropriately cited.</p>
            
            <p>I/we agree and acknowledge that: I/we have read and understood the Declaration and Statement of Authorship above. I/we accept that use of my/our Swinburne account to electronically submit this assessment constitutes my/our agreement to the Declaration and Statement of Authorship. If I/we do not agree to the Declaration and Statement of Authorship in this context, the assessment outcome may not be valid for assessment purposes and may not be included in my/our aggregate score for this unit. Penalties for plagiarism range from a formal caution to expulsion from the university, and are detailed in the Plagiarism and Misconduct webpage.</p>
        </details>

    </div>
<?php else: ?>
    <div class="profile-card">
        <h1>Profile not found</h1>
        <p>We couldn't find your account details. Please log in again.</p>
        <div class="profile-links"><a href="login.php" class="card-btn">Log In</a></div>
    </div>
<?php endif; ?>
</main>

<?php include 'footer.php'; ?>
