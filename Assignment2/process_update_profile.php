<?php
require_once 'includes/auth.php';
require_once 'includes/db.php';
require_once 'includes/upload.php';
require_role('user');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: update_profile.php');
    exit;
}

if (post_too_large()) {
    $_SESSION['up_errors'] = ['The upload was too large for the server. Photos must be 5MB or less and PDFs 7MB or less.'];
    header('Location: update_profile.php');
    exit;
}

$db         = get_db();
$old_email  = $_SESSION['email'];
$errors     = [];

$first_name = trim($_POST['first_name'] ?? '');
$last_name  = trim($_POST['last_name'] ?? '');
$dob        = trim($_POST['dob'] ?? '');
$gender     = trim($_POST['gender'] ?? '');
$email      = trim($_POST['email'] ?? '');
$hometown   = trim($_POST['hometown'] ?? '');
$new_pass   = $_POST['new_password'] ?? '';
$confirm    = $_POST['confirm_password'] ?? '';

// ------------------------------------------------------------------ validation
if ($first_name === '' || $last_name === '' || $dob === '' || $gender === '' || $email === '' || $hometown === '') {
    $errors[] = 'All fields are required.';
}
if ($first_name !== '' && !preg_match('/^[A-Za-z ]{1,50}$/', $first_name)) {
    $errors[] = 'First name may only contain letters and spaces (max 50).';
}
if ($last_name !== '' && !preg_match('/^[A-Za-z ]{1,50}$/', $last_name)) {
    $errors[] = 'Last name may only contain letters and spaces (max 50).';
}
if ($email !== '' && (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 50)) {
    $errors[] = 'Please enter a valid email address (max 50 characters).';
}
if ($dob !== '') {
    $d = DateTime::createFromFormat('Y-m-d', $dob);
    if (!$d || $d->format('Y-m-d') !== $dob || $d > new DateTime('today')) {
        $errors[] = 'Please enter a valid date of birth.';
    }
}
if ($gender !== '' && !in_array($gender, ['Female', 'Male'], true)) {
    $errors[] = 'Please select a valid gender.';
}
if ($hometown !== '' && strlen($hometown) > 50) {
    $errors[] = 'Hometown must be 50 characters or fewer.';
}
if ($new_pass !== '') {
    if (strlen($new_pass) < 8) {
        $errors[] = 'Password must be at least 8 characters long.';
    }
    if (!preg_match('/[0-9]/', $new_pass)) {
        $errors[] = 'Password must contain at least one number.';
    }
    if (!preg_match('/[^A-Za-z0-9]/', $new_pass)) {
        $errors[] = 'Password must contain at least one symbol.';
    }
    if ($new_pass !== $confirm) {
        $errors[] = 'Passwords do not match.';
    }
}

// New email must not belong to somebody else.
if (empty($errors) && strcasecmp($email, $old_email) !== 0) {
    $stmt = $db->prepare('SELECT 1 FROM user_table WHERE email = ?');
    $stmt->bind_param('s', $email);
    $stmt->execute();
    if ($stmt->get_result()->num_rows > 0) {
        $errors[] = 'That email address is already used by another account.';
    }
}

// --------------------------------------------------------------------- uploads
$new_photo  = null;
$new_resume = null;
if (empty($errors)) {
    $err = '';
    $new_photo = save_upload($_FILES['profile_photo'] ?? [], ['jpg', 'jpeg', 'png'], 5 * 1048576, 'profile_images', $err);
    if ($err !== '') {
        $errors[] = 'Profile photo: ' . $err;
    }

    $err = '';
    $new_resume = save_upload($_FILES['resume'] ?? [], ['pdf'], 7 * 1048576, 'resume', $err);
    if ($err !== '') {
        $errors[] = 'Build portfolio: ' . $err;
    }
}

// ------------------------------------------------------------------ save to DB
if (empty($errors)) {
    $cur = $db->prepare('SELECT profile_image, resume_path FROM user_table WHERE email = ?');
    $cur->bind_param('s', $old_email);
    $cur->execute();
    $current = $cur->get_result()->fetch_assoc();

    $photo_path  = $new_photo  ?? ($current['profile_image'] ?? null);
    $resume_path = $new_resume ?? ($current['resume_path'] ?? null);

    try {
        $db->begin_transaction();

        // Changing the email here also updates account_table, because the foreign key
        // is declared ON UPDATE CASCADE.
        $upd = $db->prepare(
            'UPDATE user_table SET email = ?, first_name = ?, last_name = ?, dob = ?, gender = ?,
                    hometown = ?, profile_image = ?, resume_path = ?
             WHERE email = ?'
        );
        $upd->bind_param('sssssssss', $email, $first_name, $last_name, $dob, $gender, $hometown, $photo_path, $resume_path, $old_email);
        $upd->execute();

        if ($new_pass !== '') {
            $hash = password_hash($new_pass, PASSWORD_DEFAULT);
            $pw = $db->prepare('UPDATE account_table SET password = ? WHERE email = ?');
            $pw->bind_param('ss', $hash, $email);   // already cascaded to the new email
            $pw->execute();
        }

        $db->commit();
    } catch (mysqli_sql_exception $e) {
        $db->rollback();
        foreach ([$new_photo, $new_resume] as $f) {
            if ($f !== null && is_file($f)) { unlink($f); }
        }
        $errors[] = ($e->getCode() === 1062)
            ? 'That email address is already used by another account.'
            : 'Could not save your changes. Please try again.';
    }

    if (empty($errors)) {
        // Remove the files that were just replaced.
        if ($new_photo !== null && !empty($current['profile_image']) && strpos($current['profile_image'], 'profile_images/') === 0 && is_file($current['profile_image'])) {
            unlink($current['profile_image']);
        }
        if ($new_resume !== null && !empty($current['resume_path']) && strpos($current['resume_path'], 'resume/') === 0 && is_file($current['resume_path'])) {
            unlink($current['resume_path']);
        }

        $_SESSION['email']      = $email;
        $_SESSION['first_name'] = $first_name;
        header('Location: profile.php?profile_updated=1');
        exit;
    }
} else {
    // Validation or upload failed: nothing was written, but remove any file that did get stored.
    foreach ([$new_photo, $new_resume] as $f) {
        if ($f !== null && is_file($f)) { unlink($f); }
    }
}

$_SESSION['up_errors'] = $errors;
$_SESSION['up_old'] = [
    'first_name' => $first_name, 'last_name' => $last_name, 'dob' => $dob,
    'gender' => $gender, 'email' => $email, 'hometown' => $hometown,
];
header('Location: update_profile.php');
exit;
