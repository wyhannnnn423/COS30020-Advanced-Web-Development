<?php 
session_start();

// Require login before booking any activity.
// If not logged in, remember the full URL they were trying to reach
// (including ?activity=... query string) and send them to login first.
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    $redirect_target = urlencode($_SERVER['REQUEST_URI']);
    header('Location: login.php?redirect=' . $redirect_target);
    exit;
}

$page_title = 'Activity Registration'; 
include 'header.php'; 
require_once 'data_series.php'; // Loads the $items array of anime/game entries

$activity_key = isset($_GET['activity']) ? $_GET['activity'] : '';

// Remember which page the user came from, so we can send them back
// to the exact same place after they finish (or cancel).
$came_from = $_SERVER['HTTP_REFERER'] ?? 'activities.php';

// If the referrer is login.php or this same page, it's not a real
// "came from" page — fall back to activities.php instead.
if (strpos($came_from, 'login.php') !== false || strpos($came_from, 'activity_reg.php') !== false) {
    $came_from = 'activities.php';
}

// The activity key looks like "screening-mobile-suit-gundam-1979" or
// "session-gundam-versus". Split it into a type label and the series id
// so we can look up the real title/poster from data_series.php.
$activity_type = '';
$series_id = '';
if (strpos($activity_key, 'screening-') === 0) {
    $activity_type = 'Screening';
    $series_id = substr($activity_key, strlen('screening-'));
} elseif (strpos($activity_key, 'session-') === 0) {
    $activity_type = 'Game Session';
    $series_id = substr($activity_key, strlen('session-'));
}

$series_item = isset($items[$series_id]) ? $items[$series_id] : null;
$display_title = $series_item ? ($activity_type . ': ' . $series_item['title']) : $activity_key;
$poster_image = $series_item ? $series_item['image'] : 'img/auth/register.jpg';

// If process_activity_reg.php sent the user back here because of a
// validation error, pick up the values they typed and the per-field
// error messages, then clear them from the session so a page refresh
// doesn't show stale errors again.
$old = $_SESSION['activity_form_old'] ?? [];
$field_errors = $_SESSION['activity_field_errors'] ?? [];
unset($_SESSION['activity_form_old'], $_SESSION['activity_field_errors']);
?>

<main class="auth-page">
    <img src="img/auth/register.jpg" alt="" class="auth-page-bg">
    <div class="auth-page-overlay"></div>

    <div class="auth-card">

        <!-- Poster of the anime/game being booked -->
        <div class="auth-visual">
            <img src="<?php echo htmlspecialchars($poster_image); ?>" alt="" class="auth-bg-img">
        </div>

        <div class="auth-form-side">
            <h1>Register for Activity</h1>
            <p class="auth-subtitle"><?php echo htmlspecialchars($display_title); ?></p>

            <?php if (isset($field_errors['general'])): ?>
            <div class="form-errors">
                <ul>
                    <li><?php echo htmlspecialchars($field_errors['general']); ?></li>
                </ul>
            </div>
            <?php endif; ?>

            <form action="process_activity_reg.php" method="POST" class="auth-form">
                <!-- Hidden fields carry context through to the processing script -->
                <input type="hidden" name="activity" value="<?php echo htmlspecialchars($activity_key); ?>">
                <input type="hidden" name="came_from" value="<?php echo htmlspecialchars($came_from); ?>">
                <input type="hidden" name="activity_title" value="<?php echo htmlspecialchars($display_title); ?>">

                <label for="first_name">First Name</label>
                <input type="text" name="first_name" id="first_name" value="<?php echo htmlspecialchars($old['first_name'] ?? ''); ?>">
                <?php if (isset($field_errors['first_name'])): ?>
                    <span class="error-message" style="display:block;"><?php echo htmlspecialchars($field_errors['first_name']); ?></span>
                <?php endif; ?>

                <label for="last_name">Last Name</label>
                <input type="text" name="last_name" id="last_name" value="<?php echo htmlspecialchars($old['last_name'] ?? ''); ?>">
                <?php if (isset($field_errors['last_name'])): ?>
                    <span class="error-message" style="display:block;"><?php echo htmlspecialchars($field_errors['last_name']); ?></span>
                <?php endif; ?>

                <label for="contact">Contact Number</label>
                <input type="text" name="contact" id="contact" value="<?php echo htmlspecialchars($old['contact'] ?? ''); ?>">
                <?php if (isset($field_errors['contact'])): ?>
                    <span class="error-message" style="display:block;"><?php echo htmlspecialchars($field_errors['contact']); ?></span>
                <?php endif; ?>

                <label for="email">Email</label>
                <!-- Default to the logged-in user's own email if nothing was typed before -->
                <input type="text" name="email" id="email" value="<?php echo htmlspecialchars($old['email'] ?? $_SESSION['email']); ?>">
                <?php if (isset($field_errors['email'])): ?>
                    <span class="error-message" style="display:block;"><?php echo htmlspecialchars($field_errors['email']); ?></span>
                <?php endif; ?>

                <label for="session_slot">Preferred Session</label>
                <select name="session_slot" id="session_slot">
                    <option value="">-- Select a Session --</option>
                    <option value="2026-09-27 10:00 AM" <?php echo (($old['session_slot'] ?? '') === '2026-12-2 10:00 AM') ? 'selected' : ''; ?>>Sat 2 Dec, 10:00 AM</option>
                    <option value="2026-09-27 02:00 PM" <?php echo (($old['session_slot'] ?? '') === '2026-12-2 02:00 PM') ? 'selected' : ''; ?>>Sat 2 Dec, 2:00 PM</option>
                    <option value="2026-10-04 10:00 AM" <?php echo (($old['session_slot'] ?? '') === '2026-12-9 10:00 AM') ? 'selected' : ''; ?>>Sat 9 Dec, 10:00 AM</option>
                    <option value="2026-10-04 02:00 PM" <?php echo (($old['session_slot'] ?? '') === '2026-12-9 02:00 PM') ? 'selected' : ''; ?>>Sat 9 Dec, 2:00 PM</option>
                </select>
                <?php if (isset($field_errors['session_slot'])): ?>
                    <span class="error-message" style="display:block;"><?php echo htmlspecialchars($field_errors['session_slot']); ?></span>
                <?php endif; ?>

                <div class="auth-actions">
                    <button type="submit" class="card-btn">Confirm Registration</button>
                    <!-- Cancel just takes the user back where they came from, no reset needed -->
                    <a href="<?php echo htmlspecialchars($came_from); ?>" class="card-btn secondary-btn">Cancel</a>
                </div>
            </form>
        </div>

    </div>
</main>

<?php include 'footer.php'; ?>