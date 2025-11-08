<?php
session_start();
include "db/dbconnect.php";

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];

// Handle add to cart
if (isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];

    // Check if product is already in the user's cart
    $check = $conn->query("SELECT * FROM cart WHERE username='$username' AND product_id=$product_id");
    if ($check->num_rows > 0) {
        // If already exists, increase quantity by 1
        $conn->query("UPDATE cart SET quantity = quantity + 1 WHERE username='$username' AND product_id=$product_id");
    } else {
        // Insert new cart entry
        $conn->query("INSERT INTO cart (username, product_id, quantity) VALUES ('$username', $product_id, 1)");
    }
}

// Fetch Mens products
$products = $conn->query("SELECT * FROM products WHERE category='Mens' ORDER BY id ASC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mens - New Dawn Thrift</title>
    <link rel="stylesheet" href="products.css">
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
        <a href="aboutus.html">About Us</a>
        <a href="contactus.php">Contact</a>
    </nav>

    <div class="right-icons">
        <a href="cart.php" class="cart-btn"><i class="fas fa-shopping-cart"></i></a>
        <a href="login.php" class="logout-btn">Logout</a>
    </div>
</header>

<main class="products-main">
    <!-- LEFT SIDEBAR -->
    <aside class="sidebar">
        <h3>Categories</h3>
        <ul>
            <li class="active"><a href="product1.php">Mens</a></li>
            <li><a href="product2.php">Womens</a></li>
            <li><a href="product3.php">Kids</a></li>
            <li><a href="product4.php">Unisex</a></li>
        </ul>
        <p style="margin-top: 20px; font-size: 14px;">Welcome to Mens Section! Find your style here.</p>
    </aside>

    <!-- RIGHT PRODUCTS SECTION -->
    <section class="products-grid">

        <?php while($prod = $products->fetch_assoc()): ?>
        <div class="product-card">
            <img src="<?= $prod['image'] ?>" alt="<?= $prod['name'] ?>">
            <h4><?= $prod['name'] ?></h4>
            <div class="product-buttons">
                <form method="POST" style="display:flex; gap:10px; align-items:center;">
                    <input type="hidden" name="product_id" value="<?= $prod['id'] ?>">
                    <button type="submit" name="add_to_cart" class="add-btn">ADD</button>
                    <span class="price">₱<?= number_format($prod['price'],2) ?></span>
                    <button type="button" class="buy-btn" onclick="window.location.href='cart.php'">BUY</button>
                </form>
            </div>
        </div>
        <?php endwhile; ?>

    </section>
</main>

</body>
</html>
