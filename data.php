<?php

    include 'connect.php';

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $name = $_POST["name"];
        $id = $_POST["sId"];
        $email = $_POST["email"];
        $phone = $_POST["phone"];
        $password = $_POST["pass"];

    
        $sql = "INSERT INTO `users`(`name`, `email`, `password`, `student_id`, `phone`) 
                VALUES ('$name', '$email', '$password', '$id', '$phone')";

        if (mysqli_query($conn, $sql)) {
            header("Location: home.php");
            exit();
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    }

    mysqli_close($conn);
?>
