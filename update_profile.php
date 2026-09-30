<?php 
session_start();

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: login.php?redirect=' . urlencode($_SERVER['REQUEST_URI']));
    exit;
}

$page_title = 'Update Profile'; 
include 'header.php'; 

$data_file = 'data/User/user.txt';
$user = null;

// Find the record matching the CURRENT logged-in user's email,
// instead of always reading the first line in the file.
if (file_exists($data_file)) {
    $lines = file($data_file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $fields = explode('|', $line);
        $candidate = [];
        foreach ($fields as $field) {
            $pair = explode(':', $field, 2);
            if (count($pair) === 2) {
                $candidate[$pair[0]] = $pair[1];
            }
        }
        if (isset($candidate['Email']) && $candidate['Email'] === $_SESSION['email']) {
            $user = $candidate;
            break;
        }
    }
}
?>

<main class="page profile-page">
<?php if ($user): ?>

    <div class="profile-card">
        <?php 
        $gender = isset($user['Gender']) ? strtolower($user['Gender']) : 'female';
        $default_img = ($gender === 'male') ? 'img/avatars/male-default.jpg' : 'img/avatars/female-default.jpg';
        ?>
        <img src="<?php echo htmlspecialchars($default_img); ?>" alt="Profile photo" class="profile-photo">

        <h1>Update Profile</h1>

        <form action="process_update_profile.php" method="POST" class="update-form">
            <!-- Track which record to update by its original email -->
            <input type="hidden" name="original_email" value="<?php echo htmlspecialchars($user['Email']); ?>">

            <label>First Name
                <input type="text" name="first_name" value="<?php echo htmlspecialchars($user['First Name'] ?? ''); ?>">
            </label>
            <label>Last Name
                <input type="text" name="last_name" value="<?php echo htmlspecialchars($user['LastName'] ?? ''); ?>">
            </label>
            <label>Date of Birth
                <input type="date" name="dob" value="<?php echo htmlspecialchars($user['DOB'] ?? ''); ?>">
            </label>
            <label>Gender
                <select name="gender">
                    <option value="Female" <?php echo (($user['Gender'] ?? '') === 'Female') ? 'selected' : ''; ?>>Female</option>
                    <option value="Male" <?php echo (($user['Gender'] ?? '') === 'Male') ? 'selected' : ''; ?>>Male</option>
                </select>
            </label>
            <label>Email
                <input type="text" name="email" value="<?php echo htmlspecialchars($user['Email'] ?? ''); ?>">
            </label>
            <label>Hometown
                <input type="text" name="hometown" value="<?php echo htmlspecialchars($user['Hometown'] ?? ''); ?>">
            </label>
            <label>New Password (leave blank to keep current password)
                <div class="password-wrapper">
                    <input type="password" name="new_password" autocomplete="new-password">
                    <button type="button" class="toggle-password" data-target="new_password">👁</button>
                </div>
            </label>
            <div class="profile-links">
                <button type="submit" class="card-btn">Update</button>
                <a href="main_menu.php" class="card-btn secondary-btn">Cancel</a>
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