<?php
require_once 'includes/auth.php';
require_once 'includes/db.php';
require_role('user');   // Task 3.3: must be logged in as type "user"

// Load the user's current information from the database on page load.
$db   = get_db();
$stmt = $db->prepare('SELECT first_name, last_name, dob, gender, hometown, profile_image, resume_path FROM user_table WHERE email = ?');
$stmt->bind_param('s', $_SESSION['email']);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

// If process_update_profile.php rejected the form, show what the user typed
// (instead of the database values) together with the error messages.
$errors = $_SESSION['up_errors'] ?? [];
$old    = $_SESSION['up_old'] ?? [];
unset($_SESSION['up_errors'], $_SESSION['up_old']);

$form = $user ? array_merge($user, ['email' => $_SESSION['email']], $old) : [];

$photo = 'img/avatars/female-default.jpg';
if ($user) {
    $photo = !empty($user['profile_image']) && is_file($user['profile_image'])
        ? $user['profile_image']
        : (($user['gender'] === 'Male') ? 'img/avatars/male-default.jpg' : 'img/avatars/female-default.jpg');
}

$page_title = 'Update Profile';
include 'header.php';
?>

<main class="page profile-page">
<?php if ($user): ?>

    <div class="profile-card">
        <img src="<?= htmlspecialchars($photo) ?>" alt="Profile photo" class="profile-photo">

        <h1>Update Profile</h1>

        <?php if (!empty($errors)): ?>
        <div class="form-errors">
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php endif; ?>

        <form action="process_update_profile.php" method="POST" enctype="multipart/form-data" class="update-form">
            <label>First Name
                <input type="text" name="first_name" maxlength="50" value="<?= htmlspecialchars($form['first_name'] ?? '') ?>">
            </label>
            <label>Last Name
                <input type="text" name="last_name" maxlength="50" value="<?= htmlspecialchars($form['last_name'] ?? '') ?>">
            </label>
            <label>Date of Birth
                <input type="date" name="dob" value="<?= htmlspecialchars($form['dob'] ?? '') ?>">
            </label>
            <label>Gender
                <select name="gender">
                    <option value="Female" <?= (($form['gender'] ?? '') === 'Female') ? 'selected' : '' ?>>Female</option>
                    <option value="Male" <?= (($form['gender'] ?? '') === 'Male') ? 'selected' : '' ?>>Male</option>
                </select>
            </label>
            <label>Email
                <input type="email" name="email" maxlength="50" value="<?= htmlspecialchars($form['email'] ?? '') ?>">
            </label>
            <label>Hometown
                <input type="text" name="hometown" maxlength="50" value="<?= htmlspecialchars($form['hometown'] ?? '') ?>">
            </label>

            <label>New Password <span class="form-hint">(leave blank to keep your current password)</span>
                <div class="password-wrapper">
                    <input type="password" name="new_password" id="new_password" autocomplete="new-password">
                    <button type="button" class="toggle-password" data-target="new_password">👁</button>
                </div>
            </label>
            <label>Confirm New Password
                <div class="password-wrapper">
                    <input type="password" name="confirm_password" id="confirm_password" autocomplete="new-password">
                    <button type="button" class="toggle-password" data-target="confirm_password">👁</button>
                </div>
            </label>

            <label>Profile Photo <span class="form-hint">(JPG, JPEG or PNG, max 5MB)</span>
                <input type="file" name="profile_photo" accept=".jpg,.jpeg,.png,image/jpeg,image/png">
            </label>

            <label>Build Portfolio <span class="form-hint">(optional, PDF only, max 7MB: photos of your builds, a workshop certificate or Gundam-related work)</span>
                <input type="file" name="resume" accept=".pdf,application/pdf">
            </label>
            <?php if (!empty($user['resume_path']) && is_file($user['resume_path'])): ?>
                <p class="form-hint">Current portfolio: <a href="<?= htmlspecialchars($user['resume_path']) ?>" target="_blank">View PDF</a>. Uploading a new one replaces it.</p>
            <?php endif; ?>

            <div class="profile-links">
                <button type="submit" class="card-btn">Update</button>
                <a href="profile.php" class="card-btn secondary-btn">Cancel</a>
            </div>
        </form>
    </div>

<?php else: ?>

    <div class="detail-not-found">
        <h1>Profile data not found</h1>
        <p>We couldn't find your account details. Please try logging in again.</p>
        <a href="login.php" class="card-btn">Log In</a>
    </div>

<?php endif; ?>
</main>

<?php include 'footer.php'; ?>
