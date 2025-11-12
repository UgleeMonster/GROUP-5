<?php
session_start();
include "db/dbconnect.php";

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];

 
$user = $conn->query("SELECT username, email, role FROM users WHERE username='$username'")->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Home - New Dawn Thrift</title>
<link rel="stylesheet" href="./home.css?v=3">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
<style>
 
.profile-wrapper {
    position: relative;
}

.profile-btn {
    font-size: 20px;
    cursor: pointer;
    background-color: #D4AF37;
    color: white;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    border: none;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: 0.2s ease;
}

.profile-btn:hover {
    transform: scale(1.1);
    box-shadow: 0 4px 12px rgba(0,0,0,0.3);
}

 
 
.profile-dropdown {
    position: absolute;
    top: 50px;
    right: 0;
    background: linear-gradient(145deg, #f5f5f5, #e0e0e0);
    padding: 15px;
    border-radius: 15px;
    width: 240px;
    box-shadow: 0 8px 25px rgba(0,0,0,0.25);
    transform: translateY(-20px);
    opacity: 0;
    visibility: hidden;
    transition: all 0.3s ease;
    z-index: 100;
    font-family: 'Inter', sans-serif;
}

 
.profile-dropdown.show {
    transform: translateY(0);
    opacity: 1;
    visibility: visible;
}

 
.profile-card-header {
    display: flex;
    flex-direction: column;
    align-items: center;
    margin-bottom: 10px;
    border-bottom: 1px solid rgba(0,0,0,0.1);
    padding-bottom: 10px;
}

.avatar-emoji {
    font-size: 40px;
    margin-bottom: 5px;
}

.username {
    font-weight: 600;
    font-size: 16px;
    color: #333;
}

 
.profile-card-body p {
    font-size: 14px;
    color: #555;
    margin: 6px 0;
    transition: background 0.2s ease;
    padding: 3px 6px;
    border-radius: 8px;
}

.profile-card-body p:hover {
    background: rgba(212, 175, 55, 0.1);  
}

 
.profile-dropdown .logout-btn {
    display: block;
    margin-top: 12px;
    text-align: center;
    background-color: #ff4d4d;
    color: white !important;
    padding: 8px 15px;
    border-radius: 25px;
    font-size: 14px;
    text-decoration: none;
    font-weight: 500;
    transition: 0.2s ease;
}

.profile-dropdown .logout-btn:hover {
    background-color: #e63939;
    transform: scale(1.05);
}

 
.profile-dropdown::before {
    content: '';
    position: absolute;
    top: -8px;
    right: 22px;
    border-width: 0 8px 8px 8px;
    border-style: solid;
    border-color: transparent transparent #f5f5f5 transparent;
}

</style>
</head>
<body>

<!-- HEADER -->
<header>
    <div class="logo-left">
        <img src="logo.png" alt="Logo">
    </div>

    <nav class="nav-center">
        <a href="#" class="active">HOME</a>
        <a href="product1.php">PRODUCT</a>
        <a href="aboutus.php">ABOUT US</a>
        <a href="contactus.php">CONTACT US</a>
    </nav>

    <div class="right-icons">
        <a href="cart.php" class="cart-btn" aria-label="Cart"><i class="fas fa-shopping-cart"></i></a>

        <!-- Profile emoji dropdown -->
<div class="profile-wrapper">
    <button class="profile-btn" id="profileBtn">👤</button>
    <div class="profile-dropdown" id="profileDropdown">
        <div class="profile-card-header">
            <span class="avatar-emoji">👤</span>
            <p class="username"><?= htmlspecialchars($user['username']) ?></p>
        </div>
        <div class="profile-card-body">
            <p><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>
            <p><strong>Role:</strong> <?= ucfirst($user['role']) ?></p>
        </div>
        <a href="login.php" class="logout-btn">Logout</a>
    </div>
</div>

    </div>
</header>

<!-- MAIN SECTION -->
<main>
    <div class="hero">
        <h2>Where vintage charm meets modern style,<br>giving fashion a second chance to shine.</h2>
        <p>Unveiling a thrift destination where every piece has a past, yet speaks to the present. Here, fashion finds new life, sustainability becomes style, and your wardrobe grows with unique treasures that tell a story worth wearing.</p>

        <button class="collection-btn" onclick="window.location.href='product4.php'">
            New Collection <span class="arrow-circle"><i class="fas fa-arrow-right"></i></span>
        </button>

        <div class="gallery">
            <a href="product1.php"><img src="LEFT.jpg" alt="Left"></a>
            <a href="product2.php"><img src="MIDDLE.jpg" alt="Middle"></a>
            <a href="product3.php"><img src="RIGHT.jpg" alt="Right"></a>
        </div>
    </div>
</main>

<!-- FOOTER -->
<footer>
    <hr>
    <div class="footer-container">
        <div class="footer-left">
            <p>About Us</p>
            <p>Terms & Condition</p>
        </div>
        <div class="footer-center">
            <p class="brand">NEW DAWN THRIFT</p>
            <p>Follow Us</p>
            <div class="socials">
                <a href="https://www.facebook.com" target="_blank"><img src="facebook.png" alt="Facebook"></a>
                <a href="https://www.instagram.com" target="_blank"><img src="instagram.png" alt="Instagram"></a>
                <a href="https://www.tiktok.com" target="_blank"><img src="tiktok.png" alt="Tiktok"></a>
            </div>
        </div>
        <div class="footer-right">
            <p>Privacy Policy</p>
            <p>Contact Us</p>
            <p>+63-2-8123-4567</p>
            <p>newdawnthrift@gmail.com</p>
        </div>
    </div>
</footer>

<script>
 
const profileBtn = document.getElementById('profileBtn');
const profileDropdown = document.getElementById('profileDropdown');

profileBtn.addEventListener('click', () => {
    profileDropdown.classList.toggle('show');
});

 
document.addEventListener('click', function(event) {
    if (!profileBtn.contains(event.target) && !profileDropdown.contains(event.target)) {
        profileDropdown.classList.remove('show');
    }
});
</script>

</body>
</html>
