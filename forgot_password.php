<?php 
session_start();
$page_title = 'Forgot Password'; 
include 'header.php'; 

$errors = [];
$step = $_SESSION['fp_step'] ?? 1;
$fp_email = $_SESSION['fp_email'] ?? '';
$fp_question = $_SESSION['fp_question'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $data_file = 'data/User/user.txt';

    // ---- Step 1: Verify Email and Fetch Security Question ----
    if (isset($_POST['step1_submit'])) {

        $email = trim($_POST['email'] ?? '');
        $found_user = null;

        if (file_exists($data_file)) {
            $lines = file($data_file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            foreach ($lines as $line) {
                $fields = explode('|', $line);
                $u = [];
                foreach ($fields as $f) {
                    $pair = explode(':', $f, 2);
                    if (count($pair) === 2) $u[$pair[0]] = $pair[1];
                }
                if (isset($u['Email']) && $u['Email'] === $email) {
                    $found_user = $u;
                    break;
                }
            }
        }

        if (!$found_user) {
            $errors[] = 'No account found with that email.';
        } elseif (!isset($found_user['SecurityQuestion']) || !isset($found_user['SecurityAnswer'])) {
            $errors[] = 'This account does not have a security question set.';
        } else {
            // Store data in session and move to Step 2
            $_SESSION['fp_step'] = 2;
            $_SESSION['fp_email'] = $email;
            $_SESSION['fp_question'] = $found_user['SecurityQuestion'];
            $_SESSION['fp_answer_hash'] = $found_user['SecurityAnswer'];
            
            $step = 2;
            $fp_email = $email;
            $fp_question = $found_user['SecurityQuestion'];
        }

    // ---- Step 2: Verify Security Answer ----
    } elseif (isset($_POST['step2_submit'])) {

        $answer = trim($_POST['security_answer'] ?? '');
        $stored_hash = $_SESSION['fp_answer_hash'] ?? '';

        if (!password_verify(strtolower($answer), $stored_hash)) {
            $errors[] = 'Incorrect answer to the security question.';
        } else {
            // Answer is correct, move to Step 3
            $_SESSION['fp_step'] = 3;
            $step = 3;
        }

    // ---- Step 3: Set New Password ----
    } elseif (isset($_POST['step3_submit'])) {

        $new_password = $_POST['new_password'] ?? '';
        $confirm_password = $_POST['confirm_password'] ?? '';

        if (strlen($new_password) < 8 || !preg_match('/[0-9]/', $new_password) || !preg_match('/[^A-Za-z0-9]/', $new_password)) {
            $errors[] = 'Password must be at least 8 characters with a number and a symbol.';
        } elseif ($new_password !== $confirm_password) {
            $errors[] = 'Passwords do not match.';
        } else {
            $lines = file($data_file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            $updated = [];
            foreach ($lines as $line) {
                $fields = explode('|', $line);
                $u = [];
                foreach ($fields as $f) {
                    $pair = explode(':', $f, 2);
                    if (count($pair) === 2) $u[$pair[0]] = $pair[1];
                }
                if (isset($u['Email']) && $u['Email'] === $fp_email) {
                    $u['Password'] = password_hash($new_password, PASSWORD_DEFAULT);
                    $rebuilt = [];
                    foreach ($u as $k => $v) $rebuilt[] = $k . ':' . $v;
                    $updated[] = implode('|', $rebuilt);
                } else {
                    $updated[] = $line;
                }
            }
            file_put_contents($data_file, implode(PHP_EOL, $updated) . PHP_EOL, LOCK_EX);

            // Clear session data after successful reset
            unset($_SESSION['fp_step'], $_SESSION['fp_email'], $_SESSION['fp_question'], $_SESSION['fp_answer_hash']);
            header('Location: login.php?password_reset=1');
            exit;
        }
    }
}
?>

<main class="auth-page">
    <img src="img/auth/register.jpg" alt="" class="auth-page-bg">
    <div class="auth-page-overlay"></div>

    <div class="auth-card auth-card-single">
        <h1>Forgot Password</h1>

        <?php if (!empty($errors)): ?>
        <div class="form-errors">
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?php echo htmlspecialchars($error); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php endif; ?>

        <?php if ($step === 1): ?>
        <!-- Step 1 Form: Email Only -->
        <form method="POST" class="auth-form">
            <p class="auth-subtitle">Enter your email to find your account.</p>
            
            <label for="email">Email</label>
            <input type="email" name="email" id="email" required>

            <div class="auth-actions">
                <button type="submit" name="step1_submit" class="card-btn">Next</button>
            </div>
        </form>

        <?php elseif ($step === 2): ?>
        <!-- Step 2 Form: Display Question and get Answer -->
        <form method="POST" class="auth-form">
            <p class="auth-subtitle">Answer your security question to verify your identity.</p>

            <label>Email</label>
            <input type="email" value="<?php echo htmlspecialchars($fp_email); ?>" disabled style="opacity: 0.6; cursor: not-allowed;">

            <label>Security Question</label>
            <div style="background: #0a0e14; border: 1px solid #202832; border-radius: 4px; padding: 12px; color: #7fa8d9; margin-bottom: 14px; margin-top: 4px; font-family: 'Inter', sans-serif; font-size: 0.95rem;">
                <?php echo htmlspecialchars($fp_question); ?>
            </div>

            <label for="security_answer">Security Question Answer</label>
            <input type="text" name="security_answer" id="security_answer" required autocomplete="off">

            <div class="auth-actions">
                <button type="submit" name="step2_submit" class="card-btn">Verify Answer</button>
            </div>
        </form>

        <?php else: ?>
        <!-- Step 3 Form: New Password Setup -->
        <form method="POST" class="auth-form">
            <p class="auth-subtitle">Create a new secure password.</p>
            
            <label for="new_password">New Password</label>
            <div class="password-wrapper">
                <input type="password" name="new_password" id="new_password" autocomplete="new-password" required>
            </div>

            <label for="confirm_password">Confirm New Password</label>
            <div class="password-wrapper">
                <input type="password" name="confirm_password" id="confirm_password" autocomplete="new-password" required>
            </div>

            <div class="auth-actions">
                <button type="submit" name="step3_submit" class="card-btn">Reset Password</button>
            </div>
        </form>
        <?php endif; ?>

        <p class="auth-switch"><a href="login.php">Back to Log In</a></p>
    </div>
</main>

<?php include 'footer.php'; ?>