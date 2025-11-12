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
    <title>Contact Us - New Dawn Thrift</title>
    <link rel="stylesheet" href="contactus.css?v=5">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
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
        <a href="aboutus.php">About Us</a>
        <a href="contactus.php" class="active">Contact Us</a>
    </nav>

    <div class="right-icons">
        <a href="cart.php" class="cart-btn"><i class="fas fa-shopping-cart"></i></a>

        <!-- PROFILE DROPDOWN -->
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

<!-- MAIN CONTENT -->
<main>
    <section class="contact-header">
        <h2>CONTACT US</h2>
        <p>
            Need help with an order, have a suggestion, or just want to reach out?  
            Our admin team is here to help you with any questions or concerns.  
            Send us a message and we’ll get back to you as soon as possible!
        </p>
    </section>

    <section class="contact-content">
        <div class="contact-info">
            <div class="info-item">
                <div style="font-size: 35px;">📍</div>
                <div>
                    <h3>ADDRESS</h3>
                    <p>Sta. Inis, Mabalacat, Pampanga</p>
                </div>
            </div>

            <div class="info-item">
                <div style="font-size: 35px;">📞</div>
                <div>
                    <h3>PHONE</h3>
                    <p>+63 912 345 6789</p>
                </div>
            </div>

            <div class="info-item">
                <div style="font-size: 35px;">✉️</div>
                <div>
                    <h3>E-MAIL</h3>
                    <p>newdawnthrift@gmail.com</p>
                </div>
            </div>
        </div>

        <div class="contact-form">
            <h3>SEND MESSAGE</h3>
            <form action="send_message.php" method="POST">
                <label>Full Name</label>
                <input type="text" name="fullname" required>

                <label>E-mail</label>
                <input type="email" name="email" required>

                <label>Subject</label>
                <input type="text" name="subject" required>

                <label>Type your message...</label>
                <textarea name="message" rows="4" required></textarea>

                <button type="submit">SEND</button>
            </form>
        </div>
    </section>
</main>

<!-- PROFILE DROPDOWN JS -->
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
