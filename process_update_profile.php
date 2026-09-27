<?php
session_start();

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

$data_file = 'data/User/user.txt';
$errors = [];

// Proceed only if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $original_email = $_POST['original_email'] ?? '';
    $first_name = str_replace('|', '', trim($_POST['first_name'] ?? ''));
    $last_name  = str_replace('|', '', trim($_POST['last_name'] ?? ''));
    $dob        = str_replace('|', '', trim($_POST['dob'] ?? ''));
    $gender     = str_replace('|', '', trim($_POST['gender'] ?? ''));
    $email      = str_replace('|', '', trim($_POST['email'] ?? ''));
    $hometown   = str_replace('|', '', trim($_POST['hometown'] ?? ''));
    
    // Retrieve new password fields (users may leave these blank to retain their current password)
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    // ==========================================================================
    // Form Validation 
    // ==========================================================================

    // 1. Mandatory Fields Check: Ensure no core fields are left blank
    if ($first_name === '' || $last_name === '' || $dob === '' || $gender === '' || $email === '' || $hometown === '') {
        $errors[] = 'All fields are required.';
    }
    
    // 2. Email Format Check: Validate against standard email structures
    if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Please enter a valid email address.';
    }

    // 3. Password Check: Enforce complexity and consistency validation only if a new password is provided
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
        if ($password !== $confirm_password) {
            $errors[] = 'Passwords do not match.';
        }
    }

    // ==========================================================================
    // Data Storage & Redirect
    // ==========================================================================
    if (empty($errors) && file_exists($data_file)) {

        $lines = file($data_file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $updated_lines = [];
        $found = false;

        foreach ($lines as $line) {
            // Find the line matching the original email, rebuild it with the
            // new values, but keep the existing password untouched unless a new one is provided.
            if (strpos($line, 'Email:' . $original_email . '|') !== false || strpos($line, 'Email:' . $original_email) === strlen($line) - strlen('Email:' . $original_email)) {

                $fields = explode('|', $line);
                $existing = [];
                foreach ($fields as $field) {
                    $pair = explode(':', $field, 2);
                    if (count($pair) === 2) {
                        $existing[$pair[0]] = $pair[1];
                    }
                }

                // Core Logic: Generate a new hash if a new password is provided; otherwise, retain the existing password hash
                $final_password = $existing['Password'] ?? '';
                if ($password !== '') {
                    $final_password = password_hash($password, PASSWORD_DEFAULT);
                }

                // Preserve the security question and answer fields to prevent them from being overwritten during the profile update
                $security_str = '';
                if (isset($existing['SecurityQuestion']) && isset($existing['SecurityAnswer'])) {
                    $security_str = '|SecurityQuestion:' . $existing['SecurityQuestion'] . '|SecurityAnswer:' . $existing['SecurityAnswer'];
                }

                // Reconstruct the delimited data record
                $new_line = 'First Name:' . $first_name
                          . '|LastName:' . $last_name
                          . '|DOB:' . $dob
                          . '|Gender:' . $gender
                          . '|Email:' . $email
                          . '|Hometown:' . $hometown
                          . '|Password:' . $final_password
                          . $security_str;

                $updated_lines[] = $new_line;
                $found = true;

                // Keep the session in sync if the email changed
                $_SESSION['email'] = $email;
                $_SESSION['first_name'] = $first_name;

            } else {
                $updated_lines[] = $line;
            }
        }

        // Perform write operation
        if ($found) {
            file_put_contents($data_file, implode(PHP_EOL, $updated_lines) . PHP_EOL, LOCK_EX);
            header('Location: main_menu.php?profile_updated=1');
            exit;
        } else {
            $errors[] = 'Could not find your original record to update.';
        }
    }
}
?>

<?php 
// ==========================================================================
// Error Display Template
// ==========================================================================
// If the script reaches this point and the $errors array is not empty, render the error UI
if (!empty($errors)): 
?>
<?php 
$page_title = 'Update Failed'; 
include 'header.php'; 
?>
<main class="auth-page">
    <img src="img/auth/register.jpg" alt="" class="auth-page-bg">
    <div class="auth-page-overlay"></div>
    <div class="auth-card auth-card-single">
        <h1>Update Failed</h1>
        <div class="form-errors">
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?php echo htmlspecialchars($error); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <a href="update_profile.php" class="card-btn">Back</a>
    </div>
</main>
<?php include 'footer.php'; ?>
<?php endif; ?>