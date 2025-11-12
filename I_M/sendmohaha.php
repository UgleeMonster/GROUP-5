<?php
include 'db_connect.php';  

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $subject = $_POST['subject'];
    $message = $_POST['message'];
    $date_sent = date('Y-m-d H:i:s');  

    $sql = "INSERT INTO messages (fullname, email, subject, message, date_sent)
            VALUES ('$fullname', '$email', '$subject', '$message', '$date_sent')";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Message sent successfully!'); window.location='contactus.php';</script>";
    } else {
        echo "Error: " . $conn->error;
    }

    $conn->close();
}
?>
