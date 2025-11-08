<?php
session_start();
include "db/dbconnect.php";

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];

// Fetch user's default info
$user = $conn->query("SELECT address, email, phone FROM users WHERE username='$username'")->fetch_assoc();
$default_address = $user['address'] ?? '';
$default_email = $user['email'] ?? '';
$default_phone = $user['phone'] ?? '';

// Handle placed order
if (isset($_POST['place_order'])) {
    $selected_items = $_POST['selected_items'] ?? [];
    $shipping_address = $_POST['address'];
    $shipping_email = $_POST['email'];
    $shipping_phone = $_POST['phone'];
    $payment_type = $_POST['payment_type'];
    $payment_number = $_POST['payment_number'] ?? NULL;

    if (!empty($selected_items)) {
        foreach ($selected_items as $cart_id) {
            $cart_item = $conn->query("
                SELECT cart.product_id, cart.quantity, products.price 
                FROM cart 
                JOIN products ON cart.product_id = products.id 
                WHERE cart.id=$cart_id AND cart.username='$username'
            ")->fetch_assoc();

            $product_id = $cart_item['product_id'];
            $quantity = $cart_item['quantity'];
            $price = $cart_item['price'];
            $total = $quantity * $price;

            // Apply 20% discount if quantity > 5
            if ($quantity > 5) {
                $total *= 0.8;
            }

            // Record transaction with payment info
            $conn->query("
                INSERT INTO purchases (customer_name, product_name, quantity, price, total, purchase_date, payment_type, payment_number) 
                VALUES (
                    '$username', 
                    (SELECT name FROM products WHERE id=$product_id), 
                    $quantity, 
                    $price, 
                    $total, 
                    NOW(), 
                    '$payment_type', 
                    '$payment_number'
                )
            ");

            // Reduce stock
            $conn->query("UPDATE products SET stock = stock - $quantity WHERE id=$product_id");

            // Remove from cart
            $conn->query("DELETE FROM cart WHERE id=$cart_id AND username='$username'");
        }

        $success_msg = "🎉 Order placed successfully! Thank you for shopping with us.";
    }
}

// Fetch selected items from cart (from POST)
$selected_items = $_POST['selected_items'] ?? [];
$cart_items = [];

if (!empty($selected_items)) {
    $ids = implode(",", array_map('intval', $selected_items));
    $cart_items_result = $conn->query("
        SELECT cart.id AS cart_id, products.id AS product_id, products.name, products.price, products.image, cart.quantity
        FROM cart 
        JOIN products ON cart.product_id = products.id
        WHERE cart.id IN ($ids) AND cart.username='$username'
    ");

    while ($row = $cart_items_result->fetch_assoc()) {
        $cart_items[] = $row;
    }
}

// Calculate totals
$subtotal = 0;
$total_quantity = 0;
foreach ($cart_items as $item) {
    $subtotal += $item['price'] * $item['quantity'];
    $total_quantity += $item['quantity'];
}
$discount = ($total_quantity > 5) ? $subtotal * 0.2 : 0;
$grand_total = $subtotal - $discount;
$shipping_fee = 50;
$estimated_delivery = date('F j, Y', strtotime('+5 days'));
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Checkout - New Dawn Thrift</title>
<link rel="stylesheet" href="checkout.css">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
</head>
<body>

<header>
    <div class="logo-left"><img src="logo.png" alt="New Dawn Thrift Logo"></div>
    <nav class="nav-center">
        <a href="home.php">Home</a>
        <a href="product1.php">Products</a>
        <a href="aboutus.html">About Us</a>
        <a href="contactus.php">Contact us</a>
    </nav>
    <div class="right-icons">
        <a href="cart.php" class="cart-btn"><i class="fas fa-shopping-cart"></i></a>
        <a href="login.php" class="logout-btn">Logout</a>
    </div>
</header>

<main class="checkout-main">
    <?php if(isset($success_msg)): ?>
        <div class="success-msg"><?= $success_msg ?></div>
    <?php endif; ?>

    <h2>🛒 Checkout</h2>
    <p class="checkout-message">You're one step away! Review your items, shipping info, and get ready to receive your order. 🎉</p>

    <form method="POST" class="checkout-form">
        <h3>Shipping Information</h3>
        <label>Address</label>
        <input type="text" name="address" value="<?= htmlspecialchars($default_address) ?>" required>

        <label>Email</label>
        <input type="email" name="email" value="<?= htmlspecialchars($default_email) ?>" required>

        <label>Phone</label>
        <input type="text" name="phone" value="<?= htmlspecialchars($default_phone) ?>" required>

        <h3>Payment Method</h3>
        <select name="payment_type" id="payment_type" required>
            <option value="COD" selected>Cash on Delivery (COD)</option>
            <option value="GCash">GCash</option>
            <option value="Credit">Credit Card</option>
        </select>

        <label id="payment_number_label" style="display:none;">Payment Number</label>
        <input type="text" name="payment_number" id="payment_number" style="display:none;" placeholder="Enter GCash/Credit number">

        <h3>Items</h3>
        <div class="checkout-items">
            <?php foreach($cart_items as $item): ?>
                <div class="checkout-item">
                    <img src="<?= $item['image'] ?>" alt="<?= $item['name'] ?>">
                    <div class="item-details">
                        <p><strong><?= $item['name'] ?></strong></p>
                        <p>Price: ₱<?= number_format($item['price'],2) ?></p>
                        <p>Qty: <?= $item['quantity'] ?></p>
                        <?php if($item['quantity']>5): ?>
                            <p class="discount">🎁 20% Discount Applied!</p>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="checkout-totals">
            <p>Subtotal: ₱<?= number_format($subtotal,2) ?></p>
            <p>Discount: ₱<?= number_format($discount,2) ?></p>
            <p>Shipping Fee: ₱<?= number_format($shipping_fee,2) ?></p>
            <p><strong>Grand Total: ₱<?= number_format($grand_total+$shipping_fee,2) ?></strong></p>
            <p>Estimated Delivery: <?= $estimated_delivery ?></p>
        </div>

        <?php foreach($selected_items as $id): ?>
            <input type="hidden" name="selected_items[]" value="<?= $id ?>">
        <?php endforeach; ?>

        <button type="submit" name="place_order" class="place-order-btn">Place Order</button>
    </form>
</main>

<script>
// Show/hide payment number field based on selection
const paymentType = document.getElementById('payment_type');
const paymentNumber = document.getElementById('payment_number');
const paymentLabel = document.getElementById('payment_number_label');

paymentType.addEventListener('change', () => {
    if(paymentType.value === 'GCash' || paymentType.value === 'Credit'){
        paymentNumber.style.display = 'block';
        paymentLabel.style.display = 'block';
        paymentNumber.required = true;
    } else {
        paymentNumber.style.display = 'none';
        paymentLabel.style.display = 'none';
        paymentNumber.required = false;
    }
});
</script>

</body>
</html>
