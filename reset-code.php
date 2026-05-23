<?php

include 'connect.php';
session_start();

if (!isset($_SESSION['email'])) {
    echo "Session email not set";
}

if (isset($_POST['check-reset-otp'])) {
    $otp_code = mysqli_real_escape_string($conn, $_POST['otp']);
    $email = $_SESSION['email'];

    $check_code = "SELECT * FROM users WHERE code = $otp_code AND email = '$email'";
    $run_sql = mysqli_query($conn, $check_code);

    if (mysqli_num_rows($run_sql) > 0) {
        $_SESSION['otp-verified'] = true;
        header('location: resetPassword.php'); 
        exit();
    } else {
        $errors['otp-error'] = "Invalid OTP!";
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="utf-8">
   <title>Enter OTP</title>
   <link rel="stylesheet" href="css/loginStyle.css">
   <style>
       .container {
           display: block;
       }
        .btn {
            border-radius: 20px;
            margin-right: 10px;
        }
   </style>
</head>
<body>
   <div class="center">
      <div class="container">
         <div class="text">
            Enter OTP
         </div>
         <form action="" method="post">
            <div class="data">
               <label>OTP Code</label>
               <input type="text" name="otp" required>
            </div>
            <div class="btn">
               <div class="inner"></div>
               <button type="submit" name="check-reset-otp">Submit OTP</button>
            </div>
         </form>
         <?php
            if (!empty($errors)) {
                foreach ($errors as $error) {
                    echo "<p class='error'>$error</p>";
                }
            }
         ?>
      </div>
   </div>
</body>
</html>
