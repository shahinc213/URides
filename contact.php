<?php
    session_start();
    include 'connect.php'; 

    if(!isset($_SESSION["user_id"])){
        header("Location: login.php");
    }


    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $subject = $_POST['subject'];
        $message = $_POST['message'];

        $query = "INSERT INTO contacts (name, email, subject, message) VALUES ('$name', '$email', '$subject', '$message')";
        if (mysqli_query($conn, $query)) {
            echo "<script>alert('Your message has been sent successfully!');</script>";
        } else {
            echo "<script>alert('Error: " . mysqli_error($conn) . "');</script>";
        }
    }

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>URides</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="Free HTML Templates" name="keywords">
    <meta content="Free HTML Templates" name="description">

     <!-- Profile Dropdown Menu -->
     <link rel="stylesheet" href="css/profileStyle.css">
    <script src="js/script.js" defer></script>
    <!-- Logo-->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet">


    <!-- Favicon -->
    <link href="img/favicon.ico" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@400;500;600;700&family=Rubik&display=swap" rel="stylesheet"> 

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.0/css/all.min.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css" rel="stylesheet" />

    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="css/style.css" rel="stylesheet">
    <style>
        .btn {
            border-radius: 20px;
            margin-right: 10px;
        }
    </style>
</head>

<body>
    <!-- Topbar Start -->
    <?php
        include 'topbar.php';
    ?>
    <!-- Topbar End -->


    <!-- Navbar Start -->
    <div class="container-fluid position-relative nav-bar p-0">
        <div class="position-relative px-lg-5" style="z-index: 9;">
            <nav class="navbar navbar-expand-lg bg-secondary navbar-dark py-3 py-lg-0 pl-3 pl-lg-5">
                <a href="home.php" class="navbar-brand">
                    <h1 class="text-uppercase text-primary mb-1">URides</h1>
                </a>
                <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#navbarCollapse">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse justify-content-between px-3" id="navbarCollapse">
                    <div class="navbar-nav ml-auto py-0">
                        <a href="home.php" class="nav-item nav-link">Home</a>
                        <a href="about.php" class="nav-item nav-link">About</a>
                        <a href="chat_inbox.php" class="nav-item nav-link">Messages</a>
                        <a href="requests.php" class="nav-item nav-link">Requests</a>
                        <div class="nav-item dropdown">
                            <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">Services</a>
                            <div class="dropdown-menu rounded-0 m-0">
                                <a href="rideShare.php" class="dropdown-item">Request a Ride</a>
                                <a href="shuttle.php" class="dropdown-item">Shuttle</a>
                                <a href="booking.php" class="dropdown-item">Cycle Rental</a>
                            </div>
                        </div>
                        <a href="contact.php" class="nav-item nav-link active">Contact</a>
                       
                        <!-- <a href="logout.php" class="nav-item nav-link">Logout</a> -->

                        <!-- Profile Dropdown Menu -->
                        <?php
                            include 'profileDropdown.php';
                        ?>
                        <!-- End Profile Dropdown Menu -->

                    </div>
                </div>
            </nav>
        </div>
    </div>
    <!-- Navbar End -->

        <!-- JavaScript to handle submenu toggling -->
        <script>
        document.getElementById('settingsToggle').addEventListener('click', function() {
            var settingsSubMenu = document.getElementById('settingsSubMenu');
            settingsSubMenu.classList.toggle('d-none'); // Toggle visibility
        });

        document.getElementById('helpToggle').addEventListener('click', function() {
            var helpSubMenu = document.getElementById('helpSubMenu');
            helpSubMenu.classList.toggle('d-none'); // Toggle visibility
        });
    </script>



    <!-- Page Header Start -->
    <div class="container-fluid page-header">
        <h1 class="display-3 text-uppercase text-white mb-3">Contact</h1>
        <div class="d-inline-flex text-white">
            <h6 class="text-uppercase m-0"><a class="text-white" href="home.php">Home</a></h6>
            <h6 class="text-body m-0 px-3">/</h6>
            <h6 class="text-uppercase text-body m-0">Contact</h6>
        </div>
    </div>
    <!-- Page Header Start -->


    <!-- Contact Start -->
    <div class="container-fluid py-5">
        <div class="container pt-5 pb-3">
            <h1 class="display-4 text-uppercase text-center mb-5">Contact Us</h1>
            <div class="row">
                <div class="col-lg-7 mb-2">
                    <div class="contact-form bg-light mb-4" style="padding: 30px;">
                        <form method="POST" action="">
                            <div class="row">
                                <div class="col-6 form-group">
                                    <input type="text" class="form-control p-4" name="name" placeholder="Your Name" required="required">
                                </div>
                                <div class="col-6 form-group">
                                    <input type="email" class="form-control p-4" name="email" placeholder="Your Email" required="required">
                                </div>
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control p-4" name="subject" placeholder="Subject" required="required">
                            </div>
                            <div class="form-group">
                                <textarea class="form-control py-3 px-4" rows="5" name="message" placeholder="Message" required="required"></textarea>
                            </div>
                            <div>
                                <button class="btn btn-primary py-3 px-5" type="submit">Send Message</button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-lg-5 mb-2">
                    <div class="bg-secondary d-flex flex-column justify-content-center px-5 mb-4" style="height: 435px;">
                        <div class="d-flex mb-3">
                            <i class="fa fa-2x fa-map-marker-alt text-primary flex-shrink-0 mr-3"></i>
                            <div class="mt-n1 text-left">
                                <h5 class="text-light">Head Office</h5>
                                <p>Madanee Avenue, United City</p>
                            </div>
                        </div>
                        <div class="d-flex mb-3">
                            <i class="fa fa-2x fa-envelope-open text-primary flex-shrink-0 mr-3"></i>
                            <div class="mt-n1 text-left">
                                <h5 class="text-light">Customer Service</h5>
                                <p>uridesoficials@gmail.com</p>
                            </div>
                        </div>
                        <div class="d-flex">
                            <i class="fa fa-2x fa-envelope-open text-primary flex-shrink-0 mr-3"></i>
                            <div class="mt-n1 text-left">
                                <h5 class="text-light">Return & Refund</h5>
                                <p class="m-0">refund@example.com</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Contact End -->



    <!-- Footer Start -->
    <?php
        include 'footer.php';
    ?>
    <!-- Footer End -->


    <!-- Back to Top -->
    <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="fa fa-angle-double-up"></i></a>


    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>
    <script src="lib/tempusdominus/js/moment.min.js"></script>
    <script src="lib/tempusdominus/js/moment-timezone.min.js"></script>
    <script src="lib/tempusdominus/js/tempusdominus-bootstrap-4.min.js"></script>

    <!-- Template Javascript -->
    <script src="js/main.js"></script>
</body>

</html>

<script type="text/javascript" src="js/script.js"></script>