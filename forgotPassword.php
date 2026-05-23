<?php

include 'connect.php';
session_start();  

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'mail/Exception.php';
require 'mail/PHPMailer.php';
require 'mail/SMTP.php';

if (isset($_POST['check-email'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    
    $check_email = "SELECT * FROM users WHERE email='$email'";
    $sql = mysqli_query($conn, $check_email);

    if (mysqli_num_rows($sql) > 0) {
        $code = rand(111111, 999999);
        $insert_code = "UPDATE users SET code = $code WHERE email = '$email'";
        $run_query = mysqli_query($conn, $insert_code);

        if ($run_query) {
            $_SESSION['email'] = $email;

            // Email the OTP to the user using PHPMailer
            $mail = new PHPMailer(true);
            try {
                // Server settings for SMTP
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'mubasshirahmed263@gmail.com';
                $mail->Password = 'xeen nwrp mclu mqcf';
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
                $mail->Port = 465;

                // Recipients
                $mail->setFrom('mubasshirahmed263@gmail.com', 'Admin');
                $mail->addAddress($email);

                // Email content
                $mail->isHTML(true);
                $mail->Subject = 'Password Reset Code';
                $mail->Body = 'Your password reset code is ' . $code;
                $mail->AltBody = 'Your password reset code is ' . $code;

                // Send the email
                $mail->send();

                header('location: reset-code.php');
                exit();  
            } catch (Exception $e) {
                $errors['email'] = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }
        } else {
            $errors['db-error'] = "Failed to insert the reset code in the database.";
        }
    } else {
        $errors['email'] = "This email address does not exist!";
    }
}

mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="utf-8">
   <title>Forgot Password</title>
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
         <div class="text">Forgot Password</div>
         <form action="" method="post">
            <div class="data">
               <label>Email</label>
               <input type="email" name="email" required>
            </div>
            <div class="btn">
               <div class="inner"></div>
               <button type="submit" name="check-email">Send OTP</button>
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
