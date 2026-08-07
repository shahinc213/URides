<?php
session_start();
include 'connect.php';

if (!isset($_SESSION['user_id']) || !isset($_POST['receiver_id']) || !isset($_POST['message'])) {
    header("Location: chat_inbox.php");
    exit();
}

$sender_id = $_SESSION['user_id'];
$receiver_id = $_POST['receiver_id'];
$message = mysqli_real_escape_string($conn, $_POST['message']);

$query = "INSERT INTO messages (sender_id, receiver_id, message) VALUES ('$sender_id', '$receiver_id', '$message')";

if (mysqli_query($conn, $query)) {
    header("Location: chat_inbox.php?user_id=$receiver_id");
} else {
    echo "Error: " . mysqli_error($conn);
}
?>
