<?php

include 'connect.php';

if (isset($_POST['submit'])) {
    $email = $_POST['email'];
    $password = $_POST['pass'];


    $sql = "SELECT * FROM users WHERE email = '$email' AND password = '$password'";
    $result = mysqli_query($conn, $sql);

    $num = mysqli_num_rows($result);
    if ($num == 1) {

        $user = mysqli_fetch_assoc($result);

        session_start();

        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['name'] = $user['name'];
        $_SESSION['phone'] = $user['phone'];
        $_SESSION['profile_photo'] = $user['profile_photo'];
        $_SESSION['user_type'] = $user['user_type'];


        header("Location: home.php");
        exit();
        
    } else {
        header("Location: login.php");
        exit();;
    }

    mysqli_close($conn);
}
?>
