<?php
require_once 'includes/auth.php';
require_once 'includes/db.php';
require_role('user');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: activities.php');
    exit;
}

$field_errors = [];

$first_name   = trim($_POST['first_name'] ?? '');
$last_name    = trim($_POST['last_name'] ?? '');
$contact      = trim($_POST['contact'] ?? '');
$session_slot = trim($_POST['session_slot'] ?? '');
$activity_key = trim($_POST['activity'] ?? '');
$email        = $_SESSION['email'];   // always the logged-in account, never taken from the form
$came_from    = safe_redirect_target(urldecode($_POST['came_from'] ?? ''), 'activities.php');

// Allowed sessions (value => [date, time]); must match the options in activity_reg.php
$slots = [
    '2026-12-05 10:00' => ['2026-12-05', '10:00:00'],
    '2026-12-05 14:00' => ['2026-12-05', '14:00:00'],
    '2026-12-12 10:00' => ['2026-12-12', '10:00:00'],
    '2026-12-12 14:00' => ['2026-12-12', '14:00:00'],
];

// Work out the activity title on the server instead of trusting the hidden form field.
$activity_title = '';
if (strpos($activity_key, 'ws-') === 0) {
    require_once 'data_activities.php';   // $workshops
    if (isset($workshops[$activity_key])) {
        $activity_title = 'Workshop: ' . $workshops[$activity_key]['title'];
    }
} elseif (strpos($activity_key, 'screening-') === 0 || strpos($activity_key, 'session-') === 0) {
    require_once 'data_series.php';        // $items
    $is_screening = strpos($activity_key, 'screening-') === 0;
    $series_id    = substr($activity_key, $is_screening ? strlen('screening-') : strlen('session-'));
    if (isset($items[$series_id])) {
        $activity_title = ($is_screening ? 'Screening: ' : 'Game Session: ') . $items[$series_id]['title'];
    }
}
if ($activity_title === '') {
    $field_errors['general'] = 'Unknown activity. Please start again from the Activities page.';
}
$activity_title = mb_substr($activity_title, 0, 100);

// ---------------------------------------------------------------- validation
if ($first_name === '') {
    $field_errors['first_name'] = 'First name is required.';
} elseif (!preg_match('/^[A-Za-z ]{1,50}$/', $first_name)) {
    $field_errors['first_name'] = 'Letters and spaces only (max 50).';
}

if ($last_name === '') {
    $field_errors['last_name'] = 'Last name is required.';
} elseif (!preg_match('/^[A-Za-z ]{1,50}$/', $last_name)) {
    $field_errors['last_name'] = 'Letters and spaces only (max 50).';
}

if ($contact === '') {
    $field_errors['contact'] = 'Contact number is required.';
} elseif (!preg_match('/^[0-9+\- ]{7,15}$/', $contact)) {
    $field_errors['contact'] = 'Enter 7 to 15 digits (you may use +, - and spaces).';
}

if ($session_slot === '') {
    $field_errors['session_slot'] = 'Please select a session.';
} elseif (!isset($slots[$session_slot])) {
    $field_errors['session_slot'] = 'Please choose one of the listed sessions.';
}

// ------------------------------------------------------------- save to the DB
if (empty($field_errors)) {
    [$date, $time] = $slots[$session_slot];
    $db = get_db();

    // Stop the same person booking the same slot twice (a rejected booking may be repeated).
    $chk = $db->prepare(
        "SELECT 1 FROM activity_table
         WHERE email = ? AND activity_title = ? AND `date` = ? AND `time` = ? AND status <> 'rejected'"
    );
    $chk->bind_param('ssss', $email, $activity_title, $date, $time);
    $chk->execute();

    if ($chk->get_result()->num_rows > 0) {
        $field_errors['general'] = 'You have already registered for this session.';
    } else {
        $status = 'pending';   // an admin approves or rejects it later
        $ins = $db->prepare(
            'INSERT INTO activity_table (email, first_name, last_name, `date`, `time`, contact_number, status, activity_title)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?)'
        );
        $ins->bind_param('ssssssss', $email, $first_name, $last_name, $date, $time, $contact, $status, $activity_title);

        try {
            $ins->execute();
            header('Location: ' . add_flag($came_from, 'booked'));
            exit;
        } catch (mysqli_sql_exception $e) {
            $field_errors['general'] = 'Could not save your registration. Please try again.';
        }
    }
}

// Something failed: go back to the form with the errors and what was typed.
$_SESSION['activity_form_old'] = [
    'first_name' => $first_name, 'last_name' => $last_name,
    'contact' => $contact, 'session_slot' => $session_slot,
];
$_SESSION['activity_field_errors'] = $field_errors;

$param = (strpos($activity_key, 'ws-') === 0) ? 'workshop' : 'activity';
header('Location: activity_reg.php?' . $param . '=' . urlencode($activity_key));
exit;
