<?php

include 'connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve user input from form
    $name = $_POST["name"];
    $id = $_POST["sId"];
    $email = $_POST["email"];
    $phone = $_POST["phone"];
    $password = $_POST["pass"];
    $confirm_password = $_POST["confirmPass"];
    $user_type = $_POST["userType"]; // Retrieve userType from the hidden input

    // Ensure passwords match
    if ($password !== $confirm_password) {
        echo "Passwords do not match!";
        exit();
    }

    // Handle profile photo upload
    $profile_photo = NULL; // Default value if no file is uploaded
    if (isset($_FILES["profilePhoto"]) && $_FILES["profilePhoto"]["error"] == 0) {
        $target_dir = "uploads/"; // Directory where images will be stored

        // Check if the directory exists, if not, create it
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0755, true); // Create the directory with proper permissions
        }

        $target_file = $target_dir . basename($_FILES["profilePhoto"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check if the file is an actual image
        $check = getimagesize($_FILES["profilePhoto"]["tmp_name"]);
        if ($check === false) {
            echo "File is not an image.";
            exit();
        }

        // Check file size (optional limit, e.g., 5MB)
        if ($_FILES["profilePhoto"]["size"] > 5000000) {
            echo "Sorry, your file is too large.";
            exit();
        }

        // Allow only certain file formats (JPG, PNG, JPEG, GIF)
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
        if (!in_array($imageFileType, $allowed_extensions)) {
            echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            exit();
        }

        // Try to upload file
        if (move_uploaded_file($_FILES["profilePhoto"]["tmp_name"], $target_file)) {
            $profile_photo = $target_file; // Save the file path to the variable
        } else {
            echo "Sorry, there was an error uploading your file.";
            exit();
        }
    }

    // Check if it's a Bike Rider form submission
    if ($user_type == 'rider') {
        $bike_number = $_POST["bikeNumber"];
        
        // Insert into database for Bike Rider
        $sql = "INSERT INTO `users` (`name`, `email`, `password`, `student_id`, `phone`, `user_type`, `profile_photo`, `bike_number`) 
                VALUES ('$name', '$email', '$password', '$id', '$phone', '$user_type', '$profile_photo', '$bike_number')";
    
    } else {
        // Insert into database for Ride Requester
        $sql = "INSERT INTO `users` (`name`, `email`, `password`, `student_id`, `phone`, `user_type`, `profile_photo`) 
                VALUES ('$name', '$email', '$password', '$id', '$phone', '$user_type', '$profile_photo')";
    }

    // Execute the SQL query
    if (mysqli_query($conn, $sql)) {
        header("Location: home.php"); // Redirect to home page after successful registration
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

mysqli_close($conn);
?>



<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Sign in to URides</title>
  <link rel="stylesheet" href="css/regStyle.css">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <style>
    .btn {
      border-radius: 20px;
      margin-right: 10px;
    }
    .hidden {
      display: none;
    }
    .container {
      max-width: 700px;
      width: 100%;
      background-color: #fff;
      padding: 25px 30px;
      border-radius: 5px;
      box-shadow: 0 5px 10px rgba(0,0,0,0.15);
    }
    .title {
      font-size: 25px;
      font-weight: 500;
      position: relative;
    }
    .user-choice {
      display: flex;
      justify-content: space-around;
      margin: 20px 0;
    }
    .user-choice button {
      padding: 15px 30px;
      font-size: 16px;
      border: none;
      background: #f77d0a;
      color: white;
      border-radius: 5px;
      cursor: pointer;
      transition: background 0.3s ease;
    }
    .user-choice button:hover {
      background: #e56c00;
    }
    .hidden-input {
      display: none;
    }
  </style>
  <script>
    function showForm(userType) {
      // Set the hidden input value for user type
      document.getElementById('userType').value = userType;
      
      // Hide the user choice buttons
      document.getElementById('user-choice-container').classList.add('hidden');
      
      // Show the corresponding form based on user type
      if (userType === 'rider') {
        document.getElementById('rider-form').classList.remove('hidden');
      } else if (userType === 'requester') {
        document.getElementById('requester-form').classList.remove('hidden');
      }
    }
  </script>
</head>

<body>
  <div class="container">
    <div id="user-choice-container">
      <div class="title">What type of user are you?</div>
      <div class="user-choice">
        <button onclick="showForm('rider')">Sign up as a Bike Rider</button>
        <button onclick="showForm('requester')">Sign up as a Ride Requester</button>
      </div>
    </div>

    <!-- Rider Form -->
    <div id="rider-form" class="hidden">
      <div class="title">Bike Rider Registration</div>
      <div class="content">
        <form action="" name="riderForm" method="post" enctype="multipart/form-data">
          <!-- Hidden input to store user type -->
          <input type="hidden" id="userType" name="userType" value="rider">

          <div class="user-details">
            <div class="input-box">
              <span class="details">Full Name</span>
              <input type="text" name="name" placeholder="Enter your name" required>
            </div>
            <div class="input-box">
              <span class="details">Student ID</span>
              <input type="text" name="sId" placeholder="Enter your student ID" required>
            </div>
            <div class="input-box">
              <span class="details">Email</span>
              <input type="text" name="email" placeholder="Enter your email" required>
            </div>
            <div class="input-box">
              <span class="details">Phone Number</span>
              <input type="text" name="phone" placeholder="Enter your number" required>
            </div>
            <div class="input-box">
              <span class="details">Password</span>
              <input type="password" name="pass" placeholder="Enter your password" required>
            </div>
            <div class="input-box">
              <span class="details">Confirm Password</span>
              <input type="password" name="confirmPass" placeholder="Confirm your password" required>
            </div>
            <div class="input-box">
              <span class="details">Bike Number</span>
              <input type="text" name="bikeNumber" placeholder="Enter your bike number" required>
            </div>
            <div>
              <span class="details">Profile Photo</span>
              <input type="file" name="profilePhoto" accept="image/*">
            </div>
          </div>

          <div class="button">
            <input type="submit" value="Register as Bike Rider">
          </div>
        </form>
      </div>
    </div>

    <!-- Requester Form -->
    <div id="requester-form" class="hidden">
      <div class="title">Ride Requester Registration</div>
      <div class="content">
        <form action="" name="requesterForm" method="post" enctype="multipart/form-data">
          <!-- Hidden input to store user type -->
          <input type="hidden" id="userType" name="userType" value="requester">

          <div class="user-details">
            <div class="input-box">
              <span class="details">Full Name</span>
              <input type="text" name="name" placeholder="Enter your name" required>
            </div>
            <div class="input-box">
              <span class="details">Student ID</span>
              <input type="text" name="sId" placeholder="Enter your student ID" required>
            </div>
            <div class="input-box">
              <span class="details">Email</span>
              <input type="text" name="email" placeholder="Enter your email" required>
            </div>
            <div class="input-box">
              <span class="details">Phone Number</span>
              <input type="text" name="phone" placeholder="Enter your number" required>
            </div>
            <div class="input-box">
              <span class="details">Password</span>
              <input type="password" name="pass" placeholder="Enter your password" required>
            </div>
            <div class="input-box">
              <span class="details">Confirm Password</span>
              <input type="password" name="confirmPass" placeholder="Confirm your password" required>
            </div>
            <div>
              <span class="details">Profile Photo</span>
              <input type="file" name="profilePhoto" accept="image/*">
            </div>
          </div>

          <div class="button">
            <input type="submit" value="Register as Ride Requester">
          </div>
        </form>
      </div>
    </div>
  </div>
</body>
</html>
