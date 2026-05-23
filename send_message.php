<?php
session_start();
include 'connect.php';

if (!isset($_SESSION["user_id"])) {
    die("Unauthorized access");
}

$loggedInUserId = $_SESSION["user_id"];
$receiverId = $_POST['receiver_id'];
$message = mysqli_real_escape_string($conn, $_POST['message']);

// Insert the message into the messages table
$insertQuery = "
    INSERT INTO messages (sender_id, receiver_id, message)
    VALUES ('$loggedInUserId', '$receiverId', '$message')
";
if (mysqli_query($conn, $insertQuery)) {
    echo "Message sent";
} else {
    echo "Error: " . mysqli_error($conn);
}
?>
