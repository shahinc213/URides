<?php
session_start();
include 'connect.php';

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$user_type = $_SESSION['user_type']; 

// Fetch user's pending ride requests
$existing_request_query = "SELECT * FROM rides WHERE user_id='$user_id' AND status='pending'";
$existing_request_result = mysqli_query($conn, $existing_request_query);

if (isset($_POST['submit_rickshaw_ride'])) {
    if (mysqli_num_rows($existing_request_result) == 0) {
        $pickup_location = mysqli_real_escape_string($conn, $_POST['rickshawPick']);
        $drop_location = mysqli_real_escape_string($conn, $_POST['drop']);
        $pickup_date = date('Y-m-d', strtotime(mysqli_real_escape_string($conn, $_POST['pickup_date'])));
        $pickup_time = mysqli_real_escape_string($conn, $_POST['pickup_time']);

        $query = "INSERT INTO rides (user_id, ride_type, start_location, end_location, ride_date, ride_time, status) 
                  VALUES ('$user_id', 'rickshaw', '$pickup_location', '$drop_location', '$pickup_date', '$pickup_time', 'pending')";

        if (mysqli_query($conn, $query)) {
            header("Location: rideShare.php?booking_success=1");
            exit;
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    } else {
        echo "<script>alert('You already have an active ride request. Please cancel it before making a new one.');</script>";
    }
}

if (isset($_POST['submit_bike_ride']) && $user_type == 'rider') { // Only riders can request bike rides
    if (mysqli_num_rows($existing_request_result) == 0) {
        $pickup_location = mysqli_real_escape_string($conn, $_POST['bikePick']);
        $drop_location = mysqli_real_escape_string($conn, $_POST['drop']);
        $pickup_date = date('Y-m-d', strtotime(mysqli_real_escape_string($conn, $_POST['pickup_date'])));
        $pickup_time = mysqli_real_escape_string($conn, $_POST['pickup_time']);

        $query = "INSERT INTO rides (user_id, ride_type, start_location, end_location, ride_date, ride_time, status) 
                  VALUES ('$user_id', 'bike', '$pickup_location', '$drop_location', '$pickup_date', '$pickup_time', 'pending')";

        if (mysqli_query($conn, $query)) {
            header("Location: rideShare.php?booking_success=1");
            exit;
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    } else {
        echo "<script>alert('You already have an active ride request. Please cancel it before making a new one.');</script>";
    }
}

mysqli_close($conn);
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
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet">
    <link href="img/favicon.ico" rel="icon">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@400;500;600;700&family=Rubik&display=swap" rel="stylesheet"> 
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.0/css/all.min.css" rel="stylesheet">
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css" rel="stylesheet" />
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <style>
        .btn {
            border-radius: 20px;
            margin-right: 10px;
        }
        .alert-success {
            position: absolute;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 1000;
            width: 80%;
        }

        /* Center the Rickshaw form */
        .center-form {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100%;
        }

        .form-container {
            max-width: 600px;
            width: 100%;
        }
    </style>
</head>

<body>
    <!-- Topbar Start -->
    <?php include 'topbar.php'; ?>
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
                            <a href="#" class="nav-link dropdown-toggle active" data-toggle="dropdown">Services</a>
                            <div class="dropdown-menu rounded-0 m-0">
                                <a href="rideShare.php" class="dropdown-item">Ride Share</a>
                                <a href="shuttle.php" class="dropdown-item">Shuttle</a>
                                <a href="booking.php" class="dropdown-item">Cycle Rental</a>
                            </div>
                        </div>
                        <a href="contact.php" class="nav-item nav-link">Contact</a>

                        <!-- Profile Dropdown Menu -->
                        <?php include 'profileDropdown.php'; ?>
                    </div>
                </div>
            </nav>
        </div>
    </div>
    <!-- Navbar End -->

    <!-- Page Header Start -->
    <div class="container-fluid page-header">
        <h1 class="display-3 text-uppercase text-white mb-3">Ride Share</h1>
        <div class="d-inline-flex text-white">
            <h6 class="text-uppercase m-0"><a class="text-white" href="home.php">Home</a></h6>
            <h6 class="text-body m-0 px-3">/</h6>
            <h6 class="text-uppercase text-body m-0">Ride Share</h6>
        </div>
    </div>
    <!-- Page Header End -->

    <!-- Success Message for Booking -->
    <?php if (isset($_GET['booking_success']) && $_GET['booking_success'] == 1): ?>
        <div class="alert alert-success alert-dismissible fade show mt-4" role="alert">
            <strong>Congratulations!</strong> Your ride has been successfully booked.
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    <?php endif; ?>

    <!-- Ride Selection Options -->
    <div class="container-fluid py-5">
        <div class="container pt-5 pb-3">
            <h1 class="display-4 text-uppercase text-center mb-5">Choose Your Ride</h1>
            <div class="row">
                <!-- Bike Ride Option (Only for Riders) -->
                <?php if ($user_type == 'rider'): ?>
                    <div class="col-lg-6 col-md-6 mb-2">
                        <div class="rent-item mb-4">
                            <img class="img-fluid mb-4" src="img/bike.png" alt="">
                            <h4 class="text-uppercase mb-4">Bike</h4>
                            <div class="d-flex justify-content-center mb-4">
                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal1">Select</button>

                                <!-- Bike Ride Modal -->
                                <div class="modal fade" id="modal1" tabindex="-1" role="dialog" aria-labelledby="modal1Title" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="modal1Title">Share Your Bike Ride</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <h2 class="mb-4">Booking Detail</h2>
                                                <form method="post" action="">
                                                    <div class="row">
                                                        <div class="col-6 form-group">
                                                            <input type="text" name="bikePick" placeholder="Pickup Location" required>
                                                        </div>
                                                        <div class="col-6 form-group">
                                                            <input type="text" name="drop" placeholder="Drop Location" required>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-6 form-group">
                                                            <input type="text" name="pickup_date" placeholder="Pickup Date" required>
                                                        </div>
                                                        <div class="col-6 form-group">
                                                            <input type="text" name="pickup_time" placeholder="Pickup Time" required>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="submit" name="submit_bike_ride" class="btn btn-primary">Book Now</button>
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Rickshaw Ride Option (For all users) -->
                <div class="col-lg-6 col-md-6 mb-2 center-form">
                    <div class="rent-item mb-4 form-container">
                        <img class="img-fluid mb-4" src="img/rickshaw.png" alt="">
                        <h4 class="text-uppercase mb-4">Rickshaw</h4>
                        <div class="d-flex justify-content-center mb-4">
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal2">Select</button>

                            <!-- Rickshaw Ride Modal -->
                            <div class="modal fade" id="modal2" tabindex="-1" role="dialog" aria-labelledby="modal2Title" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="modal2Title">Share Your Rickshaw Ride</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <h2 class="mb-4">Booking Detail</h2>
                                            <form method="post" action="">
                                                <div class="row">
                                                    <div class="col-6 form-group">
                                                        <input type="text" name="rickshawPick" placeholder="Pickup Location" required>
                                                    </div>
                                                    <div class="col-6 form-group">
                                                        <input type="text" name="drop" placeholder="Drop Location" required>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-6 form-group">
                                                        <input type="text" name="pickup_date" placeholder="Pickup Date" required>
                                                    </div>
                                                    <div class="col-6 form-group">
                                                        <input type="text" name="pickup_time" placeholder="Pickup Time" required>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="submit" name="submit_rickshaw_ride" class="btn btn-primary">Book Now</button>
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Footer Start -->
    <?php include 'footer.php'; ?>
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
