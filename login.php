<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="utf-8">
   <title>Login Form</title>
   <link rel="stylesheet" href="css/loginStyle.css">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
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
         <label class="close-btn fas fa-times" title="close" onclick="goToHomePage()"></label>
         <div class="text">
            Login
         </div>
         <form action="check.php" name="myForm" method="post">
            <div class="data">
               <label>Email</label>
               <input type="text" name="email" id="eId">
               <!-- <span class="sclass">Error</span> -->
            </div>
            <div class="data">
               <label>Password</label>
               <input type="password" name="pass" id="pId" required>
               <!-- <span class="sclass">Error</span> -->
            </div>
            <div class="forgot-pass">
               <a href="forgotPassword.php">Forgot Password?</a>
            </div>
            <div class="btn">
               <div class="inner"></div>
               <button type="submit" name="submit" >login</button>
            </div>
            <div class="signup-link">
               Not a member? <a href="signIn.php">Signup now</a>
            </div>
         </form>
      </div>
   </div>

   <script>
      function goToHomePage() {
         window.location.href = "index.php";
      }
   </script>
</body>

<!-- <script>
   function getError(id, Error) {

   }
   function validate(){
      var emaill = document.forms["myForm"]["email"].value;
      console.log(emaill);
      return false;
   }

</script> -->

</html>
