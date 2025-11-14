<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

include "db/dbconnect.php";

 
if (isset($_POST['add_product'])) {
    $name = $_POST['name'];
    $stock = $_POST['stock'];
    $price = $_POST['price'];
    $category = $_POST['category'];
    $image = $_POST['image'];
    $sql = "INSERT INTO products (name, stock, price, category, image) VALUES ('$name', '$stock', '$price', '$category', '$image')";
    $conn->query($sql);
}

 
if (isset($_POST['update_stock'])) {
    $id = $_POST['product_id'];
    $new_name = $_POST['name'];
    $new_price = $_POST['price'];
    $new_stock = $_POST['stock'];

    $safe_name = $conn->real_escape_string($new_name);
    $safe_price = floatval($new_price);
    $safe_stock = intval($new_stock);
    $safe_id = intval($id);

    $conn->query("UPDATE products SET name='$safe_name', price=$safe_price, stock=$safe_stock WHERE id=$safe_id");
}

 
if (isset($_POST['delete_product'])) {
    $id = $_POST['product_id'];
    $conn->query("DELETE FROM products WHERE id=$id");
}

 
if (isset($_POST['clear_logbook'])) {
    $conn->query("DELETE FROM purchases");
}

 
if (isset($_POST['clear_inbox'])) {
    $conn->query("DELETE FROM messages");
}

 
$search = isset($_GET['search']) ? $_GET['search'] : '';
$products = $conn->query("SELECT * FROM products 
    WHERE id LIKE '%$search%' 
    OR name LIKE '%$search%' 
    OR category LIKE '%$search%' 
    ORDER BY id ASC");

 
$purchases = $conn->query("SELECT * FROM purchases ORDER BY purchase_date DESC");

 
$users = $conn->query("SELECT id, username, address, email, role FROM users ORDER BY id ASC");

 
$messages = $conn->query("SELECT * FROM messages ORDER BY date_sent DESC");

 
$today = date('Y-m-d');
$weekStart = date('Y-m-d', strtotime('monday this week'));
$monthStart = date('Y-m-01');
$yearStart = date('Y-01-01');

$daily_income = $conn->query("SELECT SUM(total) AS total FROM purchases WHERE DATE(purchase_date)='$today'")->fetch_assoc()['total'] ?? 0;
$weekly_income = $conn->query("SELECT SUM(total) AS total FROM purchases WHERE DATE(purchase_date) >= '$weekStart'")->fetch_assoc()['total'] ?? 0;
$monthly_income = $conn->query("SELECT SUM(total) AS total FROM purchases WHERE DATE(purchase_date) >= '$monthStart'")->fetch_assoc()['total'] ?? 0;
$yearly_income = $conn->query("SELECT SUM(total) AS total FROM purchases WHERE DATE(purchase_date) >= '$yearStart'")->fetch_assoc()['total'] ?? 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin Dashboard - New Dawn Thrift</title>
<link href="https://fonts.googleapis.com/css?family=Inter:400,500,600&display=swap" rel="stylesheet" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
<link rel="stylesheet" href="admin.css" />
</head>
<body>

<!-- Topbar -->
<div class="topbar">
    <div class="top-left">
        <img src="teto.webp" alt="New Dawn Thrift Logo">
        <div class="marquee-wrapper">
            <div class="marquee">🌟|| GROUP 5 PROJECT || INFO MANAGEMENT || CODE CREATED/LEADER BY: Calacala, Williejay Tomas P. || Members:  De Jesus, Andrei // Payabyab, Aldrin D. // Nogueras, Jayy // Reyes, Jereek // Adrias, Rence M. // Segurate, Vincent.🌟</div>
        </div>
    </div>
    <div class="top-right">
        <div id="datetime"></div>
        <div id="wifi" title="Network Status"><i class="fas fa-wifi"></i></div>
        <a href="login.php" class="logout-btn">Logout</a>
    </div>
</div>

<div class="dashboard">
    <!-- PRODUCT MANAGEMENT -->
    <div class="card current-stock">
        <h2>📦 Product Management + Stock</h2>
        <form method="POST" style="margin-bottom: 20px;">
            <h3>Add Product</h3>
            <input type="text" name="name" placeholder="Product Name" required>
            <input type="number" name="stock" placeholder="Stock" required>
            <input type="number" step="0.01" name="price" placeholder="Price" required>
            <input type="text" name="category" placeholder="Category" required>
            <input type="text" name="image" placeholder="Image filename" required>
            <button type="submit" name="add_product">Add</button>
        </form>
        <form method="GET">
            <input type="search" name="search" placeholder="Search by ID, Name, Category" value="<?= htmlspecialchars($search); ?>">
            <button type="submit">Search</button>
        </form>
        <h3 style="margin-top: 15px;">Current Stock List</h3>
        <div class="scrollable-table">
            <table>
                <tr><th>ID</th><th>Image</th><th>Name</th><th>Category</th><th>Stock</th><th>Price</th><th>Actions</th></tr>
                <?php while($prod = $products->fetch_assoc()): ?>
                <tr>
                    <td><?= $prod['id'] ?></td>
                    <td><img src="<?= $prod['image'] ?>" class="product-img" alt="<?= $prod['name'] ?>"></td>
                    <td><?= $prod['name'] ?></td>
                    <td><span class="category-badge"><?= ucfirst($prod['category']) ?></span></td>
                    <td><?= $prod['stock'] ?></td>
                    <td>₱<?= number_format($prod['price'], 2) ?></td>
                    <td>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="product_id" value="<?= $prod['id'] ?>">
                            <input type="text" name="name" value="<?= htmlspecialchars($prod['name']) ?>" style="width:120px">
                            <input type="number" step="0.01" name="price" value="<?= $prod['price'] ?>" style="width:80px">
                            <input type="number" name="stock" value="<?= $prod['stock'] ?>" style="width:60px">
                            <button type="submit" name="update_stock" class="update-btn">Update</button>
                        </form>

                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="product_id" value="<?= $prod['id'] ?>">
                            <button type="submit" name="delete_product" class="delete-btn">Delete</button>
                        </form>
                    </td>
                </tr>
                <?php endwhile; ?>
            </table>
        </div>
    </div>

    <!-- LOGBOOK + INBOX -->
    <div class="log-inbox-wrapper">
        <div class="card log-book">
            <h2>🧾 Log Book</h2>
            <form method="POST" style="text-align: right; margin-bottom: 10px;" onsubmit="return confirm('Are you sure you want to delete the logbook history? This action cannot be undone.')">
                <button type="submit" name="clear_logbook" class="delete-btn">🗑️ Clear Logbook</button>
            </form>
            <div class="scrollable-table">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Customer</th>
                            <th>Product</th>
                            <th>Qty</th>
                            <th>Price</th>
                            <th>Total</th>
                            <th>Payment</th>
                            <th>Payment No.</th>
                            <th>Date</th>
                            <th>Receipt</th>

                        </tr>
                    </thead>
                    <tbody>
                    <?php while($row = $purchases->fetch_assoc()): ?>
                        <tr>
                            <td><?= $row['id'] ?></td>
                            <td><?= htmlspecialchars($row['customer_name']) ?></td>
                            <td><?= htmlspecialchars($row['product_name']) ?></td>
                            <td><?= $row['quantity'] ?></td>
                            <td>₱<?= number_format($row['price'],2) ?></td>
                            <td>₱<?= number_format($row['total'],2) ?></td>
                            <td><?= htmlspecialchars($row['payment_type'] ?? '-') ?></td>
                            <td><?= htmlspecialchars($row['payment_number'] ?? '-') ?></td>
                            <td><?= $row['purchase_date'] ?></td>
                            <td><?= $row['receipt_code'] ?></td>

                        </tr>
                    <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card inbox-messages">
            <h2>📩 Inbox Messages</h2>
            <form method="POST" style="text-align: right; margin-bottom: 10px;" onsubmit="return confirm('Are you sure you want to delete all inbox messages? This cannot be undone.')">
                <button type="submit" name="clear_inbox" class="delete-btn">🗑️ Clear Inbox</button>
            </form>
            <div class="scrollable-table">
                <table>
                    <tr><th>ID</th><th>Name</th><th>Email</th><th>Subject</th><th>Message</th><th>Date Sent</th></tr>
                    <?php while($msg = $messages->fetch_assoc()): ?>
                    <tr>
                        <td><?= $msg['id'] ?></td>
                        <td><?= htmlspecialchars($msg['name']) ?></td>
                        <td><?= htmlspecialchars($msg['email']) ?></td>
                        <td><?= htmlspecialchars($msg['subject']) ?></td>
                        <td><?= htmlspecialchars($msg['message']) ?></td>
                        <td><?= $msg['date_sent'] ?></td>
                    </tr>
                    <?php endwhile; ?>
                </table>
            </div>
        </div>

        <img src="logo.png" alt="New Dawn Thrift Logo" id="bottom-logo">
    </div>

    <!-- USERS -->
    <div class="card user-list">
        <h2>👥 User Accounts</h2>
        <div class="user-table-wrapper">
            <table>
                <tr><th>ID</th><th>Username</th><th>Address</th><th>Email</th><th>Role</th></tr>
                <?php while($user = $users->fetch_assoc()): ?>
                <tr>
                    <td><?= $user['id'] ?></td>
                    <td><?= $user['username'] ?></td>
                    <td><?= $user['address'] ?></td>
                    <td><?= $user['email'] ?></td>
                    <td><?= ucfirst($user['role']) ?></td>
                </tr>
                <?php endwhile; ?>
            </table>
        </div>
    </div>

    <div class="card income-summary">
        <h2>💰 Income Summary</h2>
        <div class="income-cards">
            <div class="income-card today">
                <div class="icon">📅</div>
                <h3>Today</h3>
                <p>₱<?= number_format($daily_income,2) ?></p>
            </div>
            <div class="income-card week">
                <div class="icon">📈</div>
                <h3>This Week</h3>
                <p>₱<?= number_format($weekly_income,2) ?></p>
            </div>
            <div class="income-card month">
                <div class="icon">🗓️</div>
                <h3>This Month</h3>
                <p>₱<?= number_format($monthly_income,2) ?></p>
            </div>
            <div class="income-card year">
                <div class="icon">🏆</div>
                <h3>This Year</h3>
                <p>₱<?= number_format($yearly_income,2) ?></p>
            </div>
        </div>
    </div>

</div>

<footer>
    © New Dawn Thrift | Established 2025
</footer>

<script src="admin.js?v=1"></script>
</body>
</html>
