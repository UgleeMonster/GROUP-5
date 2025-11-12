<?php
session_start();
include "db/dbconnect.php";

if (!isset($_SESSION['username'])) {
    echo json_encode(['status' => 'error', 'message' => 'Not logged in']);
    exit();
}

if (isset($_POST['product_id'])) {
    $username = $_SESSION['username'];
    $product_id = intval($_POST['product_id']);

     
    $check = $conn->query("SELECT * FROM cart WHERE username='$username' AND product_id=$product_id");
    if ($check->num_rows > 0) {
        $conn->query("UPDATE cart SET quantity = quantity + 1 WHERE username='$username' AND product_id=$product_id");
    } else {
        $conn->query("INSERT INTO cart (username, product_id, quantity) VALUES ('$username', $product_id, 1)");
    }

    echo json_encode(['status' => 'success', 'message' => 'Product added to cart']);
    exit();
}

echo json_encode(['status' => 'error', 'message' => 'No product specified']);
