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
    $sql = "INSERT INTO products (name, stock, price) VALUES ('$name', '$stock', '$price')";
    $conn->query($sql);
}

if (isset($_POST['update_stock'])) {
    $id = $_POST['product_id'];
    $change = $_POST['change'];
    $conn->query("UPDATE products SET stock = stock + $change WHERE id=$id");
}

if (isset($_POST['delete_product'])) {
    $id = $_POST['product_id'];
    $conn->query("DELETE FROM products WHERE id=$id");
}

if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: login.php");
    exit();
}

$search = isset($_GET['search']) ? $_GET['search'] : '';
$purchases = $conn->query("SELECT * FROM purchases 
    WHERE product_name LIKE '%$search%' 
    OR customer_name LIKE '%$search%' 
    OR purchase_date LIKE '%$search%' 
    ORDER BY purchase_date DESC");

$products = $conn->query("SELECT * FROM products ORDER BY id DESC");
$users = $conn->query("SELECT id, username, address, email, role FROM users ORDER BY id ASC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard - New Dawn Thrift</title>
    <link href="https://fonts.googleapis.com/css?family=Inter:400,600&display=swap" rel="stylesheet" />
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
            grid-template-rows: auto auto auto auto 1fr;
            grid-template-areas:
                "log add"
                "log delete"
                "log update"
                "log users"
                "stocks stocks";
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
        .log-book { grid-area: log; overflow-y: auto; max-height: 700px; }
        .add-product { grid-area: add; }
        .delete-product { grid-area: delete; }
        .update-product { grid-area: update; }
        .current-stock { grid-area: stocks; margin-top: 10px; }
        .user-list { grid-area: users; }
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
        .delete-btn {
            background-color: #dc2626;
        }
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
        th {
            background-color: #f3f4f6;
            color: #333;
        }
        tr:hover {
            background-color: #f9fafb;
        }
    </style>
</head>
<body>
    <div class="topbar">
        <a href="?logout=true" class="logout-btn">Logout</a>
    </div>

    <div class="dashboard">
        <div class="card log-book">
            <h2>🧾 Log Book</h2>
            <form method="GET">
                <input type="search" name="search" placeholder="Search (Name, Product, Date)" value="<?php echo $search; ?>">
                <button type="submit">Search</button>
            </form>
            <table>
                <tr>
                    <th>ID</th>
                    <th>Customer</th>
                    <th>Product</th>
                    <th>Qty</th>
                    <th>Price</th>
                    <th>Total</th>
                    <th>Date</th>
                </tr>
                <?php while($row = $purchases->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= $row['customer_name'] ?></td>
                    <td><?= $row['product_name'] ?></td>
                    <td><?= $row['quantity'] ?></td>
                    <td><?= $row['price'] ?></td>
                    <td><?= $row['total'] ?></td>
                    <td><?= $row['purchase_date'] ?></td>
                </tr>
                <?php endwhile; ?>
            </table>
        </div>

        <div class="card add-product">
            <h3>➕ Add Product</h3>
            <form method="POST">
                <input type="text" name="name" placeholder="Product Name" required>
                <input type="number" name="stock" placeholder="Stock" required>
                <input type="number" step="0.01" name="price" placeholder="Price" required>
                <button type="submit" name="add_product">Add</button>
            </form>
        </div>

        <div class="card delete-product">
            <h3>🗑️ Delete Product</h3>
            <form method="POST">
                <input type="number" name="product_id" placeholder="Enter Product ID" required>
                <button type="submit" name="delete_product" class="delete-btn">Delete</button>
            </form>
        </div>

        <div class="card update-product">
            <h3>🔄 Update Stock</h3>
            <form method="POST">
                <input type="number" name="product_id" placeholder="Enter Product ID" required>
                <input type="number" name="change" placeholder="Change (+/-)" required>
                <button type="submit" name="update_stock">Update</button>
            </form>
        </div>

        <div class="card current-stock">
            <h2>📦 Current Stock</h2>
            <table>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Stock</th>
                    <th>Price</th>
                </tr>
                <?php while($prod = $products->fetch_assoc()): ?>
                <tr>
                    <td><?= $prod['id'] ?></td>
                    <td><?= $prod['name'] ?></td>
                    <td><?= $prod['stock'] ?></td>
                    <td>₱<?= number_format($prod['price'], 2) ?></td>
                </tr>
                <?php endwhile; ?>
            </table>
        </div>

        <div class="card user-list">
            <h2>👥 User Accounts</h2>
            <table>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Address</th>
                    <th>Email</th>
                    <th>Role</th>
                </tr>
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
