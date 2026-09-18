<?php
// Initialize the error array and define file paths for user data storage
$errors = [];
$data_dir = 'data/User';
$data_file = $data_dir . '/user.txt';

// Proceed only if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Retrieve and sanitize inputs by trimming whitespace and removing the '|' delimiter to prevent file corruption
    $first_name = str_replace('|', '', trim($_POST['first_name'] ?? ''));
    $last_name = str_replace('|', '', trim($_POST['last_name'] ?? ''));
    $dob = str_replace('|', '', trim($_POST['dob'] ?? ''));
    $gender = str_replace('|', '', trim($_POST['gender'] ?? ''));
    $email = str_replace('|', '', trim($_POST['email'] ?? ''));
    $hometown = str_replace('|', '', trim($_POST['hometown'] ?? ''));
    
    // Passwords do not need the delimiter stripped before hashing, but retrieve them safely
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    // ==========================================================================
    // Form Validation 
    // ==========================================================================

    // 1. Mandatory Fields Check: Ensure no fields are left blank
    if ($first_name === '' || $last_name === '' || $dob === '' || $gender === '' || $email === '' || $hometown === '' || $password === '' || $confirm_password === '') {
        $errors[] = 'All fields are required.';
    }

    // 2. Name Format Check: Ensure names contain only alphabetical characters and spaces
    if ($first_name !== '' && !preg_match('/^[A-Za-z ]+$/', $first_name)) {
        $errors[] = 'First name may only contain letters and spaces.';
    }
    if ($last_name !== '' && !preg_match('/^[A-Za-z ]+$/', $last_name)) {
        $errors[] = 'Last name may only contain letters and spaces.';
    }

    // 3. Email Format Check: Validate against standard email structures 
    if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Please enter a valid email address.';
    }

    // 4. Password Complexity Check: Require minimum length, at least one number, and one symbol 
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

    // 5. Password Confirmation Check: Ensure both password fields match exactly 
    if ($password !== '' && $confirm_password !== '' && $password !== $confirm_password) {
        $errors[] = 'Passwords do not match.';
    }

    // 6. Email Uniqueness Check: Prevent duplicate registrations with the same email 
    if ($email !== '' && file_exists($data_file)) {
        $existing_lines = file($data_file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($existing_lines as $line) {
            if (strpos($line, 'Email:' . $email . '|') !== false) {
                $errors[] = 'An account with this email already exists.';
                break;
            }
        }
    }

    // ==========================================================================
    // Data Storage & Redirect
    // ==========================================================================

    // Proceed to save the record only if validation passed with zero errors 
    if (empty($errors)) {
        
        // Create the storage directory with appropriate permissions if it does not exist 
        if (!file_exists($data_dir)) {
            mkdir($data_dir, 0777, true);
        }

        // Securely hash the password before writing it to the file 
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Construct the delimited record string for the text database 
        $record = 'First Name:' . $first_name
                . '|LastName:' . $last_name
                . '|DOB:' . $dob
                . '|Gender:' . $gender
                . '|Email:' . $email
                . '|Hometown:' . $hometown
                . '|Password:' . $hashed_password;

        // Perform an atomic write operation with an exclusive lock (LOCK_EX) to prevent race conditions 
        if (file_put_contents($data_file, $record . PHP_EOL, FILE_APPEND | LOCK_EX) !== false) {
            header('Location: login.php?registered=1');
            exit;
        } else {
            $errors[] = 'Could not save your registration. Please try again.';
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
$page_title = 'Registration Error'; 
include 'header.php'; 
?>

<main class="auth-page">
    <img src="img/auth/register.jpg" alt="" class="auth-page-bg">
    <div class="auth-page-overlay"></div>

    <div class="auth-card auth-card-single">
        <h1>Registration Failed</h1>
        <p class="auth-subtitle">Please review the issues below and try again.</p>

        <div class="form-errors">
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?php echo htmlspecialchars($error); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>

        <a href="registration.php" class="card-btn">Back to Registration</a>
    </div>
</main>

<?php include 'footer.php'; ?>
<?php endif; ?>