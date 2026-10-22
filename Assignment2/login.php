<?php
require_once 'includes/auth.php';
require_once 'includes/db.php';

start_session_once();

// An admin who lands here from a user-only page (main_menu.php, activities.php ...) is not
// bounced back to the admin menu silently: tell them why, and let them log out to continue as a user.
$admin_blocked = is_logged_in() && $_SESSION['type'] === 'admin' && isset($_GET['redirect']);

// Already logged in: go straight to the right menu.
if (is_logged_in() && !$admin_blocked) {
    header('Location: ' . ($_SESSION['type'] === 'admin' ? 'main_menu_admin.php' : 'main_menu.php'));
    exit;
}

$errors = [];
$email  = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($email === '' || $password === '') {
        $errors[] = 'Please enter both email and password.';
    } else {
        $db   = get_db();
        $stmt = $db->prepare(
            'SELECT a.password, a.type, u.first_name
             FROM account_table a
             JOIN user_table u ON u.email = a.email
             WHERE a.email = ?'
        );
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $account = $stmt->get_result()->fetch_assoc();

        // Same message for "no such email" and "wrong password" so the form
        // does not reveal which emails are registered.
        if (!$account || !password_verify($password, $account['password'])) {
            $errors[] = 'Incorrect email or password.';
        } else {
            login_session($email, $account['type'], $account['first_name']);

            if ($account['type'] === 'admin') {
                header('Location: main_menu_admin.php');
            } else {
                $target = safe_redirect_target($_GET['redirect'] ?? '', 'main_menu.php');
                header('Location: ' . add_flag($target, 'login_success'));
            }
            exit;
        }
    }
}

$page_title = 'Log In';
include 'header.php';
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

            <?php if ($admin_blocked): ?>
            <div class="form-errors">
                <p>You are logged in as an admin, and that page is for normal users.
                   <a href="logout.php">Log out</a> and log in with a user account to see it, or go back to the
                   <a href="main_menu_admin.php">admin menu</a>.</p>
            </div>
            <?php endif; ?>

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

            <?php if (!$admin_blocked): ?>
            <!-- Keep the redirect target attached to the form so it survives submission -->
            <form action="login.php<?php echo isset($_GET['redirect']) ? '?redirect=' . urlencode($_GET['redirect']) : ''; ?>" method="POST" class="auth-form">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($email); ?>">

                <label for="password">Password</label>
                <div class="password-wrapper">
                    <input type="password" name="password" id="password" autocomplete="current-password">
                    <button type="button" class="toggle-password" data-target="password">👁</button>
                </div>

                <div class="auth-actions">
                    <button type="submit" class="card-btn">Log In</button>
                </div>

                <p class="auth-switch">Don't have an account? <a href="registration.php">Register</a></p>
                <p class="auth-switch"><a href="forgot_password.php">Forgot your password?</a></p>
            </form>
            <?php endif; ?>
        </div>

    </div>
</main>

<?php include 'footer.php'; ?>
