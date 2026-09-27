<?php 
session_start();

// Task 7 displays the logged-in user's own info, so this page requires login
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: login.php?redirect=' . urlencode($_SERVER['REQUEST_URI']));
    exit;
}

$page_title = 'Profile'; 
include 'header.php'; 
?>

<main class="page profile-page">
    <div class="profile-card">
        <img src="img/profile_images/your-photo.jpg" alt="Yan Han Wong" class="profile-photo">

        <h1>Yan Han Wong</h1>

        <ul class="profile-facts">
            <li><strong>Student ID</strong><span>104392138</span></li>
            <li><strong>Email</strong><span>104392138@students.swinburne.edu.my</span></li>
        </ul>

        <div class="integrity-declaration">
            <h3>Academic Integrity Declaration</h3>
            <p>
                Academic integrity is about presenting academic work in a moral, ethical and honest way. It means using ideas, knowledge and information to develop your own insights, not presenting someone else's work as your own. It also means acknowledging the work of others when you include it in your work.
            </p>
        </div>

       <div class="profile-links">
        <a href="index.php" class="card-btn">Home</a>
        <a href="update_profile.php" class="card-btn">Edit Profile</a>
        <a href="about.php" class="card-btn secondary-btn">About This Project</a>
        </div>
    </div>

</main>

<?php include 'footer.php'; ?>