<?php 
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

            <form action="process_registration.php" method="POST" class="auth-form" id="registerForm" novalidate="novalidate">
                <form action="process_registration.php" method="POST" class="auth-form" id="registerForm" novalidate="novalidate">
            
            <label for="first_name">First Name</label>
            <input type="text" name="first_name" id="first_name">
            <span class="error-message" id="first_name_error"></span>

            <label for="last_name">Last Name</label>
            <input type="text" name="last_name" id="last_name">
            <span class="error-message" id="last_name_error"></span>

            <label for="dob">Date of Birth</label>
            <input type="date" name="dob" id="dob">
            <span class="error-message" id="dob_error"></span>

            <label for="gender">Gender</label>
            <select name="gender" id="gender">
                <option value="">-- Select Gender --</option>
                <option value="Female">Female</option>
                <option value="Male">Male</option>
            </select>
            <span class="error-message" id="gender_error"></span>

            <label for="email">Email</label>
            <input type="email" name="email" id="email">
            <span class="error-message" id="email_error"></span>

            <label for="hometown">Hometown</label>
            <input type="text" name="hometown" id="hometown">
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
</main>
<?php include 'footer.php'; ?>