<?php
include 'db/dbconnect.php'; // Make sure this connects to your DB

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $subject = $_POST['subject'];
    $message = $_POST['message'];
    $date_sent = date('Y-m-d H:i:s'); // auto-generate current timestamp

    $sql = "INSERT INTO messages (name, email, subject, message, date_sent)
            VALUES ('$fullname', '$email', '$subject', '$message', '$date_sent')";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Message sent successfully!'); window.location='contactus.php';</script>";
    } else {
        echo "Error: " . $conn->error;
    }

    $conn->close();
}
?>
