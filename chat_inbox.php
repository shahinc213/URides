<?php
session_start();
include 'connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$loggedInUserId = $_SESSION['user_id'];

// Fetch users who have messaged the logged-in user, sorted by most recent message
$user_query = "
    SELECT DISTINCT u.user_id, u.name, u.profile_photo, MAX(m.timestamp) as last_message_time
    FROM users u
    JOIN messages m ON (u.user_id = m.sender_id OR u.user_id = m.receiver_id)
    WHERE (m.sender_id = '$loggedInUserId' OR m.receiver_id = '$loggedInUserId') AND u.user_id != '$loggedInUserId'
    GROUP BY u.user_id
    ORDER BY last_message_time DESC
";
$user_result = mysqli_query($conn, $user_query);

$users = [];
while ($row = mysqli_fetch_assoc($user_result)) {
    $users[] = $row;
}

// Fetch messages with the first user (if no user is selected)
$selectedUserId = isset($_GET['user_id']) ? $_GET['user_id'] : (count($users) > 0 ? $users[0]['user_id'] : null);
$messages = [];

if ($selectedUserId) {
    $message_query = "
        SELECT m.*, u.name, u.profile_photo 
        FROM messages m 
        JOIN users u ON m.sender_id = u.user_id
        WHERE (m.sender_id = '$loggedInUserId' AND m.receiver_id = '$selectedUserId')
        OR (m.sender_id = '$selectedUserId' AND m.receiver_id = '$loggedInUserId')
        ORDER BY m.timestamp ASC
    ";
    $message_result = mysqli_query($conn, $message_query);
    while ($msg_row = mysqli_fetch_assoc($message_result)) {
        $messages[] = $msg_row;
    }
}

// Get the name of the selected user for the chat header
$selectedUserName = "";
if ($selectedUserId) {
    $selectedUser_query = "SELECT name, profile_photo FROM users WHERE user_id = '$selectedUserId'";
    $selectedUser_result = mysqli_query($conn, $selectedUser_query);
    if ($selectedUser_result) {
        $selectedUser_row = mysqli_fetch_assoc($selectedUser_result);
        $selectedUserName = $selectedUser_row['name'];
        $selectedUserPhoto = $selectedUser_row['profile_photo']; // Get the correct profile photo
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
     <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="css/style.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f0f0f0;
            align-items: center;
        }

        .chat-container {
            display: flex;
            height: 85vh;
            width: 60%;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        /* Sidebar (User list) */
        .user-list {
            width: 45%;
            background-color: #fff;
            border-right: 1px solid #ddd;
            overflow-y: auto;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            max-height: 85vh; /* Limit height to avoid overflow */
        }

        .user-list h4 {
            font-size: 1.6rem;
            font-weight: bold;
            margin-bottom: 20px;
            color: #333;
            text-align: center; /* Center the header */
        }

        /* Search Box */
        .search-box {
            margin-bottom: 15px;
        }

        /* Other existing styles... */

        .user {
            display: flex;
            align-items: center;
            padding: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s ease; /* Smooth transitions */
            border-radius: 10px;
            margin-bottom: 2px; /* Space between users */
            text-decoration: none; /* Remove underline */
        }

        .user:hover {
            background-color: #e7f1f5; /* Lighter hover color */
            transform: scale(1.02); /* Slightly enlarge on hover */
        }

        .user img {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            margin-right: 15px; /* Add more spacing between image and name */
            border: 2px solid #075e54; /* Border around the profile photo */
            transition: border 0.3s ease; /* Smooth border transition */
        }

        .user img:hover {
            border-color: #28a745; /* Change border color on hover */
        }

        .user .name {
            font-size: 1.2rem; /* Slightly larger font size for names */
            font-weight: 500;
            color: #333;
        }

        /* Chat window */
        .chat-window {
            width: 75%;
            background-color: #ece5dd;
            display: flex;
            flex-direction: column;
        }

        .chat-header {
            background-color: #075e54;
            color: white;
            padding: 15px;
            display: flex;
            align-items: center;
            border-bottom: 1px solid #ddd;
        }

        .chat-header img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-right: 10px;
        }

        .chat-header .name {
            font-size: 1.2rem;
        }

        .chat-history {
            flex: 1;
            padding: 20px;
            overflow-y: auto;
            background-color: #fafafa;
        }

        .message {
            display: flex;
            margin-bottom: 15px;
            align-items: flex-end;
            animation: slideIn 0.5s ease-in-out;
        }

        .message.sender .message-content {
            background-color: #dcf8c6;
            color: black;
            border-radius: 20px 20px 0 20px;
            align-self: flex-end;
            text-align: right;
            margin-left: auto;
        }

        .message.receiver .message-content {
            background-color: #fff;
            color: black;
            border-radius: 20px 20px 20px 0;
            align-self: flex-start;
            text-align: left;
            margin-right: auto;
        }

        .message-content {
            max-width: 60%;
            padding: 10px;
            border-radius: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            word-wrap: break-word;
        }

        .typing-area {
            background-color: #fff;
            padding: 10px;
            border-top: 1px solid #ddd;
            display: flex;
            align-items: center;
            animation: fadeIn 1s ease-in-out;
        }

        .typing-area input {
            flex: 1;
            padding: 12px;
            border-radius: 30px;
            border: 1px solid #ddd;
            margin-right: 10px;
            box-shadow: 0 1px 5px rgba(0, 0, 0, 0.1);
        }

        .typing-area button {
            background-color: #075e54;
            color: white;
            border: none;
            border-radius: 30px;
            padding: 10px 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .typing-area button:hover {
            background-color: #06433b;
        }

        @keyframes fadeIn {
            0% {
                opacity: 0;
            }
            100% {
                opacity: 1;
            }
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
                    <a href="chat_inbox.php" class="nav-item nav-link active">Messages</a>
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
                </div>
            </div>
        </nav>
    </div>
</div>
<!-- Navbar End -->

<!-- Chat Container -->
<div class="container mt-5 d-flex justify-content-center align-items-center" style="height: 85vh;">
    <div class="chat-container d-flex" style="width: 80%; max-width: 900px;"> <!-- Set a max-width for larger screens -->
        <!-- User List (Left Sidebar) -->
        <div class="user-list" style="width: 35%;">
            <h4>Chats</h4>
            <div class="search-box mb-3">
                <input type="text" class="form-control" id="user-search" placeholder="Search users by name..." onkeyup="filterUsers()">
            </div>
            <?php foreach ($users as $user): ?>
                <a href="chat_inbox.php?user_id=<?php echo $user['user_id']; ?>" class="user">
                    <div class="user">
                        <img src="<?php echo $user['profile_photo']; ?>" alt="Profile Photo">
                        <div class="name"><?php echo htmlspecialchars($user['name']); ?></div>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>

        <!-- Chat Window (Right Pane) -->
        <div class="chat-window" style="width: 65%;">
            <?php if ($selectedUserId && count($messages) > 0): ?>
                <div class="chat-header">
                    <img src="<?php echo htmlspecialchars($selectedUserPhoto); ?>" alt="Profile Photo">
                    <div class="name"><?php echo htmlspecialchars($selectedUserName); ?></div>
                </div>

                <div class="chat-history" id="chat-history">
                    <?php foreach ($messages as $msg): ?>
                        <div class="message <?php echo $msg['sender_id'] == $loggedInUserId ? 'sender' : 'receiver'; ?>">
                            <div class="message-content">
                                <?php echo htmlspecialchars($msg['message']); ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="chat-header">
                    <h5>No Messages Selected</h5>
                </div>
            <?php endif; ?>

            <!-- Typing Area -->
            <?php if ($selectedUserId): ?>
                <form class="typing-area" id="typing-area">
                    <input type="hidden" name="receiver_id" value="<?php echo $selectedUserId; ?>">
                    <input type="text" id="message" placeholder="Type your message..." required>
                    <button type="submit">Send</button>
                </form>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Footer Start -->
<?php include 'footer.php'; ?>
<!-- Footer End -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const sendBtn = document.querySelector('#typing-area button');
    const inputField = document.getElementById("message");
    const chatbox = document.getElementById("chat-history");
    const receiverId = document.querySelector('input[name="receiver_id"]').value;

    function sendChat() {
        let xhr = new XMLHttpRequest();
        xhr.open("POST", "chat.php", true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onload = () => {
            if (xhr.status === 200) {
                inputField.value = ""; // Clear input field after sending
            }
        };
        let params = `sender_id=<?php echo $loggedInUserId; ?>&receiver_id=${receiverId}&message=${inputField.value}`;
        xhr.send(params);
    }

    // Send message on form submit
    document.getElementById('typing-area').onsubmit = (e) => {
        e.preventDefault();
        sendChat();
    };

    // Fetch new messages every 500ms
    let previousMessageCount = 0;

    setInterval(() => {
        if (receiverId) {
            loadChatHistory();

            // After loading the chat history, check the number of messages
            $(document).ready(function() {
                var container = $("#chat-history"); // The div you want to scroll
                var messages = $(".message"); // Select all message elements
                var currentMessageCount = messages.length; // Get the current number of messages

                // Only scroll if a new message has been added
                if (currentMessageCount > previousMessageCount) {
                    var lastMessage = messages.last(); // Select the last message element
                    
                    // Scroll the container to the last message element
                    container.scrollTop(lastMessage.position().top - container.position().top + container.scrollTop());

                    // Update the previous message count
                    previousMessageCount = currentMessageCount;
                }
            });
        }
    }, 200);

    function loadChatHistory() {
        let xhr = new XMLHttpRequest();
        xhr.open("POST", "get-chat.php", true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onload = () => {
            if (xhr.status === 200) {
                const data = JSON.parse(xhr.responseText);
                chatbox.innerHTML = ''; // Clear previous messages

                data.forEach(msg => {
                    const messageDiv = document.createElement('div');
                    messageDiv.classList.add('message', msg.sender_id === '<?php echo $loggedInUserId; ?>' ? 'sender' : 'receiver');

                    const messageContent = document.createElement('div');
                    messageContent.classList.add('message-content');
                    messageContent.textContent = msg.message;

                    messageDiv.appendChild(messageContent);
                    chatbox.appendChild(messageDiv);
                });
            }
        };
        let params = `sender_id=<?php echo $loggedInUserId; ?>&receiver_id=${receiverId}`;
        xhr.send(params);
    }

    // Filter users based on search input
    function filterUsers() {
        const searchTerm = document.getElementById("user-search").value.toLowerCase();
        const userElements = document.querySelectorAll('.user');

        userElements.forEach(user => {
            const userName = user.querySelector('.name').textContent.toLowerCase();
            if (userName.includes(searchTerm)) {
                user.style.display = ""; // Show user if it matches the search term
            } else {
                user.style.display = "none"; // Hide user if it doesn't match
            }
        });
    }
</script>

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
