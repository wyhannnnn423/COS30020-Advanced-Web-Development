<?php
require_once 'includes/auth.php';
start_session_once();

// Errors and previously typed values come back from process_registration.php
$errors = $_SESSION['reg_errors'] ?? [];
$old    = $_SESSION['reg_old'] ?? [];
unset($_SESSION['reg_errors'], $_SESSION['reg_old']);

$page_title = 'Register';
include 'header.php';
?>
<main class="auth-page">
    <img src="img/auth/register.jpg" alt="register" class="auth-page-bg">
    <div class="auth-page-overlay"></div>

    <div class="auth-card">
        <div class="auth-form-side">
            <h1>Create Your Account</h1>
            <p class="auth-subtitle">Join Gundam Hub to book workshops and screenings.</p>

            <?php if (!empty($errors)): ?>
            <div class="form-errors">
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo htmlspecialchars($error); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php endif; ?>

            <form action="process_registration.php" method="POST" enctype="multipart/form-data" class="auth-form" id="registerForm" novalidate="novalidate">

                <label for="first_name">First Name</label>
                <input type="text" name="first_name" id="first_name" maxlength="50" value="<?php echo htmlspecialchars($old['first_name'] ?? ''); ?>">
                <span class="error-message" id="first_name_error"></span>

                <label for="last_name">Last Name</label>
                <input type="text" name="last_name" id="last_name" maxlength="50" value="<?php echo htmlspecialchars($old['last_name'] ?? ''); ?>">
                <span class="error-message" id="last_name_error"></span>

                <label for="dob">Date of Birth</label>
                <input type="date" name="dob" id="dob" value="<?php echo htmlspecialchars($old['dob'] ?? ''); ?>">
                <span class="error-message" id="dob_error"></span>

                <label for="gender">Gender</label>
                <select name="gender" id="gender">
                    <option value="">-- Select Gender --</option>
                    <option value="Female" <?php echo (($old['gender'] ?? '') === 'Female') ? 'selected' : ''; ?>>Female</option>
                    <option value="Male" <?php echo (($old['gender'] ?? '') === 'Male') ? 'selected' : ''; ?>>Male</option>
                </select>
                <span class="error-message" id="gender_error"></span>

                <label for="email">Email</label>
                <input type="email" name="email" id="email" maxlength="50" value="<?php echo htmlspecialchars($old['email'] ?? ''); ?>">
                <span class="error-message" id="email_error"></span>

                <label for="hometown">Hometown</label>
                <input type="text" name="hometown" id="hometown" maxlength="50" value="<?php echo htmlspecialchars($old['hometown'] ?? ''); ?>">
                <span class="error-message" id="hometown_error"></span>

                <label for="password">Password</label>
                <div class="password-wrapper">
                    <input type="password" name="password" id="password" autocomplete="new-password">
                    <button type="button" class="toggle-password" data-target="password">👁</button>
                </div>
                <span class="error-message" id="password_error"></span>

                <label for="confirm_password">Confirm Password</label>
                <div class="password-wrapper">
                    <input type="password" name="confirm_password" id="confirm_password" autocomplete="new-password">
                    <button type="button" class="toggle-password" data-target="confirm_password">👁</button>
                </div>
                <span class="error-message" id="confirm_password_error"></span>

                <label for="profile_photo">Profile Photo <span class="form-hint">(optional, JPG or PNG, max 5MB)</span></label>
                <input type="file" name="profile_photo" id="profile_photo" accept=".jpg,.jpeg,.png,image/jpeg,image/png">
                <img id="photo_preview" alt="" style="display:none;width:96px;height:96px;object-fit:cover;border-radius:50%;margin:0.5rem 0;">

                <label for="security_question">Security Question</label>
                <select name="security_question" id="security_question">
                    <option value="">-- Select a Question --</option>
                    <?php
                    $questions = [
                        'first_pet'        => 'What was the name of your first pet?',
                        'live_street'      => 'What street do you live in?',
                        'favorite_teacher' => "What was your favorite teacher's name?",
                    ];
                    foreach ($questions as $value => $label): ?>
                        <option value="<?php echo $value; ?>" <?php echo (($old['security_question'] ?? '') === $value) ? 'selected' : ''; ?>><?php echo htmlspecialchars($label); ?></option>
                    <?php endforeach; ?>
                </select>

                <label for="security_answer">Your Answer</label>
                <input type="text" name="security_answer" id="security_answer" maxlength="100" value="<?php echo htmlspecialchars($old['security_answer'] ?? ''); ?>">

                <div class="auth-actions">
                    <button type="submit" class="card-btn">Register</button>
                    <button type="reset" class="card-btn secondary-btn">Reset</button>
                </div>

                <p class="auth-switch">Already have an account? <a href="login.php">Log In</a></p>
            </form>
        </div>

        <div class="auth-visual">
            <img src="img/auth/register.jpg" alt="register" class="auth-bg-img">
        </div>
    </div>
</main>
<script>
// Shows the chosen photo before submitting (the server still checks the file).
document.getElementById('profile_photo').addEventListener('change', function () {
    var preview = document.getElementById('photo_preview');
    if (this.files && this.files[0] && this.files[0].type.indexOf('image/') === 0) {
        preview.src = URL.createObjectURL(this.files[0]);
        preview.style.display = 'block';
    } else {
        preview.style.display = 'none';
    }
});
</script>
<?php include 'footer.php'; ?>
