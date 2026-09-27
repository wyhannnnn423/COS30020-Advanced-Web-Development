<?php 
session_start();
$page_title = 'Forgot Password'; 
include 'header.php'; 

$errors = [];
$step = $_SESSION['fp_step'] ?? 1;
$fp_email = $_SESSION['fp_email'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $data_file = 'data/User/user.txt';

    // ---- Step 1: verify email + security question answer ----
    if (isset($_POST['step1_submit'])) {

        $email = trim($_POST['email'] ?? '');
        $answer = trim($_POST['security_answer'] ?? '');

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
        } elseif (!isset($found_user['SecurityAnswer']) || !password_verify(strtolower($answer), $found_user['SecurityAnswer'])) {
            $errors[] = 'Incorrect answer to the security question.';
        } else {
            $_SESSION['fp_step'] = 2;
            $_SESSION['fp_email'] = $email;
            $step = 2;
            $fp_email = $email;
        }

    // ---- Step 2: set new password ----
    } elseif (isset($_POST['step2_submit'])) {

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

            unset($_SESSION['fp_step'], $_SESSION['fp_email']);
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
        <form method="POST" class="auth-form">
            <label for="email">Email</label>
            <input type="email" name="email" id="email" required>

            <label for="security_answer">Security Question Answer</label>
            <input type="text" name="security_answer" id="security_answer" required>

            <div class="auth-actions">
                <button type="submit" name="step1_submit" class="card-btn">Verify</button>
            </div>
        </form>

        <?php else: ?>
        <form method="POST" class="auth-form">
            <label for="new_password">New Password</label>
            <div class="password-wrapper">
                <input type="password" name="new_password" id="new_password" autocomplete="new-password">
                <button type="button" class="toggle-password" data-target="new_password">👁</button>
            </div>

            <label for="confirm_password">Confirm New Password</label>
            <div class="password-wrapper">
                <input type="password" name="confirm_password" id="confirm_password" autocomplete="new-password">
                <button type="button" class="toggle-password" data-target="confirm_password">👁</button>
            </div>

            <div class="auth-actions">
                <button type="submit" name="step2_submit" class="card-btn">Reset Password</button>
            </div>
        </form>
        <?php endif; ?>

        <p class="auth-switch"><a href="login.php">Back to Log In</a></p>
    </div>
</main>

<?php include 'footer.php'; ?>