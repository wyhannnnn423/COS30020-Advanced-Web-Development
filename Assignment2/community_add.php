<?php
require_once 'includes/auth.php';
require_once 'includes/db.php';
require_once 'includes/upload.php';
require_once 'data_activities.php';   // $workshops and $competitions: used for the "related activity" list
start_session_once();

$page_title = 'Share a Contribution';

// Only logged-in users may post. Everyone else sees a page that points them to login.php.
if (!is_logged_in() || $_SESSION['type'] !== 'user') {
    include 'header.php';
    ?>
    <main class="auth-page">
        <img src="img/auth/register.jpg" alt="" class="auth-page-bg">
        <div class="auth-page-overlay"></div>
        <div class="auth-card auth-card-single">
            <h1>Please Log In</h1>
            <p class="auth-subtitle">You need to be logged in to share a build or a discussion.</p>
            <a href="login.php?redirect=community_add.php" class="card-btn">Go to Log In</a>
        </div>
    </main>
    <?php
    include 'footer.php';
    exit;
}

$tags = ['Workshop', 'Gunpla Build', 'Anime Discussion', 'Game Highlight'];

// Activities a post can be linked to (titles are at most 50 characters, like the column)
$related_options = ['General'];
foreach ($workshops as $w)    { $related_options[] = $w['title']; }
foreach ($competitions as $c) { $related_options[] = $c['title']; }

$errors  = [];
$success = false;
$form    = ['title' => '', 'caption' => '', 'tag' => '', 'related_activity' => ''];

if (post_too_large()) {
    $errors[] = 'The upload was too large for the server. The photo must be 5MB or less.';
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($form as $k => $_) {
        $form[$k] = trim($_POST[$k] ?? '');
    }

    if ($form['title'] === '' || mb_strlen($form['title']) > 100) {
        $errors[] = 'A title is required (max 100 characters).';
    }
    if ($form['caption'] === '' || mb_strlen($form['caption']) > 1000) {
        $errors[] = 'A caption is required (max 1000 characters).';
    }
    if (!in_array($form['tag'], $tags, true)) {
        $errors[] = 'Please choose a category.';
    }
    if (!in_array($form['related_activity'], $related_options, true)) {
        $errors[] = 'Please choose a related activity.';
    }
    if (empty($_FILES['image']) || $_FILES['image']['error'] === UPLOAD_ERR_NO_FILE) {
        $errors[] = 'Please attach a photo.';
    }

    $img_path = null;
    if (empty($errors)) {
        $err = '';
        $img_path = save_upload($_FILES['image'], ['jpg', 'jpeg', 'png'], 5 * 1048576, 'img/user_uploads', $err);
        if ($err !== '') {
            $errors[] = 'Photo: ' . $err;
        }
    }

    if (empty($errors)) {
        try {
            $db = get_db();

            // The poster's name comes from their account, not from the form.
            $who = $db->prepare('SELECT first_name, last_name FROM user_table WHERE email = ?');
            $who->bind_param('s', $_SESSION['email']);
            $who->execute();
            $user = $who->get_result()->fetch_assoc();

            $status = 'pending';   // an admin approves it before it appears on the Community page
            $ins = $db->prepare(
                'INSERT INTO community_table (first_name, last_name, contribution_image, related_activity, status, title, caption, tag, email)
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)'
            );
            $ins->bind_param('sssssssss', $user['first_name'], $user['last_name'], $img_path,
                $form['related_activity'], $status, $form['title'], $form['caption'], $form['tag'], $_SESSION['email']);
            $ins->execute();

            // Leave the form and show the "post submitted" alert on the Community page.
            header('Location: community.php?post_submitted=1');
            exit;
        } catch (mysqli_sql_exception $e) {
            delete_stored_file($img_path);
            $errors[] = 'Could not save your contribution. Please try again.';
        }
    } else {
        delete_stored_file($img_path);
    }
}

include 'header.php';
?>

<main class="auth-page">
    <img src="img/auth/register.jpg" alt="" class="auth-page-bg">
    <div class="auth-page-overlay"></div>

    <div class="auth-card auth-card-single">
        <h1>Share With the Community</h1>
        <p class="auth-subtitle">Show off a build or start a discussion. An admin reviews every post before it goes live.</p>

        <?php if ($success): ?>
        <div class="form-success">
            <p>Thank you! Your post was submitted and is <strong>pending</strong> approval.
               <a href="community.php">Back to Community</a></p>
        </div>
        <?php endif; ?>

        <?php if (!empty($errors)): ?>
        <div class="form-errors"><ul>
            <?php foreach ($errors as $error): ?><li><?= htmlspecialchars($error) ?></li><?php endforeach; ?>
        </ul></div>
        <?php endif; ?>

        <form action="community_add.php" method="POST" enctype="multipart/form-data" class="auth-form">
            <label for="title">Title</label>
            <input type="text" name="title" id="title" maxlength="100" value="<?= htmlspecialchars($form['title']) ?>">

            <label for="tag">Category</label>
            <select name="tag" id="tag">
                <option value="">-- Select a Category --</option>
                <?php foreach ($tags as $t): ?>
                    <option value="<?= htmlspecialchars($t) ?>" <?= ($form['tag'] === $t) ? 'selected' : '' ?>><?= htmlspecialchars($t) ?></option>
                <?php endforeach; ?>
            </select>

            <label for="related_activity">Related Activity</label>
            <select name="related_activity" id="related_activity">
                <option value="">-- Select an Activity --</option>
                <?php foreach ($related_options as $o): ?>
                    <option value="<?= htmlspecialchars($o) ?>" <?= ($form['related_activity'] === $o) ? 'selected' : '' ?>><?= htmlspecialchars($o) ?></option>
                <?php endforeach; ?>
            </select>

            <label for="caption">Caption</label>
            <textarea name="caption" id="caption" rows="4" maxlength="1000"><?= htmlspecialchars($form['caption']) ?></textarea>

            <label for="image">Photo <span class="form-hint">(JPG or PNG, max 5MB)</span></label>
            <input type="file" name="image" id="image" accept=".jpg,.jpeg,.png,image/jpeg,image/png">

            <div class="auth-actions">
                <button type="submit" class="card-btn">Submit Post</button>
                <a href="community.php" class="card-btn secondary-btn">Cancel</a>
            </div>
        </form>
    </div>
</main>

<?php include 'footer.php'; ?>
