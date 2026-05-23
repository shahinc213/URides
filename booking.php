<?php

include 'connect.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$phone = $_SESSION['phone'];

$check_active_rental = "SELECT r.*, c.location FROM cycle_rentals r 
                        JOIN cycles c ON r.cycle_id = c.cycle_id
                        WHERE r.user_id = '$user_id' AND r.status = 1";

$active_rental_result = mysqli_query($conn, $check_active_rental);
$has_active_rental = mysqli_num_rows($active_rental_result) > 0;

if ($_SERVER["REQUEST_METHOD"] == "POST" && !$has_active_rental) {
    $pick = $_POST['pickup_location'];
    $drop = $_POST['drop_location'];
    $pickDate = $_POST['pickup_date'];
    $pickTime = $_POST['pickup_time'];
    $pickupDateTime = date('Y-m-d H:i:s', strtotime($pickDate . ' ' . $pickTime));

    $location_query = $pick == 'Notunbazar' ? 'Notunbazar' : 'UIU Campus';
    $check_cycle_sql = "SELECT cycle_id FROM cycles WHERE location = '$location_query' AND is_available = 1 LIMIT 1";
    $cycle_result = mysqli_query($conn, $check_cycle_sql);

    if (mysqli_num_rows($cycle_result) == 1) {
        $cycle = mysqli_fetch_assoc($cycle_result);
        $cycle_id = $cycle['cycle_id'];

        $rental_sql = "INSERT INTO cycle_rentals (user_id, cycle_id, rental_start_time, status, phone) 
                       VALUES ('$user_id', '$cycle_id', '$pickupDateTime', 1, '$phone')";
        if (mysqli_query($conn, $rental_sql)) {
            $update_cycle_sql = "UPDATE cycles SET is_available = 0 WHERE cycle_id = '$cycle_id'";
            mysqli_query($conn, $update_cycle_sql);
            header("Location: booking.php");
            exit();
        } else {
            echo "Error: " . $rental_sql . "<br>" . mysqli_error($conn);
        }
    } else {
        echo "Sorry, no cycles are available at $location_query.";
    }
}


if (isset($_POST['return_cycle'])) {
    $rental = mysqli_fetch_assoc($active_rental_result);
    $cycle_id = $rental['cycle_id'];

    $return_cycle_sql = "UPDATE cycle_rentals SET status = 0, rental_end_time = NOW() WHERE user_id = '$user_id' AND cycle_id = '$cycle_id'";

    mysqli_query($conn, $return_cycle_sql);

    $update_cycle_sql = "UPDATE cycles SET is_available = 1 WHERE cycle_id = '$cycle_id'";
    mysqli_query($conn, $update_cycle_sql);

    header("Location: booking.php");
    exit();
}

$conn->close();
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
                            <a href="#" class="nav-link dropdown-toggle active" data-toggle="dropdown">Services</a>
                            <div class="dropdown-menu rounded-0 m-0">
                                <a href="rideShare.php" class="dropdown-item">Ride Share</a>
                                <a href="shuttle.php" class="dropdown-item">Shuttle</a>
                                <a href="booking.php" class="dropdown-item">Cycle Rental</a>
                            </div>
                        </div>
    
                        <a href="contact.php" class="nav-item nav-link">Contact</a>
                        
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
        <h1 class="display-3 text-uppercase text-white mb-3">Cycle Rental</h1>
        <div class="d-inline-flex text-white">
            <h6 class="text-uppercase m-0"><a class="text-white" href="home.php">Home</a></h6>
            <h6 class="text-body m-0 px-3">/</h6>
            <h6 class="text-uppercase text-body m-0">Cycle Rental</h6>
        </div>
    </div>
    <!-- Page Header Start -->


    <!-- Detail Start -->
    <div class="container-fluid pt-5">
    <div class="container pt-5 pb-3">
        <h1 class="display-4 text-uppercase mb-5 text-left">Book Here</h1>
        <div class="row align-items-center pb-2">
            <div class="col-lg-6 mb-4">
                <img class="img-fluid" src="img/cycleS.png" alt="">
            </div>
            <div class="col-lg-6 mb-4" style="text-align: left;"> <!-- Added text-align: left -->
                <h4 class="mb-2">BDT 10 / Hour</h4>
                <div class="d-flex mb-3">
                </div>
                <p>Book your cycle today for a smooth, budget-friendly commute! Choose your desired pick-up and drop-off spots, confirm with your student ID, and you’re set. Get on the move now!</p>
            </div>
        </div>
    </div>
</div>

    <!-- Detail End -->


    <!--Booking Start -->
    <div class="container-fluid pb-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <form action="" method="POST">
                        <h2 class="mb-4 text-left">Booking Detail</h2>
                        <div class="mb-5">
                            <div class="row">
                                <div class="col-6 form-group">
                                    <select name="pickup_location" class="custom-select px-4" style="height: 50px;" required>
                                        <option selected disabled>Pickup Location</option>
                                        <option value="Notunbazar">Notunbazar</option>
                                        <option value="UIU Campus">UIU Campus</option>
                                    </select>
                                </div>
                                <div class="col-6 form-group">
                                    <select name="drop_location" class="custom-select px-4" style="height: 50px;" required>
                                        <option selected disabled>Drop Location</option>
                                        <option value="Notunbazar">Notunbazar</option>
                                        <option value="UIU Campus">UIU Campus</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6 form-group">
                                    <div class="date" id="date2" data-target-input="nearest">
                                        <input type="text" name="pickup_date" class="form-control p-4 datetimepicker-input" placeholder="Pickup Date" data-target="#date2" data-toggle="datetimepicker" required />
                                    </div>
                                </div>
                                <div class="col-6 form-group">
                                    <div class="time" id="time2" data-target-input="nearest">
                                        <input type="text" name="pickup_time" class="form-control p-4 datetimepicker-input" placeholder="Pickup Time" data-target="#time2" data-toggle="datetimepicker" required />
                                    </div>
                                </div>
                            </div>
                            <?php if (!$has_active_rental) { ?>
                                <div class="text-left">
                                    <button type="submit" name="submit" class="btn btn-primary py-md-3 px-md-5 mt-2">Book Now</button>
                                </div>                            
                            <?php } ?>
                        </div>
                    </form>
                </div>

                <div class="col-lg-4">
                    <div class="bg-secondary p-5 mb-5">
                        <h2 class="text-primary mb-4">Booking Info</h2>
                        <?php if ($has_active_rental) {
                            $rental = mysqli_fetch_assoc($active_rental_result); ?>
                            <p>Pickup Location: <?php echo $rental['location']; ?></p>
                            <p>Rental Start Time: <?php echo $rental['rental_start_time']; ?></p>
                            <form action="" method="POST">
                                <button type="submit" name="return_cycle" class="btn btn-block btn-primary py-3">Return Now</button>
                            </form>
                        <?php } else { ?>
                            <button class="btn btn-block btn-primary py-3" disabled>No active rental</button>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Booking End -->




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
