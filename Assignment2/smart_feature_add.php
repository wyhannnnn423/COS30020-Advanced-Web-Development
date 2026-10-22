<?php
require_once 'includes/auth.php';
require_once 'includes/db.php';
require_once 'includes/upload.php';
start_session_once();

$page_title = 'Contribute a Kit';

// Task 3.4: only logged-in users of type "user" may contribute. Everyone else sees a page
// that points them to login.php instead of the form.
if (!is_logged_in() || $_SESSION['type'] !== 'user') {
    include 'header.php';
    ?>
    <main class="auth-page">
        <img src="img/auth/register.jpg" alt="" class="auth-page-bg">
        <div class="auth-page-overlay"></div>
        <div class="auth-card auth-card-single">
            <h1>Please Log In</h1>
            <p class="auth-subtitle">You need to be logged in as a user to contribute a kit.</p>
            <a href="login.php?redirect=smart_feature_add.php" class="card-btn">Go to Log In</a>
        </div>
    </main>
    <?php
    include 'footer.php';
    exit;
}

// Categories offered in the form; also the allowed values on the server.
$categories = ['Entry Grade', 'High Grade', 'Real Grade', 'Master Grade', 'Perfect Grade',
               'Full Mechanics', 'SD', 'Re/100'];

$errors  = [];
$success = false;
$name    = '';
$cat     = '';

if (post_too_large()) {
    $errors[] = 'The upload was too large for the server. The PDF must be 7MB or less and the photo 5MB or less.';
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['item_name'] ?? '');
    $cat  = trim($_POST['item_category'] ?? '');

    if ($name === '') {
        $errors[] = 'Kit name is required.';
    } elseif (mb_strlen($name) > 50) {
        $errors[] = 'Kit name must be 50 characters or fewer.';
    }
    if (!in_array($cat, $categories, true)) {
        $errors[] = 'Please choose a category.';
    }

    $pdf_path = null;
    $img_path = null;

    if (empty($errors)) {
        // PDF description: required, PDF only, max 7MB, stored in smart_feature_description/
        $err = '';
        if (empty($_FILES['description_file']) || $_FILES['description_file']['error'] === UPLOAD_ERR_NO_FILE) {
            $errors[] = 'Please attach a PDF description.';
        } else {
            $pdf_path = save_upload($_FILES['description_file'], ['pdf'], 7 * 1048576, 'smart_feature_description', $err);
            if ($err !== '') {
                $errors[] = 'PDF description: ' . $err;
            }
        }

        // Image: optional, JPG/PNG, max 5MB
        $err = '';
        $img_path = save_upload($_FILES['item_image'] ?? [], ['jpg', 'jpeg', 'png'], 5 * 1048576, 'img/user_uploads', $err);
        if ($err !== '') {
            $errors[] = 'Image: ' . $err;
        }
    }

    if (empty($errors)) {
        $status = 'pending';   // an admin has to approve it before it can appear in results
        try {
            $db   = get_db();
            $stmt = $db->prepare(
                'INSERT INTO smart_feature_table (item_name, item_category, item_image, description_file, status)
                 VALUES (?, ?, ?, ?, ?)'
            );
            $stmt->bind_param('sssss', $name, $cat, $img_path, $pdf_path, $status);
            $stmt->execute();
            header('Location: smart_feature.php?kit_submitted=1');
            exit;
        } catch (mysqli_sql_exception $e) {
            $errors[] = 'Could not save your entry. Please try again.';
        }
    }

    // Do not leave orphaned files behind if anything failed.
    if (!empty($errors)) {
        foreach ([$pdf_path, $img_path] as $f) {
            if ($f !== null && is_file($f)) { unlink($f); }
        }
    }
}

include 'header.php';
?>

<main class="auth-page">
    <img src="img/auth/register.jpg" alt="" class="auth-page-bg">
    <div class="auth-page-overlay"></div>

    <div class="auth-card auth-card-single">
        <h1>Contribute a Kit</h1>
        <p class="auth-subtitle">Suggest a kit for the Kit Recommender. An admin will review it before it appears.</p>

        <?php if ($success): ?>
        <div class="form-success">
            <p>Thank you! Your kit was submitted and is now <strong>pending</strong> admin approval.</p>
        </div>
        <?php endif; ?>

        <?php if (!empty($errors)): ?>
        <div class="form-errors">
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php endif; ?>

        <form action="smart_feature_add.php" method="POST" enctype="multipart/form-data" class="auth-form">
            <label for="item_name">Kit Name</label>
            <input type="text" name="item_name" id="item_name" maxlength="50" value="<?= htmlspecialchars($name) ?>">

            <label for="item_category">Category</label>
            <select name="item_category" id="item_category">
                <option value="">-- Select a Category --</option>
                <?php foreach ($categories as $c): ?>
                    <option value="<?= htmlspecialchars($c) ?>" <?= ($cat === $c) ? 'selected' : '' ?>><?= htmlspecialchars($c) ?></option>
                <?php endforeach; ?>
            </select>

            <label for="item_image">Kit Photo <span class="form-hint">(optional, JPG or PNG, max 5MB)</span></label>
            <input type="file" name="item_image" id="item_image" accept=".jpg,.jpeg,.png,image/jpeg,image/png">

            <label for="description_file">PDF Description <span class="form-hint">(required, PDF only, max 7MB)</span></label>
            <input type="file" name="description_file" id="description_file" accept=".pdf,application/pdf">

            <div class="auth-actions">
                <button type="submit" class="card-btn">Submit Kit</button>
                <a href="main_menu.php" class="card-btn secondary-btn">Back to Menu</a>
            </div>
        </form>
    </div>
</main>

<?php include 'footer.php'; ?>
