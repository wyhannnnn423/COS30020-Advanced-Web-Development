<?php
// Initialize the error array and define file paths for user data storage[cite: 1]
$errors = [];
$data_dir = 'data/User';
$data_file = $data_dir . '/user.txt';

// Proceed only if the request method is POST[cite: 1]
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Retrieve and sanitize inputs by trimming whitespace and removing the '|' delimiter to prevent file corruption[cite: 1]
    $first_name = str_replace('|', '', trim($_POST['first_name'] ?? ''));
    $last_name = str_replace('|', '', trim($_POST['last_name'] ?? ''));
    $dob = str_replace('|', '', trim($_POST['dob'] ?? ''));
    $gender = str_replace('|', '', trim($_POST['gender'] ?? ''));
    $email = str_replace('|', '', trim($_POST['email'] ?? ''));
    $hometown = str_replace('|', '', trim($_POST['hometown'] ?? ''));
    
    // Retrieve and sanitize security question and answer
    $security_question = str_replace('|', '', trim($_POST['security_question'] ?? ''));
    $security_answer = str_replace('|', '', trim($_POST['security_answer'] ?? ''));
    
    // Passwords do not need the delimiter stripped before hashing, but retrieve them safely[cite: 1]
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    // ==========================================================================
    // Form Validation 
    // ==========================================================================

    // 1. Mandatory Fields Check: Ensure no core fields are left blank[cite: 1]
    if ($first_name === '' || $last_name === '' || $dob === '' || $gender === '' || $email === '' || $hometown === '' || $password === '' || $confirm_password === '') {
        $errors[] = 'All fields are required.';
    }

    // 2. Security Question Check: Ensure a security question and answer are provided
    if ($security_question === '' || $security_answer === '') {
        $errors[] = 'Please select a security question and provide an answer.';
    }

    // 3. Name Format Check: Ensure names contain only alphabetical characters and spaces[cite: 1]
    if ($first_name !== '' && !preg_match('/^[A-Za-z ]+$/', $first_name)) {
        $errors[] = 'First name may only contain letters and spaces.';
    }
    if ($last_name !== '' && !preg_match('/^[A-Za-z ]+$/', $last_name)) {
        $errors[] = 'Last name may only contain letters and spaces.';
    }

    // 4. Email Format Check: Validate against standard email structures[cite: 1]
    if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Please enter a valid email address.';
    }

    // 5. Password Complexity Check: Require minimum length, at least one number, and one symbol[cite: 1]
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

    // 6. Password Confirmation Check: Ensure both password fields match exactly[cite: 1]
    if ($password !== '' && $confirm_password !== '' && $password !== $confirm_password) {
        $errors[] = 'Passwords do not match.';
    }

    // 7. Email Uniqueness Check: Prevent duplicate registrations with the same email[cite: 1]
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

    // Proceed to save the record only if validation passed with zero errors[cite: 1]
    if (empty($errors)) {
        
        // Create the storage directory with appropriate permissions if it does not exist[cite: 1]
        if (!file_exists($data_dir)) {
            mkdir($data_dir, 0777, true);
        }

        // Securely hash the password before writing it to the file[cite: 1]
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        // Hash the security answer for secure storage (converted to lowercase for case-insensitive verification later)
        $hashed_answer = password_hash(strtolower(trim($security_answer)), PASSWORD_DEFAULT);

        // Construct the delimited record string for the text database, appending the security question and hashed answer[cite: 1]
        $record = 'First Name:' . $first_name
                . '|LastName:' . $last_name
                . '|DOB:' . $dob
                . '|Gender:' . $gender
                . '|Email:' . $email
                . '|Hometown:' . $hometown
                . '|Password:' . $hashed_password
                . '|SecurityQuestion:' . $security_question
                . '|SecurityAnswer:' . $hashed_answer;

        // Perform an atomic write operation with an exclusive lock (LOCK_EX) to prevent race conditions[cite: 1]
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
// If the script reaches this point and the $errors array is not empty, render the error UI[cite: 1]
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