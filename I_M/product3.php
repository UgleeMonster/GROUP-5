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

 
if (isset($_POST['buy_now'])) {
    $_SESSION['checkout_item'] = [
        'product_id' => $_POST['product_id'],
        'quantity' => 1
    ];
    header("Location: checkout.php");
    exit();
}

 
$products = $conn->query("SELECT * FROM products WHERE category='Kids' ORDER BY id ASC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Kids - New Dawn Thrift</title>
<link rel="stylesheet" href="products.css?v=4">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
</head>
<body>

<header>
    <div class="logo-left">
        <img src="logo.png" alt="New Dawn Thrift Logo">
    </div>

    <nav class="nav-center">
        <a href="home.php">Home</a>
        <a href="products.php" class="active">Products</a>
        <a href="aboutus.php">About Us</a>
        <a href="contactus.php">Contact</a>
    </nav>

    <div class="right-icons">
        <a href="cart.php" class="cart-btn"><i class="fas fa-shopping-cart"></i></a>

        <!-- PROFILE ICON -->
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

<main class="products-main">
    <!-- LEFT SIDEBAR -->
    <aside class="sidebar">
        <h3>Categories</h3>
        <ul>
            <li><a href="product1.php">Mens</a></li>
            <li><a href="product2.php">Womens</a></li>
            <li class="active"><a href="product3.php">Kids</a></li>
            <li><a href="product4.php">Unisex</a></li>
        </ul>
        <p class="sidebar-message">Welcome to Kids Section! Fun and comfy styles for kids.</p>
    </aside>

    <!-- RIGHT PRODUCTS SECTION -->
    <section class="products-grid">
        <?php while($prod = $products->fetch_assoc()): ?>
            <?php $isOut = $prod['stock'] <= 0; ?>
            <div class="product-card <?= $isOut ? 'out-of-stock' : '' ?>">
                <img src="<?= $prod['image'] ?>" alt="<?= htmlspecialchars($prod['name']) ?>">
                <h4><?= htmlspecialchars($prod['name']) ?></h4>
                <div class="product-buttons">
                    <form method="POST" style="display:flex; gap:10px; align-items:center;">
                        <input type="hidden" name="product_id" value="<?= $prod['id'] ?>">
                        <button type="button" class="add-btn" data-id="<?= $prod['id'] ?>" <?= $isOut ? 'disabled' : '' ?>>ADD</button>
                        <span class="price">₱<?= number_format($prod['price'],2) ?></span>
                        <button type="submit" name="buy_now" class="buy-btn" <?= $isOut ? 'disabled' : '' ?>>BUY</button>
                    </form>
                </div>
            </div>
        <?php endwhile; ?>
    </section>
</main>

<!-- AJAX for ADD button & Profile Dropdown -->
<script>
document.querySelectorAll('.add-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        const productId = this.getAttribute('data-id');
        fetch('add_to_cart.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'product_id=' + encodeURIComponent(productId)
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                alert('✅ ' + data.message);
            } else {
                alert('⚠️ ' + data.message);
            }
        })
        .catch(err => {
            console.error(err);
            alert('An error occurred while adding to cart.');
        });
    });
});

 
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
