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


 
if (isset($_POST['delete_item'])) {
    $cart_id = $_POST['cart_id'];
    $conn->query("DELETE FROM cart WHERE id=$cart_id AND username='$username'");
}

 
$cart_items = $conn->query("
    SELECT cart.id AS cart_id, products.id AS product_id, products.name, products.price, products.image, products.stock, cart.quantity
    FROM cart 
    JOIN products ON cart.product_id = products.id
    WHERE cart.username='$username'
    ORDER BY cart.id ASC
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Cart - New Dawn Thrift</title>

<!-- External Stylesheet -->
<link rel="stylesheet" href="cart.css?v=5">

<!-- Fonts & Icons -->
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />

</head>
<body>

<header>
    <div class="logo-left"><img src="logo.png" alt="New Dawn Thrift Logo"></div>
    <nav class="nav-center">
        <a href="home.php">Home</a>
        <a href="product1.php">Products</a>
        <a href="aboutus.php">About Us</a>
        <a href="contactus.php">Contact us</a>
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

<main class="cart-main">

<div class="cart-columns">
    <div class="id-col">ID</div>
    <div class="image-col">IMAGE</div>
    <div class="name-col">NAME</div>
    <div class="price-col">PRICE</div>
    <div class="quantity-col">QUANTITY</div>
    <div class="total-col">TOTAL</div>
    <div class="actions-col">DELETE</div>
    <div class="select-col">SELECT</div>
</div>

<form id="checkoutForm" method="POST" action="checkout.php">

<?php
$counter = 1;
if($cart_items->num_rows > 0):
    while ($item = $cart_items->fetch_assoc()):
        $isOut = $item['stock'] <= 0;
?>
<div class="cart-item-row <?= $isOut ? 'out-of-stock' : '' ?>" 
     data-id="<?= $item['cart_id'] ?>" 
     data-price="<?= $item['price'] ?>" 
     data-stock="<?= $item['stock'] ?>" 
     data-name="<?= htmlspecialchars($item['name']) ?>">
    <div class="id-col"><?= $counter++ ?></div>
    <div class="image-col"><img src="<?= $item['image'] ?>" alt="<?= $item['name'] ?>"></div>
    <div class="name-col"><?= $item['name'] ?></div>
    <div class="price-col">₱<span class="item-price"><?= number_format($item['price'],2) ?></span></div>
    <div class="quantity-col">
        <div class="quantity-control">
            <button type="button" class="dec-btn" <?= $isOut ? 'disabled' : '' ?>>-</button>
            <input type="number" class="qty-display" value="<?= $item['quantity'] ?>" readonly>
            <button type="button" class="inc-btn" <?= $isOut ? 'disabled' : '' ?>>+</button>
        </div>
    </div>
    <div class="total-col">₱<span class="item-total"><?= number_format($item['price']*$item['quantity'],2) ?></span></div>
    <div class="actions-col">
        <button type="button" class="delete-btn">Delete</button>
    </div>
    <div class="select-col">
        <input type="checkbox" class="item-checkbox" <?= $isOut ? 'disabled' : '' ?>>
    </div>
</div>
<?php
    endwhile;
else: 
?>
<div class="cart-item-row empty-row">
    Your cart is empty.
</div>
<?php endif; ?>

<div class="cart-totals-box">
    <p>Subtotal: ₱<span id="subtotal">0.00</span></p>
    <p>Shipping Fee: ₱<span id="shipping">0.00</span></p>
    <p><strong>Grand Total: ₱<span id="grandtotal">0.00</span></strong></p>
    <button type="button" class="checkout-btn" id="checkoutBtn">Proceed to Checkout</button>
</div>
</form>

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

 
const subtotalEl = document.getElementById('subtotal');
const shippingEl = document.getElementById('shipping');
const grandtotalEl = document.getElementById('grandtotal');
const shippingFee = 5;

function updateRowTotal(row) {
    const price = parseFloat(row.dataset.price);
    const qty = parseInt(row.querySelector('.qty-display').value);
    const rowTotal = price * qty;
    row.querySelector('.item-total').textContent = rowTotal.toFixed(2);
    return rowTotal;
}

function updateTotals() {
    let subtotal = 0;
    let shipping = 0;

    document.querySelectorAll('.cart-item-row:not(.empty-row)').forEach(row=>{
        updateRowTotal(row);
        const checkbox = row.querySelector('.item-checkbox');
        if(checkbox && checkbox.checked){
            subtotal += parseFloat(row.querySelector('.item-total').textContent);
            shipping += shippingFee;
        }
    });

    subtotalEl.textContent = subtotal.toFixed(2);
    shippingEl.textContent = shipping.toFixed(2);
    grandtotalEl.textContent = (subtotal + shipping).toFixed(2);
}

updateTotals();

document.querySelectorAll('.cart-item-row:not(.empty-row)').forEach(row=>{
    const dec = row.querySelector('.dec-btn');
    const inc = row.querySelector('.inc-btn');
    const qtyInput = row.querySelector('.qty-display');
    const checkbox = row.querySelector('.item-checkbox');
    const cart_id = row.dataset.id;
    const stock = parseInt(row.dataset.stock);

    if(!dec || !inc) return;  

    function updateDB(qty){
        fetch('update_cart_quantity.php', {
            method:'POST',
            headers:{'Content-Type':'application/x-www-form-urlencoded'},
            body:'cart_id=' + cart_id + '&quantity=' + qty
        });
    }

    dec.addEventListener('click', ()=>{
        let qty = parseInt(qtyInput.value);
        if(qty>1) qty--;
        qtyInput.value = qty;
        updateTotals();
        updateDB(qty);
    });

    inc.addEventListener('click', ()=>{
        let qty = parseInt(qtyInput.value);
        if(qty < stock){
            qty++;
            qtyInput.value = qty;
            updateTotals();
            updateDB(qty);
        } else {
            alert(`Cannot add more. Only ${stock} in stock.`);
        }
    });

    row.querySelector('.delete-btn').addEventListener('click', ()=>{
        if(confirm('Are you sure you want to delete this item?')){
            fetch('cart.php',{
                method:'POST',
                headers:{'Content-Type':'application/x-www-form-urlencoded'},
                body:'delete_item=1&cart_id='+cart_id
            }).then(()=>{
                row.remove();
                updateTotals();
            });
        }
    });

    if(checkbox) checkbox.addEventListener('change', updateTotals);
});

document.getElementById('checkoutBtn').addEventListener('click', ()=>{
    const selected = document.querySelectorAll('.item-checkbox:checked');
    if(selected.length===0){
        alert('Please select at least one item to checkout.');
        return;
    }

    for(const cb of selected){
        const row = cb.closest('.cart-item-row');
        const qty = parseInt(row.querySelector('.qty-display').value);
        const stock = parseInt(row.dataset.stock);
        const name = row.dataset.name;
        if(qty > stock){
            alert(`Cannot checkout "${name}". Only ${stock} in stock.`);
            return;
        }
    }

    const form = document.getElementById('checkoutForm');
    form.querySelectorAll('input[name="selected_items[]"]').forEach(i=>i.remove());

    selected.forEach(cb=>{
        const row = cb.closest('.cart-item-row');
        const hidden = document.createElement('input');
        hidden.type = 'hidden';
        hidden.name = 'selected_items[]';
        hidden.value = row.dataset.id;
        form.appendChild(hidden);
    });

    form.submit();
});
</script>

</body>
</html>
