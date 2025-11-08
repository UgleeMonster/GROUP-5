<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

include "db/dbconnect.php";

// Add product
if (isset($_POST['add_product'])) {
    $name = $_POST['name'];
    $stock = $_POST['stock'];
    $price = $_POST['price'];
    $category = $_POST['category'];
    $image = $_POST['image'];
    $sql = "INSERT INTO products (name, stock, price, category, image) VALUES ('$name', '$stock', '$price', '$category', '$image')";
    $conn->query($sql);
}

// Update stock
if (isset($_POST['update_stock'])) {
    $id = $_POST['product_id'];
    $change = $_POST['change'];
    $conn->query("UPDATE products SET stock = stock + $change WHERE id=$id");
}

// Delete product
if (isset($_POST['delete_product'])) {
    $id = $_POST['product_id'];
    $conn->query("DELETE FROM products WHERE id=$id");
}

if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: login.php");
    exit();
}

// Search
$search = isset($_GET['search']) ? $_GET['search'] : '';
$purchases = $conn->query("SELECT * FROM purchases 
    WHERE product_name LIKE '%$search%' 
    OR customer_name LIKE '%$search%' 
    OR purchase_date LIKE '%$search%' 
    ORDER BY purchase_date DESC");

// Products
$products = $conn->query("SELECT * FROM products ORDER BY id ASC");

// Users
$users = $conn->query("SELECT id, username, address, email, role FROM users ORDER BY id ASC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard - New Dawn Thrift</title>
    <link href="https://fonts.googleapis.com/css?family=Inter:400,500,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f3f4f6;
            margin: 0;
            padding: 0;
        }

        .topbar {
            display: flex;
            justify-content: flex-end;
            align-items: center;
            background: #1f2937;
            padding: 12px 25px;
        }

        .logout-btn {
            text-decoration: none;
            color: #f87171;
            font-weight: bold;
            background: #fff;
            padding: 6px 12px;
            border-radius: 6px;
            transition: 0.2s;
        }

        .logout-btn:hover {
            background: #f87171;
            color: white;
        }

        .dashboard {
            display: grid;
            grid-template-columns: 1.2fr 0.8fr;
            grid-template-rows: auto auto;
            grid-template-areas:
                "stock log"
                "users log";
            gap: 20px;
            padding: 25px;
        }

        .card {
            background: #fff;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        h2, h3 {
            margin-top: 0;
            color: #1f2937;
        }

        .current-stock { grid-area: stock; }
        .user-list { grid-area: users; }
        .log-inbox-wrapper { grid-area: log; display: flex; flex-direction: column; gap: 20px; }
        .log-book { overflow-y: auto; max-height: 400px; }
        .inbox-messages { max-height: 300px; overflow-y: auto; }

        input[type="text"], input[type="number"], input[type="search"] {
            width: 100%;
            padding: 8px;
            margin: 6px 0;
            border: 1px solid #ccc;
            border-radius: 6px;
        }

        button {
            background-color: #1f2937;
            color: white;
            padding: 8px 12px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
        }

        button:hover { background-color: #111827; }
        .delete-btn { background-color: #dc2626; }
        .delete-btn:hover { background-color: #b91c1c; }

        table {
            width: 100%;
            border-collapse: collapse;
            border-radius: 8px;
            overflow: hidden;
            margin-top: 10px;
        }

        th, td {
            text-align: left;
            padding: 10px 12px;
            border-bottom: 1px solid #eee;
        }

        th { background-color: #f3f4f6; color: #333; }
        tr:hover { background-color: #f9fafb; }

        .flex-forms {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
        }

        .flex-forms form {
            flex: 1;
            min-width: 180px;
            max-width: 250px;
        }

        .product-img {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 8px;
        }

        .category-badge {
            background-color: #D4AF37;
            color: #fff;
            padding: 3px 8px;
            border-radius: 10px;
            font-size: 12px;
            font-weight: 500;
        }
    </style>
</head>
<body>
    <div class="topbar">
        <a href="?logout=true" class="logout-btn">Logout</a>
    </div>

    <div class="dashboard">
        <!-- PRODUCT MANAGEMENT -->
        <div class="card current-stock">
            <h2>📦 Product Management + Stock</h2>

            <div class="flex-forms">
                <form method="POST">
                    <h3>Add Product</h3>
                    <input type="text" name="name" placeholder="Product Name" required>
                    <input type="number" name="stock" placeholder="Stock" required>
                    <input type="number" step="0.01" name="price" placeholder="Price" required>
                    <input type="text" name="category" placeholder="Category" required>
                    <input type="text" name="image" placeholder="Image filename" required>
                    <button type="submit" name="add_product">Add</button>
                </form>

                <form method="POST">
                    <h3>Update Stock</h3>
                    <input type="number" name="product_id" placeholder="Product ID" required>
                    <input type="number" name="change" placeholder="Change (+/-)" required>
                    <button type="submit" name="update_stock">Update</button>
                </form>

                <form method="POST">
                    <h3>Delete Product</h3>
                    <input type="number" name="product_id" placeholder="Product ID" required>
                    <button type="submit" name="delete_product" class="delete-btn">Delete</button>
                </form>
            </div>

            <h3 style="margin-top: 20px;">Current Stock List</h3>
            <table>
                <tr><th>ID</th><th>Image</th><th>Name</th><th>Category</th><th>Stock</th><th>Price</th></tr>
                <?php while($prod = $products->fetch_assoc()): ?>
                <tr>
                    <td><?= $prod['id'] ?></td>
                    <td><img src="<?= $prod['image'] ?>" class="product-img" alt="<?= $prod['name'] ?>"></td>
                    <td><?= $prod['name'] ?></td>
                    <td><span class="category-badge"><?= ucfirst($prod['category']) ?></span></td>
                    <td><?= $prod['stock'] ?></td>
                    <td>₱<?= number_format($prod['price'], 2) ?></td>
                </tr>
                <?php endwhile; ?>
            </table>
        </div>

        <!-- LOGBOOK + INBOX -->
<div class="log-inbox-wrapper">
    <div class="card log-book">
        <h2>🧾 Log Book</h2>
        <form method="GET">
            <input type="search" name="search" placeholder="Search (Customer, Product, Date)" value="<?= htmlspecialchars($search); ?>">
            <button type="submit">Search</button>
        </form>
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
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <div class="card inbox-messages">
        <h2>📩 Inbox Messages</h2>
        <table>
            <thead>
                <tr><th>ID</th><th>Name</th><th>Email</th><th>Subject</th><th>Message</th><th>Date Sent</th></tr>
            </thead>
            <tbody>
            <?php
            $messages = $conn->query("SELECT * FROM messages ORDER BY date_sent DESC");
            while($msg = $messages->fetch_assoc()):
            ?>
                <tr>
                    <td><?= $msg['id'] ?></td>
                    <td><?= htmlspecialchars($msg['name']) ?></td>
                    <td><?= htmlspecialchars($msg['email']) ?></td>
                    <td><?= htmlspecialchars($msg['subject']) ?></td>
                    <td><?= htmlspecialchars($msg['message']) ?></td>
                    <td><?= $msg['date_sent'] ?></td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

            <div class="card inbox-messages">
                <h2>📩 Inbox Messages</h2>
                <table>
                    <tr><th>ID</th><th>Name</th><th>Email</th><th>Subject</th><th>Message</th><th>Date Sent</th></tr>
                    <?php
                    $messages = $conn->query("SELECT * FROM messages ORDER BY date_sent DESC");
                    while($msg = $messages->fetch_assoc()):
                    ?>
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

        <!-- USERS -->
        <div class="card user-list">
            <h2>👥 User Accounts</h2>
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
</body>
</html>
