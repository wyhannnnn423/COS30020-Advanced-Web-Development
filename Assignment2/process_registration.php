<?php
require_once 'includes/auth.php';
require_once 'includes/db.php';
require_once 'includes/upload.php';

start_session_once();

// This script only handles form submissions.
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: registration.php');
    exit;
}

$errors = [];

// A photo that is too big for PHP's post_max_size arrives as an empty form.
if (post_too_large()) {
    $_SESSION['reg_errors'] = ['The upload was too large for the server. The profile photo must be 5MB or less.'];
    header('Location: registration.php');
    exit;
}

$first_name = trim($_POST['first_name'] ?? '');
$last_name  = trim($_POST['last_name'] ?? '');
$dob        = trim($_POST['dob'] ?? '');
$gender     = trim($_POST['gender'] ?? '');
$email      = trim($_POST['email'] ?? '');
$hometown   = trim($_POST['hometown'] ?? '');
$sec_q      = trim($_POST['security_question'] ?? '');
$sec_a      = trim($_POST['security_answer'] ?? '');
$password   = $_POST['password'] ?? '';
$confirm    = $_POST['confirm_password'] ?? '';

// ------------------------------------------------------------------ validation
if ($first_name === '' || $last_name === '' || $dob === '' || $gender === ''
    || $email === '' || $hometown === '' || $password === '' || $confirm === '') {
    $errors[] = 'All fields are required.';
}

if ($sec_q === '' || $sec_a === '') {
    $errors[] = 'Please select a security question and provide an answer.';
} elseif (!in_array($sec_q, ['first_pet', 'live_street', 'favorite_teacher'], true)) {
    $errors[] = 'Invalid security question.';
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

if ($password !== '') {
    if (strlen($password) < 8) {
        $errors[] = 'Password must be at least 8 characters long.';
    }
    if (!preg_match('/[0-9]/', $password)) {
        $errors[] = 'Password must contain at least one number.';
    }
    if (!preg_match('/[^A-Za-z0-9]/', $password)) {
        $errors[] = 'Password must contain at least one symbol.';
    }
}
if ($password !== '' && $confirm !== '' && $password !== $confirm) {
    $errors[] = 'Passwords do not match.';
}

// ------------------------------------------------------------ duplicate email
if (empty($errors)) {
    $db   = get_db();
    $stmt = $db->prepare('SELECT 1 FROM user_table WHERE email = ?');
    $stmt->bind_param('s', $email);
    $stmt->execute();
    if ($stmt->get_result()->num_rows > 0) {
        $errors[] = 'An account with this email already exists.';
    }
}

// -------------------------------------------------------------- profile photo
$photo_path = null;
if (empty($errors)) {
    $err = '';
    $photo_path = save_upload($_FILES['profile_photo'] ?? [], ['jpg', 'jpeg', 'png'], 5 * 1048576, 'profile_images', $err);
    if ($err !== '') {
        $errors[] = 'Profile photo: ' . $err;
    }
}

// ------------------------------------------------------------------- save it
if (empty($errors)) {
    $hash     = password_hash($password, PASSWORD_DEFAULT);
    $ans_hash = password_hash(strtolower($sec_a), PASSWORD_DEFAULT);
    $type     = 'user';   // registration always creates a normal user, never an admin

    try {
        $db->begin_transaction();

        $insUser = $db->prepare(
            'INSERT INTO user_table (email, first_name, last_name, dob, gender, hometown, profile_image) VALUES (?, ?, ?, ?, ?, ?, ?)'
        );
        $insUser->bind_param('sssssss', $email, $first_name, $last_name, $dob, $gender, $hometown, $photo_path);
        $insUser->execute();

        $insAcc = $db->prepare(
            'INSERT INTO account_table (email, password, type, security_question, security_answer) VALUES (?, ?, ?, ?, ?)'
        );
        $insAcc->bind_param('sssss', $email, $hash, $type, $sec_q, $ans_hash);
        $insAcc->execute();

        $db->commit();

        header('Location: login.php?registered=1');
        exit;
    } catch (mysqli_sql_exception $e) {
        $db->rollback();
        delete_stored_file($photo_path);   // don't leave the photo behind if nothing was saved
        // 1062 = duplicate key: someone registered the same email a moment ago
        $errors[] = ($e->getCode() === 1062)
            ? 'An account with this email already exists.'
            : 'Could not save your registration. Please try again.';
    }
}

// Something failed: go back to the form with the errors and what was typed.
$_SESSION['reg_errors'] = $errors;
$_SESSION['reg_old'] = [
    'first_name' => $first_name, 'last_name' => $last_name, 'dob' => $dob,
    'gender' => $gender, 'email' => $email, 'hometown' => $hometown,
    'security_question' => $sec_q, 'security_answer' => $sec_a,
];
header('Location: registration.php');
exit;
