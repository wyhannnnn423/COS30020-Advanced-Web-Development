<?php
session_start();
$field_errors = [];
$data_dir = 'data/Activity';
$data_file = $data_dir . '/bookings.txt';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Strip the "|" delimiter from every input so it can't break the
    // pipe-delimited storage format, then trim whitespace.
    $first_name    = str_replace('|', '', trim($_POST['first_name'] ?? ''));
    $last_name     = str_replace('|', '', trim($_POST['last_name'] ?? ''));
    $contact       = str_replace('|', '', trim($_POST['contact'] ?? ''));
    $email         = str_replace('|', '', trim($_POST['email'] ?? ''));
    $session_slot  = str_replace('|', '', trim($_POST['session_slot'] ?? ''));
    $activity_title = str_replace('|', '', trim($_POST['activity_title'] ?? ''));
    $activity_key  = $_POST['activity'] ?? '';
    $came_from     = $_POST['came_from'] ?? 'activities.php';

    // ---- Validation (same style as process_registration.php) ----

    if ($first_name === '') {
        $field_errors['first_name'] = 'First name is required.';
    } elseif (!preg_match('/^[A-Za-z ]+$/', $first_name)) {
        $field_errors['first_name'] = 'Letters and spaces only.';
    }

    if ($last_name === '') {
        $field_errors['last_name'] = 'Last name is required.';
    } elseif (!preg_match('/^[A-Za-z ]+$/', $last_name)) {
        $field_errors['last_name'] = 'Letters and spaces only.';
    }

    if ($contact === '') {
        $field_errors['contact'] = 'Contact number is required.';
    }

    if ($email === '') {
        $field_errors['email'] = 'Email is required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $field_errors['email'] = 'Please enter a valid email address.';
    }

    if ($session_slot === '') {
        $field_errors['session_slot'] = 'Please select a session.';
    }

    // ---- Save the record if everything passed ----

    if (empty($field_errors)) {

        if (!file_exists($data_dir)) {
            mkdir($data_dir, 0777, true);
        }

        $record = 'First Name:' . $first_name
                . '|LastName:' . $last_name
                . '|Contact:' . $contact
                . '|Email:' . $email
                . '|SessionSlot:' . $session_slot
                . '|ActivityTitle:' . $activity_title;

        if (file_put_contents($data_file, $record . PHP_EOL, FILE_APPEND | LOCK_EX) !== false) {
            // Success — send the user back to wherever they started
            // (series_detail.php or activities.php), with a flag that
            // triggers the "You're registered!" toast on that page.
            $separator = (strpos($came_from, '?') !== false) ? '&' : '?';
            $redirect = $came_from . $separator . 'booked=1';
            header('Location: ' . $redirect);
            exit;
        } else {
            $field_errors['general'] = 'Could not save your registration. Please try again.';
        }
    }

    // ---- If there were errors, send the user back to the same form ----
    // Store what they typed and the specific field errors in the session,
    // so activity_reg.php can show them again without wiping the form.
    if (!empty($field_errors)) {
        $_SESSION['activity_form_old'] = [
            'first_name'   => $first_name,
            'last_name'    => $last_name,
            'contact'      => $contact,
            'email'        => $email,
            'session_slot' => $session_slot,
        ];
        $_SESSION['activity_field_errors'] = $field_errors;

        $back_url = 'activity_reg.php?activity=' . urlencode($activity_key);
        header('Location: ' . $back_url);
        exit;
    }
}
?>