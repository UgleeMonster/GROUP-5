<?php
session_start();
if (isset($_GET['logout'])) {
    session_unset();
    session_destroy();
    header("Location: login.php");
    exit();
}

include "db/dbconnect.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $conn->real_escape_string($_POST['username']);
    $password = $conn->real_escape_string($_POST['password']);

    $sql = "SELECT * FROM users WHERE username='$username' AND password='$password'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];

        if ($user['role'] == 'admin') {
            header("Location: admin.php");
        } else {
            header("Location: home.php");
        }
        exit();
    } else {
        $error = "Invalid username or password";
    }

    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - New Dawn Thrift</title>
    <link href="https://fonts.googleapis.com/css?family=Inter:400,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="design.css">
</head>
<body>
    <div class="container">
        <div class="left-panel">
            <img src="logo.png" alt="New Dawn Thrift Logo" class="logo">
        </div>

        <div class="right-panel">
            <h2>Log in to your Account</h2>

            <div class="social-login">
                <a href="https://l.instagram.com/?u=https%3A%2F%2Fwww.facebook.com%2Fshare%2F15senCad1W%2F%3Fmibextid%3DwwXIfr&e=AT0gTaGirLmnt9Bw3QOeqU02cKTa3xht2UeE_s2wnGG3a9h5hIfw9uj5CGdSXplMTjwPofj1tqa89blfEOj_cOArEjzcuIGHbEbMcp_lrT1xoNjcPEpGvotpKg" target="_blank">
                    <img src="facebook.png" alt="Facebook">
                </a>
                <a href="https://www.tiktok.com/@new.dawn.thrift?is_from_webapp=1&sender_device=pc" target="_blank">
                    <img src="tiktok.png" alt="Tiktok">
                </a>
                <a href="https://www.instagram.com/newdawnthrift?utm_source=ig_web_button_share_sheet&igsh=NHJ1dDFxdnhkNnVv" target="_blank">
                    <img src="instagram.png" alt="Instagram">
                </a>
            </div>

            <form action="login.php" method="POST" class="login-form">
                <input type="text" name="username" placeholder="Username" required>
                <input type="password" name="password" placeholder="Password" required>

                <div class="options">
                    <label><input type="checkbox" name="remember"> Remember me</label>
                    <a href="#">Forgot password?</a>
                </div>

                <button type="submit" class="btn">LOG IN</button>

                <?php if (!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>
            </form>

            <p class="signup"><a href="register.php">Create an account</a></p>
            <?php if(isset($_SESSION['username'])): ?>
                <p><a href="login.php?logout=1"></a></p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
