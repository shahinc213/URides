<?php
session_start();
include 'connect.php';

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

$loggedInUserId = $_SESSION["user_id"];
$accepted = false;
$chatUserId = null;
$currentAcceptedRide = null;

// Handle request actions (accept/decline/cancel/finish)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $requestId = $_POST['request_id'];

    if (isset($_POST['accept_request'])) {
        // Accept the ride only if no active accepted ride exists for the user
        $update_query = "UPDATE rides SET status='confirmed', accepter_id='$loggedInUserId' WHERE ride_id='$requestId' AND accepter_id IS NULL";
        if (mysqli_query($conn, $update_query)) {
            $accepted = true;
            $chatUserId = $_POST['chat_user_id'];
        } else {
            echo "<script>alert('Error accepting request: " . mysqli_error($conn) . "');</script>";
        }
    } elseif (isset($_POST['decline_request'])) {
        // Decline a ride request
        $decline_query = "INSERT INTO declined_requests (user_id, ride_id) VALUES ('$loggedInUserId', '$requestId')";
        if (!mysqli_query($conn, $decline_query)) {
            echo "<script>alert('Error declining request: " . mysqli_error($conn) . "');</script>";
        }
    } elseif (isset($_POST['cancel_request'])) {
        // Cancel the ride request and remove it from the rides table for all users
        $cancel_query = "DELETE FROM rides WHERE ride_id='$requestId' AND user_id='$loggedInUserId'";
        if (mysqli_query($conn, $cancel_query)) {
            header("Location: requests.php");
            exit();
        } else {
            echo "<script>alert('Error cancelling request: " . mysqli_error($conn) . "');</script>";
        }
    } elseif (isset($_POST['finish_ride'])) {
        // Finish the ride by setting the status to 'completed'
        $finish_ride_query = "UPDATE rides SET status='completed' WHERE ride_id='$requestId' AND (accepter_id='$loggedInUserId' OR user_id='$loggedInUserId')";
        if (mysqli_query($conn, $finish_ride_query)) {
            header("Location: requests.php");
            exit();
        } else {
            echo "<script>alert('Error finishing ride: " . mysqli_error($conn) . "');</script>";
        }
    }
}

// Fetch active accepted ride for the logged-in user (both sender and accepter)
$active_ride_query = "
    SELECT * FROM rides
    WHERE (user_id='$loggedInUserId' OR accepter_id='$loggedInUserId') AND status = 'confirmed'
";
$active_ride_result = mysqli_query($conn, $active_ride_query);

if (!$active_ride_result) {
    die("Error in active ride query: " . mysqli_error($conn));
}

if (mysqli_num_rows($active_ride_result) > 0) {
    $currentAcceptedRide = mysqli_fetch_assoc($active_ride_result);
    $accepted = true;
}

// Fetch available ride requests excluding declined and confirmed requests
$search_conditions = [];
$search_conditions[] = "r.ride_id NOT IN (SELECT ride_id FROM declined_requests WHERE user_id = '$loggedInUserId')";
$search_conditions[] = "r.status = 'pending'";
$search_conditions[] = "r.accepter_id IS NULL";

if (!empty($_GET['search_type'])) {
    $search_type = mysqli_real_escape_string($conn, $_GET['search_type']);
    $search_conditions[] = "r.ride_type = '$search_type'";
}

if (!empty($_GET['search_pickup'])) {
    $search_pickup = mysqli_real_escape_string($conn, $_GET['search_pickup']);
    $search_conditions[] = "r.start_location LIKE '%$search_pickup%'";
}

$where_sql = "";
if (count($search_conditions) > 0) {
    $where_sql = "WHERE " . implode(" AND ", $search_conditions);
}

// Fetch ride requests based on search filters
$request_query = "
    SELECT r.*, u.name 
    FROM rides r 
    JOIN users u ON r.user_id = u.user_id 
    $where_sql
    ORDER BY r.ride_date DESC, r.ride_time DESC
";

$result = mysqli_query($conn, $request_query);

if (!$result) {
    die("Error in ride request query: " . mysqli_error($conn));
}

$active_ride_query = "
    SELECT r.*, 
           u1.name AS sender_name, 
           u2.name AS accepter_name 
    FROM rides r
    LEFT JOIN users u1 ON r.user_id = u1.user_id
    LEFT JOIN users u2 ON r.accepter_id = u2.user_id
    WHERE (r.user_id='$loggedInUserId' OR r.accepter_id='$loggedInUserId') AND r.status = 'confirmed'
";

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
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@400;500;600;700&family=Rubik&display=swap"
        rel="stylesheet">

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

        /* style.css */
        /* General body and font styling */
        body {
            font-family: 'Rubik', sans-serif;
        }

        /* Request Cards */
        .request-card {
            border: 1px solid #ddd;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }

        .request-card:hover {
            transform: scale(1.03);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
        }

        .ride-info {
            font-size: 0.9em;
            color: #555;
        }

        .btn {
            margin-top: 10px;
            margin-right: 10px;
            border-radius: 20px;
        }

        /* Congratulations Card */
        .congrats-card {
            background-color: #f8f9fa;
            border: 1px solid #ddd;
            border-radius: 10px;
            padding: 30px;
            text-align: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .congrats-card h2 {
            color: #28a745;
            margin-bottom: 20px;
        }

        /* Pending animation */
        @keyframes pendingAnimation {
            0% {
                opacity: 1;
            }

            50% {
                opacity: 0.5;
            }

            100% {
                opacity: 1;
            }
        }

        .pending-animation {
            font-size: 20px;
            color: #f77d0a;
            animation: pendingAnimation 2s infinite;
        }

        /* Advanced Search Form */
        .advanced-search {
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .form-control {
            border-radius: 20px;
        }

        .btn-search {
            background-color: #f77d0a;
            color: white;
            border: none;
            border-radius: 20px;
        }

        .btn-search:hover {
            background-color: #e56c00;
        }

        .chat-modal {
            position: fixed;
            z-index: 99999999;
            bottom: 10px;
            right: 20px;
            width: 400px;
            height: 500px;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            display: none;
            flex-direction: column;
            border-radius: 10px;
            transition: all 0.3s ease-in-out;
        }

        .chat-modal.show {
            display: flex;
        }

        .chat-container {
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .chat-header {
            background-color: #075e54;
            color: white;
            padding: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
        }

        .chat-header h3 {
            color: white;
            margin: 0;
        }

        .close-chat-btn {
            background-color: transparent;
            border: none;
            color: white;
            font-size: 18px;
        }

        .chat-history {
            flex: 1;
            padding: 20px;
            overflow-y: scroll;
            background-color: #ece5dd;
        }

        /* Message styling */
        .message {
            display: flex;
            margin-bottom: 15px;
            align-items: center;
        }

        .message.other-user .message-content {
            background-color: #f1f0f0;
            color: #333;
            border-radius: 20px 20px 20px 0;
            align-self: flex-end;
            /* text-align: left;
            margin-left: auto; */
        }

        .message.logged-in-user .message-content {
            background-color: #075e54;
            color: white;
            border-radius: 20px 20px 0 20px;
            align-self: flex-end;
            text-align: right;
            margin-left: auto;
        }

        .message-content {
            padding: 10px 15px;
            max-width: 60%;
        }

        /* Profile photo */
        .message img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-right: 10px;
        }

        .message.logged-in-user img {
            display: none;
            /* Hide logged-in user's image */
        }

        /* Input and button styling */
        .typing-area {
            display: flex;
            padding: 10px;
            background-color: #fff;
            border-top: 1px solid #ddd;
            border-bottom-left-radius: 10px;
            border-bottom-right-radius: 10px;
        }

        .typing-area input {
            flex: 1;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 20px;
            margin-right: 10px;
        }

        .send-btn {
            padding: 10px 15px;
            background-color: #075e54;
            color: white;
            border: none;
            border-radius: 20px;
            cursor: pointer;
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
                        <a href="home.php" class="nav-item nav-link ">Home</a>
                        <a href="about.php" class="nav-item nav-link">About</a>
                        <a href="chat_inbox.php" class="nav-item nav-link">Messages</a>
                        <a href="requests.php" class="nav-item nav-link active">Requests</a>
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
                    </div>
                </div>
            </nav>
        </div>
    </div>
    <!-- Navbar End -->
    <!-- Page Header Start -->
    <div class="container-fluid page-header">
        <h1 class="display-3 text-uppercase text-white mb-3">Requests</h1>
        <div class="d-inline-flex text-white">
            <h6 class="text-uppercase m-0"><a class="text-white" href="home.php">Home</a></h6>
            <h6 class="text-body m-0 px-3">/</h6>
            <h6 class="text-uppercase text-body m-0">Requests</h6>
        </div>
    </div>
    <!-- Page Header Start -->


    <div class="container mt-5">
    <?php
    // Fetch the accepter's name based on the accepter_id
    $accepterId = null;
    $accepterName = "";

    if ($currentAcceptedRide && isset($currentAcceptedRide['accepter_id'])) {
        $accepterId = $currentAcceptedRide['accepter_id'];
        if ($accepterId) {
            $accepter_query = "SELECT name FROM users WHERE user_id = '$accepterId'";
            $accepter_result = mysqli_query($conn, $accepter_query);
        
            if ($accepter_result) {
                $accepter_row = mysqli_fetch_assoc($accepter_result);
                $accepterName = $accepter_row['name']; // Store the accepter's name
            }
        }
    }

    ?>

        <!-- Show Congratulations card if user has accepted a ride -->
        <?php if ($accepted && $currentAcceptedRide): ?>
            <div class="congrats-card">
                <?php if ($loggedInUserId == $currentAcceptedRide['user_id']): ?>
                    <!-- Request Sender's Card -->
                    <h2>Congratulations! Your request has been accepted by 
                        <?php echo htmlspecialchars($accepterName); ?>.
                    </h2>
                <?php else: ?>
                    <!-- Request Accepter's Card -->
                    <h2>Congratulations! You have accepted the request from 
                        <?php 
                        // Fetch the requester's name
                        $senderName = "";
                        $senderId = $currentAcceptedRide['user_id'];
                        
                        $sender_query = "SELECT name FROM users WHERE user_id = '$senderId'";
                        $sender_result = mysqli_query($conn, $sender_query);
                        
                        if ($sender_result) {
                            $sender_row = mysqli_fetch_assoc($sender_result);
                            $senderName = $sender_row['name']; // Store the sender's name
                        }
                        
                        echo htmlspecialchars($senderName);
                        ?>.
                    </h2>
                <?php endif; ?>
                
                
                <div class="pending-animation">Ride Pending...</div>

                <!-- Chat button now opens the chat modal instead of navigating to chat.php -->
                <button type="button" class="btn btn-primary mt-4 chat-btn" data-user-id="<?php echo $loggedInUserId == $currentAcceptedRide['user_id'] ? $currentAcceptedRide['accepter_id'] : $currentAcceptedRide['user_id']; ?>">
                    Chat with the user
                </button>

                <!-- Finish Ride Button Logic -->
                <?php if ($loggedInUserId == $currentAcceptedRide['user_id'] || ($currentAcceptedRide['ride_type'] != 'bike')): ?>
                    <form method="post" action="">
                        <input type="hidden" name="request_id" value="<?php echo $currentAcceptedRide['ride_id']; ?>">
                        <button type="submit" name="finish_ride" class="btn btn-success mt-3">Finish Ride</button>
                    </form>
                <?php endif; ?>
            </div>
        <?php endif; ?>


        <h1>Booking Requests</h1>
        <!-- Advanced Search Form -->
        <div class="advanced-search">
            <form method="GET" action="">
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="search_type">Ride Type</label>
                        <select class="form-control" name="search_type">
                            <option value="">All</option>
                            <option value="rickshaw" <?php echo (isset($_GET['search_type']) &&
                                $_GET['search_type']=='rickshaw' ) ? 'selected' : '' ; ?>>Rickshaw</option>
                            <option value="bike" <?php echo (isset($_GET['search_type']) && $_GET['search_type']=='bike'
                                ) ? 'selected' : '' ; ?>>Bike</option>
                        </select>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="search_pickup">Pickup Location</label>
                        <input type="text" class="form-control" name="search_pickup"
                            value="<?php echo isset($_GET['search_pickup']) ? $_GET['search_pickup'] : ''; ?>">
                    </div>
                </div>
                <button type="submit" class="btn btn-search">Search</button>
            </form>
        </div>

        <!-- Display Requests as Cards -->
        <div class="row">
            <?php while ($request = mysqli_fetch_assoc($result)) : ?>
            <div class="col-md-4">
                <div class="request-card">
                    <h4>
                        <?php echo htmlspecialchars($request['start_location']) . " to " . htmlspecialchars($request['end_location']); ?>
                    </h4>
                    <p>Date:
                        <?php echo htmlspecialchars($request['ride_date']); ?>
                    </p>
                    <p>Time:
                        <?php echo htmlspecialchars($request['ride_time']); ?>
                    </p>
                    <p class="ride-info">Requested by:
                        <?php echo htmlspecialchars($request['name']); ?>
                    </p>
                    <p class="ride-info">Ride Type:
                        <?php echo htmlspecialchars($request['ride_type']); ?>
                    </p>
                    <p class="ride-info">Status:
                        <?php echo htmlspecialchars($request['status']); ?>
                    </p>
                    <form method="post" action="">
                        <input type="hidden" name="request_id" value="<?php echo $request['ride_id']; ?>">
                        <input type="hidden" name="chat_user_id" value="<?php echo $request['user_id']; ?>">
                        <?php if ($request['user_id'] === $loggedInUserId): ?>
                        <!-- Show the Cancel Request button for the user who created the request -->
                        <button type="submit" name="cancel_request" class="btn btn-danger">Cancel Request</button>
                        <?php else: ?>
                        <!-- Show the Accept/Decline buttons for other users -->
                        <button type="submit" name="accept_request" class="btn btn-success" <?php echo $accepted
                            ? 'disabled' : '' ; ?>>
                            Accept
                        </button>
                        <button type="submit" name="decline_request" class="btn btn-danger">Decline</button>
                        <button type="button" class="btn btn-info chat-btn"
                            data-user-id="<?php echo $request['user_id']; ?>">
                            Chat
                        </button>
                        <?php endif; ?>
                    </form>
                </div>
            </div>
            <?php endwhile; ?>
        </div>

    </div>

    <!-- Chat Modal -->
    <div id="chat-modal" class="chat-modal">
        <div class="chat-container">
            <div class="chat-header">
                <h3>Chat</h3>
                <button id="close-chat" class="close-chat-btn">X</button>
            </div>
            <div id="chat-history" class="chat-history">
                <!-- Chat messages will be dynamically loaded here -->
            </div>
            <form id="typing-area" class="typing-area">
                <input type="text" id="message" placeholder="Type your message...">
                <button type="submit" id="send" class="send-btn">Send</button>
            </form>
        </div>
    </div>

    <!-- Chat JavaScript -->
    <script>
        const chatModal = document.getElementById("chat-modal");
        const closeChatBtn = document.getElementById("close-chat");
        const sendBtn = document.getElementById("send");
        const inputField = document.getElementById("message");
        const chatbox = document.getElementById("chat-history");

        let receiver_id = null;

        // Open Chat Modal
        document.querySelectorAll('.chat-btn').forEach(button => {
            button.addEventListener('click', () => {
                receiver_id = button.getAttribute('data-user-id');
                chatModal.style.display = 'flex'; // Show the chat modal
                loadChatHistory();
            });
        });

        // Close Chat Modal
        closeChatBtn.addEventListener('click', () => {
            chatModal.style.display = 'none'; // Hide the chat modal
        });

        // Send message
        sendBtn.addEventListener('click', function (event) {
            event.preventDefault();
            sendChat();
        });

        function sendChat() {
            let xhr = new XMLHttpRequest();
            xhr.open("POST", "chat.php", true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onload = () => {
                if (xhr.status == 200) {
                    inputField.value = ""; // Clear the input field after sending
                }
            };
            let params = `sender_id=<?php echo $loggedInUserId; ?>&receiver_id=${receiver_id}&message=${inputField.value}`;
            xhr.send(params);
        }

        // Fetch new messages every 500ms
        setInterval(() => {
            if (receiver_id) {
                loadChatHistory();
            }
        }, 200);

        function loadChatHistory() {
            let xhr = new XMLHttpRequest();
            xhr.open("POST", "get-chat.php", true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onload = () => {
                if (xhr.status == 200) {
                    let data = JSON.parse(xhr.responseText);
                    chatbox.innerHTML = ''; // Clear previous messages

                    data.forEach(msg => {
                        const isLoggedInUser = msg.sender_id === '<?php echo $loggedInUserId; ?>';
                        const messageDiv = document.createElement('div');
                        messageDiv.classList.add('message', isLoggedInUser ? 'logged-in-user' : 'other-user');

                        const messageContent = document.createElement('div');
                        messageContent.classList.add('message-content');
                        messageContent.textContent = msg.message;

                        if (!isLoggedInUser) {
                            const img = document.createElement('img');
                            img.src = msg.profile_photo;  // Assuming you fetch the profile photo URL
                            messageDiv.appendChild(img);
                        }

                        messageDiv.appendChild(messageContent);
                        chatbox.appendChild(messageDiv);
                    });
                }
            };
            let params = `sender_id=<?php echo $loggedInUserId; ?>&receiver_id=${receiver_id}`;
            xhr.send(params);
        }
    </script>

    <!-- Footer Start -->
    <?php include 'footer.php'; ?>
    <!-- Footer End -->

    <!-- <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="fa fa-angle-double-up"></i></a> -->



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