<?php
session_start();
include "db/dbconnect.php";

if (!isset($_SESSION['username'])) {
    http_response_code(403);
    echo "Not logged in";
    exit();
}

$username = $_SESSION['username'];

if (isset($_POST['cart_id']) && isset($_POST['quantity'])) {
    $cart_id = intval($_POST['cart_id']);
    $quantity = intval($_POST['quantity']);

    if ($quantity < 1) $quantity = 1;  

    $stmt = $conn->prepare("UPDATE cart SET quantity=? WHERE id=? AND username=?");
    $stmt->bind_param("iis", $quantity, $cart_id, $username);
    $stmt->execute();
    $stmt->close();

    echo "Quantity updated";
} else {
    http_response_code(400);
    echo "Invalid request";
}
?>
