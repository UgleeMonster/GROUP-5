<?php
session_start();
include "db/dbconnect.php";

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];

// Handle delete via AJAX
if (isset($_POST['delete_item'])) {
    $cart_id = $_POST['cart_id'];
    $conn->query("DELETE FROM cart WHERE id=$cart_id AND username='$username'");
}

// Fetch cart items
$cart_items = $conn->query("
    SELECT cart.id AS cart_id, products.id AS product_id, products.name, products.price, products.image, cart.quantity
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
<link rel="stylesheet" href="products.css">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
<style>
/* --- Same design as before --- */
header { display:flex; justify-content:space-between; align-items:center; padding:15px 80px; }
.logo-left img { width:200px; height:auto; }
.nav-center { background-color:#d9d9d9; border-radius:90px; padding:8px 25px; display:flex; gap:30px; }
.nav-center a { text-decoration:none; color:black; padding:8px 20px; border-radius:50px; font-size:16px; font-weight:500; transition:0.3s; }
.nav-center a:hover { background-color:white; font-weight:600; }
.right-icons { display:flex; align-items:center; gap:15px; }
.cart-btn { background-color:#D4AF37; width:40px; height:40px; border-radius:50%; display:flex; align-items:center; justify-content:center; color:white !important; font-size:18px; text-decoration:none; }
.cart-btn:hover { transform:scale(1.1); }
.logout-btn { background-color:#d9d9d9; color:black !important; padding:8px 20px; border-radius:25px; font-size:14px; font-weight:500; }
.logout-btn:hover { background-color:#bfbfbf; transform:scale(1.05); }

.cart-main { padding:40px 80px; }
.cart-columns, .cart-item-row { display:flex; align-items:center; gap:10px; padding:10px; border-radius:12px; margin-bottom:10px; }
.cart-columns { font-weight:600; border-bottom:2px solid #d9d9d9; }
.cart-columns div, .cart-item-row div { text-align:center; }
.cart-columns .id-col, .cart-item-row .id-col { flex:0.5; }
.cart-columns .image-col, .cart-item-row .image-col { flex:1.2; }
.cart-columns .name-col, .cart-item-row .name-col { flex:2; text-align:left; }
.cart-columns .price-col, .cart-item-row .price-col { flex:1; }
.cart-columns .quantity-col, .cart-item-row .quantity-col { flex:1.2; }
.cart-columns .total-col, .cart-item-row .total-col { flex:1; }
.cart-columns .actions-col, .cart-item-row .actions-col { flex:0.8; }
.cart-columns .select-col, .cart-item-row .select-col { flex:0.5; }

.cart-item-row { background-color:#f5f5f5; padding:12px; }
.cart-item-row img { width:100px; height:150px; object-fit:cover; border-radius:10px; }

.quantity-control { display:flex; align-items:center; justify-content:center; gap:5px; }
.quantity-control input { width:50px; text-align:center; border-radius:8px; padding:5px; border:1px solid #ccc; }
.quantity-control button { padding:5px 10px; border:none; border-radius:6px; background-color:#D4AF37; color:white; cursor:pointer; }
.quantity-control button:hover { background-color:#b6932f; }

.delete-btn { background-color: #dc2626; color:white; padding: 6px 12px; border:none; border-radius:6px; cursor:pointer; }
.delete-btn:hover { background-color:#b91c1c; }

.cart-totals-box { max-width:400px; margin-left:auto; background-color:#f5f5f5; padding:20px; border-radius:12px; box-shadow:0 3px 8px rgba(0,0,0,0.1); font-size:18px; }
.cart-totals-box p { margin:10px 0; }
.checkout-btn { display:block; width:100%; padding:12px 0; background-color:#1f2937; color:white; font-weight:600; border-radius:8px; border:none; cursor:pointer; font-size:18px; text-align:center; margin-top:15px; text-decoration:none; }

.empty-row { text-align:center; font-style:italic; color:#555; padding:30px; flex:1; }
</style>
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
?>
<div class="cart-item-row" data-id="<?= $item['cart_id'] ?>" data-price="<?= $item['price'] ?>">
    <div class="id-col"><?= $counter++ ?></div>
    <div class="image-col"><img src="<?= $item['image'] ?>" alt="<?= $item['name'] ?>"></div>
    <div class="name-col"><?= $item['name'] ?></div>
    <div class="price-col">₱<span class="item-price"><?= number_format($item['price'],2) ?></span></div>
    <div class="quantity-col">
        <div class="quantity-control">
            <button type="button" class="dec-btn">-</button>
            <input type="number" class="qty-display" value="<?= $item['quantity'] ?>" readonly>
            <button type="button" class="inc-btn">+</button>
        </div>
    </div>
    <div class="total-col">₱<span class="item-total"><?= number_format($item['price']*$item['quantity'],2) ?></span></div>
    <div class="actions-col">
        <button type="button" class="delete-btn">Delete</button>
    </div>
    <div class="select-col">
        <input type="checkbox" class="item-checkbox">
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
const subtotalEl = document.getElementById('subtotal');
const shippingEl = document.getElementById('shipping');
const grandtotalEl = document.getElementById('grandtotal');
const shippingFee = 5;

// Update row total individually
function updateRowTotal(row) {
    const price = parseFloat(row.dataset.price);
    const qty = parseInt(row.querySelector('.qty-display').value);
    const rowTotal = price * qty;
    row.querySelector('.item-total').textContent = rowTotal.toFixed(2);
    return rowTotal;
}

// Update bottom totals (only for checked items)
function updateTotals() {
    let subtotal = 0;
    let shipping = 0;

    document.querySelectorAll('.cart-item-row:not(.empty-row)').forEach(row=>{
        updateRowTotal(row); // always update row total visually
        const checkbox = row.querySelector('.item-checkbox');
        if(checkbox.checked){
            subtotal += parseFloat(row.querySelector('.item-total').textContent);
            shipping += shippingFee;
        }
    });

    subtotalEl.textContent = subtotal.toFixed(2);
    shippingEl.textContent = shipping.toFixed(2);
    grandtotalEl.textContent = (subtotal + shipping).toFixed(2);
}

// Initial totals
updateTotals();

// Quantity buttons, DB update, delete button, and checkbox listener
document.querySelectorAll('.cart-item-row:not(.empty-row)').forEach(row=>{
    const dec = row.querySelector('.dec-btn');
    const inc = row.querySelector('.inc-btn');
    const qtyInput = row.querySelector('.qty-display');
    const checkbox = row.querySelector('.item-checkbox');
    const cart_id = row.dataset.id;

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
        qty++;
        qtyInput.value = qty;
        updateTotals();
        updateDB(qty);
    });

    // Delete button
    row.querySelector('.delete-btn').addEventListener('click', ()=>{
        if(confirm('Are you sure you want to delete this item?')){
            fetch('cart.php',{
                method:'POST',
                headers:{'Content-Type':'application/x-www-form-urlencoded'},
                body:'delete_item=1&cart_id='+cart_id
            }).then(()=>{
                row.remove();
                updateTotals();
                if(document.querySelectorAll('.cart-item-row:not(.empty-row)').length===0){
                    const emptyRow = document.createElement('div');
                    emptyRow.classList.add('cart-item-row','empty-row');
                    emptyRow.textContent = "Your cart is empty.";
                    document.getElementById('checkoutForm').insertBefore(emptyRow, document.querySelector('.cart-totals-box'));
                }
            });
        }
    });

    // Checkbox change
    checkbox.addEventListener('change', updateTotals);
});

// Checkout button
document.getElementById('checkoutBtn').addEventListener('click', ()=>{
    const selected = document.querySelectorAll('.item-checkbox:checked');
    if(selected.length===0){
        alert('Please select at least one item to checkout.');
        return;
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
