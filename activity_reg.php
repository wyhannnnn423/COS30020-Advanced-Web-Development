<?php 
session_start();

$page_title = 'Activity Registration'; 

$activity_key = isset($_GET['activity']) ? $_GET['activity'] : '';
$workshop_key = isset($_GET['workshop']) ? $_GET['workshop'] : '';

// ----------------------LOGIC---------------------------------
// Where should Cancel / a successful submission send the user back to?
// We don't rely on HTTP_REFERER (it can be blocked by browser privacy
// settings and gets lost across the login redirect). Instead every
// booking link (series_detail.php, activities.php) passes its own
// page explicitly via ?from=...
// ------------------------------------------------------------------
if (isset($_GET['from'])) {
    // Normal case: user is already logged in and clicked a booking button.
    $came_from = urldecode($_GET['from']);
} elseif (isset($_SESSION['activity_origin'])) {
    // User was NOT logged in — we stored the origin before sending them
    // to login.php, and they've just been bounced back here.
    $came_from = $_SESSION['activity_origin'];
} else {
    $came_from = 'activities.php';
}

// ----------------------LOGIC---------------------------------
// Require login before booking any activity.
// ------------------------------------------------------------------
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    // Save the real origin page so it survives the login redirect.
    $_SESSION['activity_origin'] = $came_from;

    $redirect_target = urlencode($_SERVER['REQUEST_URI']);
    header('Location: login.php?redirect=' . $redirect_target);
    exit;
}

// User is confirmed logged in now — the stored origin (if any) has
// done its job, clear it so it doesn't leak into a later unrelated visit.
unset($_SESSION['activity_origin']);

include 'header.php'; 

// ----------------------LOGIC---------------------------------
// Work out the display title + poster image for this booking.
// Handles two cases: anime/game (?activity=) or workshop (?workshop=).
// ------------------------------------------------------------------
$display_title = '';
$poster_image = 'img/auth/register.jpg'; // fallback if nothing matches

if ($activity_key !== '') {
    require_once 'data_series.php'; // loads $items

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
    if ($series_item) {
        $display_title = $activity_type . ': ' . $series_item['title'];
        $poster_image = $series_item['image'];
    } else {
        $display_title = $activity_key;
    }

} elseif ($workshop_key !== '') {
    require_once 'data_activities.php'; // loads $workshops (and $competitions, unused here) — shared with activities.php

    $workshop_item = isset($workshops[$workshop_key]) ? $workshops[$workshop_key] : null;
    if ($workshop_item) {
        $display_title = 'Workshop: ' . $workshop_item['title'];
        $poster_image = $workshop_item['image'];
    } else {
        $display_title = $workshop_key;
    }
}

// The value stored in the hidden "activity" field and sent to
// process_activity_reg.php — works for both activity and workshop bookings.
$booking_key = $activity_key !== '' ? $activity_key : $workshop_key;

// If process_activity_reg.php sent the user back here because of a
// validation error, pick up the values they typed and the per-field
// error messages, then clear them from the session.
$old = $_SESSION['activity_form_old'] ?? [];
$field_errors = $_SESSION['activity_field_errors'] ?? [];
unset($_SESSION['activity_form_old'], $_SESSION['activity_field_errors']);
?>

<main class="auth-page">
    <img src="img/auth/register.jpg" alt="" class="auth-page-bg">
    <div class="auth-page-overlay"></div>

    <div class="auth-card">

        <!-- Poster of the anime / game / workshop being booked -->
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
                <input type="hidden" name="activity" value="<?php echo htmlspecialchars($booking_key); ?>">
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
                    <?php
                    // Fixed: value and displayed label now describe the SAME
                    // date/time, and the "selected" check compares against
                    // the same string that's actually stored in $old.
                    $slot_options = [
                        'Sat 6 Dec, 10:00 AM' => 'Sat 6 Dec, 10:00 AM',
                        'Sat 6 Dec, 2:00 PM'  => 'Sat 6 Dec, 2:00 PM',
                        'Sat 13 Dec, 10:00 AM' => 'Sat 13 Dec, 10:00 AM',
                        'Sat 13 Dec, 2:00 PM'  => 'Sat 13 Dec, 2:00 PM',
                    ];
                    foreach ($slot_options as $value => $label):
                    ?>
                        <option value="<?php echo htmlspecialchars($value); ?>" <?php echo (($old['session_slot'] ?? '') === $value) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($label); ?>
                        </option>
                    <?php endforeach; ?>
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