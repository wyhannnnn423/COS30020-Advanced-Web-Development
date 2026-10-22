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
        <img src="img/avatars/your-photo.jpg" alt="Yan Han Wong" class="profile-photo">

        <h1>Yan Han Wong</h1>

        <ul class="profile-facts">
            <li><strong>Student ID</strong><span>104392138</span></li>
            <li><strong>Email</strong><span>104392138@students.swinburne.edu.my</span></li>
        </ul>

       <div class="integrity-declaration">
            <div class="integrity-declaration">
            <h3>Academic Integrity Declaration</h3>
            
            <p>I/we have not impersonated, or allowed myself/ourselves to be impersonated by any person for the purposes of this assessment. This assessment is my/our original work and no part of it has been copied from any other source except where due acknowledgement is made. No part of this assessment has been written for me/us by any other person except where such collaboration has been authorised by the lecturer/teacher concerned. I/we have not previously submitted this work for this or any other course/unit. I/we give permission for my/our assessment response to be reproduced, communicated, compared and archived for plagiarism detection, benchmarking or educational purposes.</p>
            
            <p>I/we understand that: Plagiarism is the presentation of another person's work as though it is your own. It is a form of cheating and is a very serious academic offence that may lead to exclusion from the university. Plarjarised material may be drawn from published and unpublished written documents, interpretations, computer software, designs, music, sounds, images, photographs, and ideas or ideological frameworks gained through working with another person or in a group. Plagiarised material can be drawn from, and presented in, written, graphic and visual form, including electronic data and oral presentations. Plagiarism occurs when the origin of the material used is not appropriately cited.</p>
            
            <p>I/we agree and acknowledge that: I/we have read and understood the Declaration and Statement of Authorship above. I/we accept that use of my/our Swinburne account to electronically submit this assessment constitutes my/our agreement to the Declaration and Statement of Authorship. If I/we do not agree to the Declaration and Statement of Authorship in this context, the assessment outcome may not be valid for assessment purposes and may not be included in my/our aggregate score for this unit. Penalties for plagiarism range from a formal caution to expulsion from the university, and are detailed in the Plagiarism and Misconduct webpage.</p>
        </div>


       <div class="profile-links">
        <a href="index.php" class="card-btn">Home</a>
        <a href="update_profile.php" class="card-btn">Edit Profile</a>
        <a href="about.php" class="card-btn secondary-btn">About This Project</a>
        </div>
    </div>

</main>

<?php include 'footer.php'; ?>