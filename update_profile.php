<?php
session_start();
include 'connect.php'; // Ensure your database connection

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Initialize variables
$name = trim($_POST['name']);
$phone = trim($_POST['phone']);
$profile_photo = $_FILES['profile_photo'];

// Validate inputs
if (empty($name) || empty($phone)) {
    echo "<script>alert('Name and Phone are required.'); window.history.back();</script>";
    exit();
}

// Prepare the SQL statement to update user information
$update_query = "UPDATE users SET name=?, phone=? WHERE user_id=?";
$stmt = $conn->prepare($update_query);
$stmt->bind_param("ssi", $name, $phone, $user_id);

if ($stmt->execute()) {
    // Check if a new profile photo was uploaded
    if ($profile_photo['error'] == UPLOAD_ERR_OK) {
        $upload_dir = 'uploads/';
        $file_name = basename($profile_photo['name']);
        $target_file = $upload_dir . $file_name;

        // Move the uploaded file to the designated directory
        if (move_uploaded_file($profile_photo['tmp_name'], $target_file)) {
            // Update the profile photo path in the database
            $update_photo_query = "UPDATE users SET profile_photo=? WHERE user_id=?";
            $photo_stmt = $conn->prepare($update_photo_query);
            $photo_stmt->bind_param("si", $file_name, $user_id);
            $photo_stmt->execute();
        } else {
            echo "<script>alert('Error uploading the profile photo.'); window.history.back();</script>";
            exit();
        }
    }

    // Update session variables
    $_SESSION['name'] = $name;
    $_SESSION['phone'] = $phone;
    if (isset($file_name)) {
        $_SESSION['profile_photo'] = $file_name;
    }

    echo "<script>alert('Profile updated successfully.'); window.location.href = 'chat_inbox.php';</script>";
} else {
    echo "<script>alert('Error updating profile: " . $stmt->error . "'); window.history.back();</script>";
}

// Close the prepared statements and database connection
$stmt->close();
$conn->close();
?>
