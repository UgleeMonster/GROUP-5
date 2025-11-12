<?php
session_start();
include "db/dbconnect.php";

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];

// Fetch current user info
$userQuery = $conn->prepare("SELECT email, role, address, phone FROM users WHERE username=? LIMIT 1");
$userQuery->bind_param("s", $username);
$userQuery->execute();
$userResult = $userQuery->get_result();
$userData = $userResult->fetch_assoc();

// Fallbacks
$email = $userData['email'] ?? 'No email';
$role  = $userData['role'] ?? 'Customer';
$default_address = $userData['address'] ?? '';
$default_phone = $userData['phone'] ?? '';

// Determine items to checkout
$cart_items = [];

// 1️⃣ Check if user submitted selected items from cart page
if (!empty($_POST['selected_items'])) {
    $selected_items = $_POST['selected_items'];
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
// 2️⃣ Check if user did a direct buy
elseif (isset($_SESSION['checkout_item'])) {
    $item = $_SESSION['checkout_item'];
    $product_id = $item['product_id'];
    $quantity = $item['quantity'];

    $product = $conn->query("SELECT * FROM products WHERE id=$product_id")->fetch_assoc();
    if ($product) {
        $cart_items[] = [
            'cart_id' => 0,
            'product_id' => $product['id'],
            'name' => $product['name'],
            'price' => $product['price'],
            'image' => $product['image'],
            'quantity' => $quantity
        ];
    }
} 
// 3️⃣ Fallback: load all cart items if user accessed checkout.php directly
else {
    $cart_items_result = $conn->query("
        SELECT cart.id AS cart_id, products.id AS product_id, products.name, products.price, products.image, cart.quantity
        FROM cart 
        JOIN products ON cart.product_id = products.id
        WHERE cart.username='$username'
    ");
    while ($row = $cart_items_result->fetch_assoc()) {
        $cart_items[] = $row;
    }
}

// Helper: generate receipt code
function generateReceiptCode($length = 10) {
    $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $code = '';
    for ($i = 0; $i < $length; $i++) {
        $code .= $characters[rand(0, strlen($characters)-1)];
    }
    return $code;
}

// Initialize
$success_msg = '';
$receipt_code = '';
$hide_form = false;

// Only unset order_placed if this is a fresh visit without submitting the form
if (!isset($_POST['place_order'])) {
    unset($_SESSION['order_placed']);
}

// Place new order
if (isset($_POST['place_order'])) {
    if (empty($cart_items)) {
        $success_msg = "You didn't order anything :3";
    } else {
        $shipping_address = $_POST['address'];
        $shipping_email = $_POST['email'];
        $shipping_phone = $_POST['phone'];
        $payment_type = $_POST['payment_type'];
        $payment_number = $_POST['payment_number'] ?? NULL;

        // Generate receipt code for this order
        $receipt_code = generateReceiptCode();

        foreach ($cart_items as $item) {
            $product_id = $item['product_id'];
            $quantity = $item['quantity'];
            $price = $item['price'];
            $total = $quantity * $price;

            // Apply 20% discount if quantity > 5
            if ($quantity > 5) {
                $total *= 0.8;
            }

            // Record transaction
            $stmt = $conn->prepare("INSERT INTO purchases 
                (customer_name, product_name, quantity, price, total, purchase_date, payment_type, payment_number, receipt_code)
                VALUES (?, ?, ?, ?, ?, NOW(), ?, ?, ?)
            ");
            $stmt->bind_param(
                "ssiddsss",
                $username,
                $item['name'],
                $quantity,
                $price,
                $total,
                $payment_type,
                $payment_number,
                $receipt_code
            );
            $stmt->execute();

            // Reduce stock and remove from cart if applicable
            if (isset($item['cart_id']) && $item['cart_id'] != 0) {
                $conn->query("UPDATE products SET stock = stock - $quantity WHERE id=$product_id");
                $conn->query("DELETE FROM cart WHERE id={$item['cart_id']} AND username='$username'");
            } else {
                $conn->query("UPDATE products SET stock = stock - $quantity WHERE id=$product_id");
            }
        }

        unset($_SESSION['checkout_item']);
        $_SESSION['order_placed'] = true;

        $success_msg = "🎉 Order placed successfully! Thank you for shopping with us.";
        $hide_form = true;
    }
}

if (isset($_SESSION['order_placed']) && $_SESSION['order_placed'] === true) {
    $hide_form = true;
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
<link rel="stylesheet" href="checkout.css?v=6">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
</head>
<body>

<header>
    <div class="logo-left"><img src="logo.png" alt="New Dawn Thrift Logo"></div>
    <nav class="nav-center">
        <a href="home.php">Home</a>
        <a href="product1.php">Products</a>
        <a href="aboutus.php">About Us</a>
        <a href="contactus.php">Contact</a>
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

<main class="checkout-main">
    <?php if($success_msg): ?>
        <div class="success-msg"><?= htmlspecialchars($success_msg) ?></div>
        <?php if($receipt_code): ?>
            <div class="receipt-popup" id="receiptPopup">
                🎉 Transaction Successful!<br>
                Receipt Code: <span id="receiptCode"><?= $receipt_code ?></span><br>
                <button class="copy-btn" onclick="copyReceiptCode()">Copy Code</button>
            </div>
        <?php endif; ?>
    <?php endif; ?>

    <?php if(!$hide_form): ?>
    <div class="checkout-wrapper">
        <div class="floating-message">
            🚀 Checkout! You're just one step away from your new treasure! 🛒
        </div>
    </div>

    <form method="POST" class="checkout-form">
        <h3>Shipping Information</h3>
        <label>Address</label>
        <input type="text" name="address" value="<?= htmlspecialchars($default_address) ?>" required>
        <label>Email</label>
        <input type="email" name="email" value="<?= htmlspecialchars($email) ?>" required>
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
            <?php if(empty($cart_items)): ?>
                <p>No items selected.</p>
            <?php else: ?>
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
            <?php endif; ?>
        </div>

        <div class="checkout-totals">
            <p>Subtotal: ₱<?= number_format($subtotal,2) ?></p>
            <p>Discount: ₱<?= number_format($discount,2) ?></p>
            <p>Shipping Fee: ₱<?= number_format($shipping_fee,2) ?></p>
            <p><strong>Grand Total: ₱<?= number_format($grand_total+$shipping_fee,2) ?></strong></p>
            <p>Estimated Delivery: <?= $estimated_delivery ?></p>
        </div>

        <?php foreach($cart_items as $item): ?>
            <?php if(isset($item['cart_id']) && $item['cart_id'] != 0): ?>
                <input type="hidden" name="selected_items[]" value="<?= $item['cart_id'] ?>">
            <?php endif; ?>
        <?php endforeach; ?>

        <button type="submit" name="place_order" class="place-order-btn">Place Order</button>
    </form>
    <?php endif; ?>
</main>

<script>
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

// PROFILE DROPDOWN
const profileBtn = document.querySelector('.profile-btn');
const profileDropdown = document.querySelector('.profile-dropdown');
profileBtn.addEventListener('click', () => {
    profileDropdown.classList.toggle('show');
});
window.addEventListener('click', (e) => {
    if (!profileBtn.contains(e.target) && !profileDropdown.contains(e.target)) {
        profileDropdown.classList.remove('show');
    }
});

// ✅ COPY RECEIPT CODE
function copyReceiptCode() {
    const code = document.getElementById("receiptCode").textContent;
    navigator.clipboard.writeText(code).then(() => {
        alert("✅ Receipt code copied to clipboard: " + code);
    });
}
</script>
</body>
</html>
