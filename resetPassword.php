<?php
session_start();
include 'connect.php';

if (!isset($_SESSION['otp-verified'])) {
    header('location: forgotPassword.php');
    exit();
}

if (isset($_POST['reset-password'])) {
    $new_password = mysqli_real_escape_string($conn, $_POST['new_password']);
    $confirm_password = mysqli_real_escape_string($conn, $_POST['confirm_password']);
    $email = $_SESSION['email'];

    if ($new_password === $confirm_password) {
        
        $update_password = "UPDATE users SET password = '$new_password', code = NULL WHERE email = '$email'";
        $run_query = mysqli_query($conn, $update_password);

        if ($run_query) {
            session_unset();
            session_destroy();
            header('location: login.php');
            exit();
        } else {
            $errors['db-error'] = "Failed to update password!";
        }
    } else {
        $errors['password'] = "Passwords do not match!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="utf-8">
   <title>Reset Password</title>
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
            Reset Password
         </div>
         <form action="" method="post">
            <div class="data">
               <label>New Password</label>
               <input type="password" name="new_password" required>
            </div>
            <div class="data">
               <label>Confirm Password</label>
               <input type="password" name="confirm_password" required>
            </div>
            <div class="btn">
               <div class="inner"></div>
               <button type="submit" name="reset-password">Reset Password</button>
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
