<?php
session_start();
include 'connect.php';

if (isset($_POST['sender_id']) && isset($_POST['receiver_id'])) {
    $sender_id = $_POST['sender_id'];
    $receiver_id = $_POST['receiver_id'];

    $query = "
        SELECT m.*, u.profile_photo 
        FROM messages m
        JOIN users u ON m.sender_id = u.user_id
        WHERE (m.sender_id = '$sender_id' AND m.receiver_id = '$receiver_id')
        OR (m.sender_id = '$receiver_id' AND m.receiver_id = '$sender_id')
        ORDER BY m.timestamp ASC
    ";

    $result = mysqli_query($conn, $query);
    $messages = [];

    while ($row = mysqli_fetch_assoc($result)) {
        $messages[] = $row;
    }

    echo json_encode($messages);
}
?>
