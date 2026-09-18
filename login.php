<?php 
session_start();

if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    header('Location: main_menu.php');
    exit;
}

$page_title = 'Log In'; 
include 'header.php'; 

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $data_file = 'data/User/user.txt';

    if ($email === '' || $password === '') {
        $errors[] = 'Please enter both email and password.';
    } else {
        $found_user = null;

        // Read all stored users and look for a matching email
        if (file_exists($data_file)) {
            $lines = file($data_file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

            foreach ($lines as $line) {
                $fields = explode('|', $line);
                $user = [];
                foreach ($fields as $field) {
                    $pair = explode(':', $field, 2);
                    if (count($pair) === 2) {
                        $user[$pair[0]] = $pair[1];
                    }
                }

                if (isset($user['Email']) && $user['Email'] === $email) {
                    $found_user = $user;
                    break;
                }
            }
        }

        if (!$found_user) {
            $errors[] = 'Incorrect email or password.';
        } elseif (!password_verify($password, $found_user['Password'])) {
            $errors[] = 'Incorrect email or password.';
        } else {
            // Login successful — store session data
            $_SESSION['logged_in'] = true;
            $_SESSION['email'] = $found_user['Email'];
            $_SESSION['first_name'] = $found_user['First Name'];

            // Figure out where to send the user after login.
            // If a "redirect" param was passed (e.g. they were sent here
            // from activity_reg.php or order.php), send them back there.
            // Otherwise default to the main menu.
            $redirect = isset($_GET['redirect']) ? urldecode($_GET['redirect']) : 'main_menu.php';

            // Security check: only allow redirecting to a relative path
            // on this site, never to an external URL (open redirect protection)
            if (strpos($redirect, '://') !== false || strpos($redirect, '//') === 0) {
                $redirect = 'main_menu.php';
            }

            // Append login_success=1 to whatever the target URL is, so the
            // destination page can show the "Logged in successfully" toast.
            // If the target already has a "?" (query string), use "&" to add
            // another parameter; otherwise start a new one with "?".
            $separator = (strpos($redirect, '?') !== false) ? '&' : '?';
            $redirect .= $separator . 'login_success=1';

            header('Location: ' . $redirect);
            exit;
        }
    }
}
?>

<main class="auth-page">
    <img src="img/auth/register.jpg" alt="" class="auth-page-bg">
    <div class="auth-page-overlay"></div>

    <div class="auth-card">

        <div class="auth-visual">
            <img src="img/auth/register.jpg" alt="" class="auth-bg-img">
        </div>

        <div class="auth-form-side">
            <h1>Log In</h1>
            <p class="auth-subtitle">Welcome back to Gundam Hub.</p>

            <?php if (!empty($errors)): ?>
            <div class="form-errors">
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo htmlspecialchars($error); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php endif; ?>

            <?php if (isset($_GET['registered'])): ?>
            <div class="form-success">
                <p>Registration successful! Please log in.</p>
            </div>
            <?php endif; ?>

            <!-- Keep the redirect target attached to the form so it survives submission -->
            <form action="login.php<?php echo isset($_GET['redirect']) ? '?redirect=' . urlencode($_GET['redirect']) : ''; ?>" method="POST" class="auth-form">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">

                <label for="password">Password</label>
                <div class="password-wrapper">
                    <input type="password" name="password" id="password" autocomplete="current-password">
                    <button type="button" class="toggle-password" data-target="password">👁</button>
                </div>

                <div class="auth-actions">
                    <button type="submit" class="card-btn">Log In</button>
                </div>

                <p class="auth-switch">Don't have an account? <a href="registration.php">Register</a></p>
            </form>
        </div>

    </div>
</main>

<?php include 'footer.php'; ?>