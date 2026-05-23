<?php
    session_start();
    include 'connect.php';

    if(!isset($_SESSION["user_id"])){
        header("Location: login.php");
        exit();
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
    <link href="css/profileStyle.css" rel="stylesheet">
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
                        <a href="home.php" class="nav-item nav-link active">Home</a>
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

                        <a href="contact.php" class="nav-item nav-link">Contact</a>

                        <!-- Profile Dropdown Menu -->
                        <?php
                            include 'profileDropdown.php';
                        ?>
                        <!-- End Profile Dropdown Menu -->

                        <!-- <a href="logout.php" class="nav-item nav-link">Logout</a> -->
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
        <h1 class="display-3 text-uppercase text-white mb-3">Home</h1>
        <div class="d-inline-flex text-white">
            <h6 class="text-uppercase m-0"><a class="text-white" href="home.php">Home</a></h6>
            <h6 class="text-body m-0 px-3">/</h6>
            <h6 class="text-uppercase text-body m-0">Home</h6>
        </div>
    </div>
    <!-- Page Header Start -->


    <!-- Carousel Start -->
    <!-- <div class="container-fluid p-0" style="margin-bottom: 90px;">
        <div id="header-carousel" class="carousel slide" data-ride="carousel">
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img class="w-100" src="img/carousel-1.jpg" alt="Image">
                    <div class="carousel-caption d-flex flex-column align-items-center justify-content-center">
                        <div class="p-3" style="max-width: 900px;">
                            <h4 class="text-white text-uppercase mb-md-3">Share your Ride</h4>
                            <h1 class="display-1 text-white mb-md-4">Easy Rides, Every Day</h1>
                            <a href="#" class="btn btn-primary py-md-3 px-md-5 mt-2">Reserve Now</a>
                        </div>
                    </div>
                </div>
                <div class="carousel-item">
                    <img class="w-100" src="img/carousel-2.jpg" alt="Image">
                    <div class="carousel-caption d-flex flex-column align-items-center justify-content-center">
                        <div class="p-3" style="max-width: 900px;">
                            <h4 class="text-white text-uppercase mb-md-3">Share your Ride</h4>
                            <h1 class="display-1 text-white mb-md-4">Effortless Rides, Anytime</h1>
                            <a href="#" class="btn btn-primary py-md-3 px-md-5 mt-2">Reserve Now</a>
                        </div>
                    </div>
                </div>
            </div>
            <a class="carousel-control-prev" href="#header-carousel" data-slide="prev">
                <div class="btn btn-dark" style="width: 45px; height: 45px;">
                    <span class="carousel-control-prev-icon mb-n2"></span>
                </div>
            </a>
            <a class="carousel-control-next" href="#header-carousel" data-slide="next">
                <div class="btn btn-dark" style="width: 45px; height: 45px;">
                    <span class="carousel-control-next-icon mb-n2"></span>
                </div>
            </a>
        </div>
    </div> -->
    <!-- Carousel End -->


    <!-- About Start -->
    <div class="container-fluid">
        <div class="container pt-5 pb-3">
            <h1 class="display-1 text-primary text-center">01</h1>
            <h1 class="display-4 text-uppercase text-center mb-5">Welcome To <span class="text-primary">URides</span></h1>
            <div class="row justify-content-center">
                <div class="col-lg-10 text-center">
                    <img class="w-75 mb-4" src="img/about1.jpg" alt="">
                    <p>Welcome to URides, your go-to platform for seamless and sustainable university commuting. Our mission is to make transportation between Notunbazar and the campus more accessible, affordable, and eco-friendly for students. We provide a comprehensive solution that includes ride-sharing for rickshaws and bikes, efficient shuttle bus services with real-time tracking, and emergency assistance for vehicle breakdowns. At URides, we believe in fostering a connected student community while promoting environmentally responsible travel. Join us in making your daily commute smarter, greener, and hassle-free.</p>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-lg-4 mb-2">
                    <div class="d-flex align-items-center bg-light p-4 mb-4" style="height: 150px;">
                        <div class="d-flex align-items-center justify-content-center flex-shrink-0 bg-primary ml-n4 mr-4" style="width: 100px; height: 100px;">
                            <i class="fa fa-2x fa-headset text-secondary"></i>
                        </div>
                        <h4 class="text-uppercase m-0">Ride Share on University Hours</h4>
                    </div>
                </div>
                <div class="col-lg-4 mb-2">
                    <div class="d-flex align-items-center bg-secondary p-4 mb-4" style="height: 150px;">
                        <div class="d-flex align-items-center justify-content-center flex-shrink-0 bg-primary ml-n4 mr-4" style="width: 100px; height: 100px;">
                            <i class="fa fa-2x fa-car text-secondary"></i>
                        </div>
                        <h4 class="text-light text-uppercase m-0">Take a Cycle Anytime</h4>
                    </div>
                </div>
                <div class="col-lg-4 mb-2">
                    <div class="d-flex align-items-center bg-light p-4 mb-4" style="height: 150px;">
                        <div class="d-flex align-items-center justify-content-center flex-shrink-0 bg-primary ml-n4 mr-4" style="width: 100px; height: 100px;">
                            <i class="fa fa-2x fa-map-marker-alt text-secondary"></i>
                        </div>
                        <h4 class="text-uppercase m-0">Shuttle Live Locations</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- About End -->


    <!-- Services Start -->
    <div class="container-fluid">
        <div class="container pt-5 pb-3">
            <h1 class="display-1 text-primary text-center">02</h1>
            <h1 class="display-4 text-uppercase text-center mb-5">Our Services</h1>
            <div class="row">
                <div class="col-lg-6 col-md-6 mb-2">
                    <div class="service-item d-flex flex-column justify-content-center px-4 mb-4">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div class="d-flex align-items-center justify-content-center bg-primary ml-n4" style="width: 80px; height: 80px;">
                                <i class="fa fa-2x fa-taxi text-secondary"></i>
                            </div>
                            <h1 class="display-2 text-white mt-n2 m-0">01</h1>
                        </div>
                        <h4 class="text-uppercase mb-3">Ride Share</h4>
                        <p class="m-0">Our Ride Sharing feature is designed to make your commute more affordable and convenient. With URides, students can easily connect with others traveling the same route, allowing them to share rides and split the fare. Whether you're heading to class or returning home, Ride Sharing offers a flexible, budget-friendly way to travel with your peers.</p>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 mb-2">
                    <div class="service-item d-flex flex-column justify-content-center px-4 mb-4">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div class="d-flex align-items-center justify-content-center bg-primary ml-n4" style="width: 80px; height: 80px;">
                                <i class="fa fa-2x fa-taxi text-secondary"></i>
                            </div>
                            <h1 class="display-2 text-white mt-n2 m-0">02</h1>
                        </div>
                        <h4 class="text-uppercase mb-3">Shuttle Service</h4>
                        <p class="m-0">Our Shuttle Service feature provides students with reliable transportation between key locations. With real-time GPS tracking, you can easily plan your commute, knowing exactly when the shuttle will arrive. This service ensures a safe, timely, and efficient travel experience, making your journey to and from campus stress-free.</p>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 mb-2">
                    <div class="service-item d-flex flex-column justify-content-center px-4 mb-4">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div class="d-flex align-items-center justify-content-center bg-primary ml-n4" style="width: 80px; height: 80px;">
                                <i class="fa fa-2x fa-car text-secondary"></i>
                            </div>
                            <h1 class="display-2 text-white mt-n2 m-0">03</h1>
                        </div>
                        <h4 class="text-uppercase mb-3">Cycle Rental</h4>
                        <p class="m-0">Our Cycle Rental feature offers a convenient and eco-friendly way to travel between Notunbazar and the campus. Students can easily rent bikes from designated stations, using their student ID for verification. This service promotes sustainable commuting while providing a quick and flexible transportation option for short-distance travel.</p>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 mb-2">
                    <div class="service-item d-flex flex-column justify-content-center px-4 mb-4">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div class="d-flex align-items-center justify-content-center bg-primary ml-n4" style="width: 80px; height: 80px;">
                                <i class="fa fa-2x fa-cogs text-secondary"></i>
                            </div>
                            <h1 class="display-2 text-white mt-n2 m-0">04</h1>
                        </div>
                        <h4 class="text-uppercase mb-3">Emergency Assistance</h4>
                        <p class="m-0">Our Emergency Assistance feature provides quick help for bike or car breakdowns. With map integration, students can locate nearby garages, access stored garage information in our database, and directly communicate with mechanics. This ensures immediate support, minimizing delays and enhancing safety during your commute.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Services End -->



    <!-- Team Start -->
    <div class="container-fluid">
        <div class="container py-5">
            <h1 class="display-1 text-primary text-center">03</h1>
            <h1 class="display-4 text-uppercase text-center mb-5">Meet Our Team</h1>
            <div class="owl-carousel team-carousel position-relative" style="padding: 0 30px;">
                <div class="team-item">
                    <img class="img-fluid w-100" src="img/arnab.jpeg" alt="">
                    <div class="position-relative py-4">
                        <h4 class="text-uppercase">Mubasshir Ahmed</h4>
                        <p class="m-0">Student</p>
                        <div class="team-social position-absolute w-100 h-100 d-flex align-items-center justify-content-center">
                            <a class="btn btn-lg btn-primary btn-lg-square mx-1" href="#"><i class="fab fa-twitter"></i></a>
                            <a class="btn btn-lg btn-primary btn-lg-square mx-1" href="#"><i class="fab fa-facebook-f"></i></a>
                            <a class="btn btn-lg btn-primary btn-lg-square mx-1" href="#"><i class="fab fa-linkedin-in"></i></a>
                        </div>
                    </div>
                </div>
                <div class="team-item">
                    <img class="img-fluid w-100" src="img/arafath.jpeg" alt="">
                    <div class="position-relative py-4">
                        <h4 class="text-uppercase">Ahasan Arafath</h4>
                        <p class="m-0">Student</p>
                        <div class="team-social position-absolute w-100 h-100 d-flex align-items-center justify-content-center">
                            <a class="btn btn-lg btn-primary btn-lg-square mx-1" href="#"><i class="fab fa-twitter"></i></a>
                            <a class="btn btn-lg btn-primary btn-lg-square mx-1" href="#"><i class="fab fa-facebook-f"></i></a>
                            <a class="btn btn-lg btn-primary btn-lg-square mx-1" href="#"><i class="fab fa-linkedin-in"></i></a>
                        </div>
                    </div>
                </div>
                <div class="team-item">
                    <img class="img-fluid w-100" src="img/noman.jpg" alt="">
                    <div class="position-relative py-4">
                        <h4 class="text-uppercase">Noman Hossain</h4>
                        <p class="m-0">Student</p>
                        <div class="team-social position-absolute w-100 h-100 d-flex align-items-center justify-content-center">
                            <a class="btn btn-lg btn-primary btn-lg-square mx-1" href="#"><i class="fab fa-twitter"></i></a>
                            <a class="btn btn-lg btn-primary btn-lg-square mx-1" href="#"><i class="fab fa-facebook-f"></i></a>
                            <a class="btn btn-lg btn-primary btn-lg-square mx-1" href="#"><i class="fab fa-linkedin-in"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Team End -->



    <!-- Contact Start -->
    <div class="container-fluid">
        <div class="container pt-5 pb-3">
            <h1 class="display-1 text-primary text-center">04</h1>
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