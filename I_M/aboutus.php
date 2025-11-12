<?php
session_start();
include "db/dbconnect.php";

 
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];

 
$userQuery = $conn->prepare("SELECT email, role FROM users WHERE username=? LIMIT 1");
$userQuery->bind_param("s", $username);
$userQuery->execute();
$userResult = $userQuery->get_result();
$userData = $userResult->fetch_assoc();

$email = $userData['email'] ?? 'No email';
$role  = $userData['role'] ?? 'Customer';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - New Dawn Thrift</title>
    <link rel="stylesheet" href="aboutus.css?v=4">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700&display=swap" rel="stylesheet">
</head>
<body>

<!-- HEADER -->
<header>
    <div class="logo-left">
        <img src="logo.png" alt="New Dawn Thrift Logo">
    </div>

    <nav class="nav-center">
        <a href="home.php">Home</a>
        <a href="product1.php">Products</a>
        <a href="aboutus.php" class="active">About Us</a>
        <a href="contactus.php">Contact us</a>
    </nav>

    <div class="right-icons">
        <a href="cart.php" class="cart-btn"><i class="fas fa-shopping-cart"></i></a>

        <div class="profile-wrapper">
            <button class="profile-btn">👤</button>
            <div class="profile-dropdown">
                <div class="profile-card-header">
                    <div class="avatar-emoji">👤</div>
                    <div class="username"><?= htmlspecialchars($username) ?></div>
                </div>
                <div class="profile-card-body">
                    <p>Email: <?= htmlspecialchars($email) ?></p>
                    <p>Role: <?= htmlspecialchars($role) ?></p>
                    <a href="login.php" class="logout-btn">Logout</a>
                </div>
            </div>
        </div>
    </div>
</header>

<!-- ABOUT SECTION -->
<section class="about-section">
    <div class="about-text">
        <h2>About New Dawn Thrift</h2>
        <p>
            At New Dawn Thrift, we believe in the beauty of second chances. Every piece of clothing tells a story, 
            and we’re passionate about helping those stories continue—one outfit at a time. Our shop isn’t just 
            a store; it’s a movement dedicated to making sustainable fashion accessible to all. Whether you're a 
            bargain hunter, a style lover, or a sustainability advocate, we've got something special waiting for you.
        </p>
        <p>
            Founded on the belief that fashion shouldn’t harm the planet or your wallet, New Dawn Thrift was created 
            to offer a responsible and stylish alternative to fast fashion. We handpick each item, ensuring quality, 
            uniqueness, and affordability. Every purchase you make not only helps reduce waste but also supports 
            a greener future.
        </p>
        <p>
            From vintage gems and retro fits to modern styles and timeless basics, our collection is always growing 
            and constantly refreshing—just like a new dawn. Whether you're searching for the perfect pair of jeans, 
            a cozy sweater, or something totally unexpected, we make sure your thrift experience is inspiring and fun.
        </p>
        <p class="team-signature">— The New Dawn Team 🌅</p>
    </div>

    <div class="about-image">
        <img src="aboutusimage.png" alt="Our Store">
    </div>
</section>

<!-- FOOTER -->
<footer>
    <hr>
    <div class="footer-container">
        <div class="footer-left">
            <p>&copy; 2024 New Dawn Thrift</p>
            <p>All rights reserved.</p>
        </div>
        <div class="footer-center">
            <p class="brand">New Dawn Thrift</p>
        </div>
        <div class="footer-right">
            <div class="socials">
                <img src="facebook.png" alt="Facebook">
                <img src="instagram.png" alt="Instagram">
                <img src="tiktok.png" alt="Tiktok">
            </div>
        </div>
    </div>
</footer>

<script>
 
const profileBtn = document.querySelector('.profile-btn');
const profileDropdown = document.querySelector('.profile-dropdown');

profileBtn.addEventListener('click', () => {
    profileDropdown.classList.toggle('show');
});

 
window.addEventListener('click', function(e) {
    if (!profileBtn.contains(e.target) && !profileDropdown.contains(e.target)) {
        profileDropdown.classList.remove('show');
    }
});
</script>

</body>
</html>
