<?php
session_start();
include "db/dbconnect.php";  

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $conn->real_escape_string($_POST['username']);
    $address = $conn->real_escape_string($_POST['address']);
    $email = $conn->real_escape_string($_POST['email']);
    $password = $conn->real_escape_string($_POST['password']);
    $confirm = $conn->real_escape_string($_POST['confirm']);

    if ($password !== $confirm) {
        $error = "Passwords do not match.";
    } else {
        $check = $conn->query("SELECT * FROM users WHERE username='$username' OR email='$email'");
        if ($check->num_rows > 0) {
            $error = "Username or email already exists.";
        } else {
            $role = "customer";
            $sql = "INSERT INTO users (username, address, email, password, role) 
                    VALUES ('$username', '$address', '$email', '$password', '$role')";
            if ($conn->query($sql)) {
                $_SESSION['username'] = $username;
                $_SESSION['email'] = $email;
                $_SESSION['role'] = $role;
                $_SESSION['address'] = $address;
                header("Location: home.php");
                exit();
            } else {
                $error = "Something went wrong. Please try again.";
            }
        }
    }

    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register - New Dawn Thrift</title>
    <link href="https://fonts.googleapis.com/css?family=Inter:400,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="register.css">
</head>
<body>
    <div class="container">
        <div class="left-panel">
            <div class="form-wrap">
                <h2>Create Account</h2>
                <form action="register.php" method="POST" class="register-form">
                    <input type="text" name="username" placeholder="Username" required>
                    <input type="text" name="address" placeholder="Address" required>
                    <input type="email" name="email" placeholder="E-mail" required>
                    <input type="password" name="password" placeholder="Password" required>
                    <input type="password" name="confirm" placeholder="Confirm password" required>
                    <button type="submit" class="btn">REGISTER</button>
                    <?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>
                </form>
                <p class="login-link">Already have an account? <a href="login.php">Login</a></p>
            </div>
        </div>
        <div class="right-panel">
            <img src="logo.png" alt="New Dawn Thrift Logo" class="logo">
        </div>
    </div>
</body>
</html>
