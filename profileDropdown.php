<?php
// Start the session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include 'connect.php'; // Ensure your database connection

$user_id = $_SESSION['user_id'];

// Fetch ride history from the database for the logged-in user
$ride_history_query = "SELECT * FROM rides WHERE user_id='$user_id' ORDER BY ride_date DESC, ride_time DESC";
$ride_history_result = mysqli_query($conn, $ride_history_query);

// Default profile photo if none is available
$profile_photo = $_SESSION['profile_photo'] ?? 'img/default.jpg'; 

// Check if the photo path includes the 'uploads/' directory and prepend it if necessary
if ($profile_photo !== 'img/default.jpg' && strpos($profile_photo, 'uploads/') === false) {
    $profile_photo = 'uploads/' . $profile_photo;
}

// Fetch the user's name from the session or use 'Guest' as default
$name = $_SESSION['name'] ?? 'Guest';
?>

<!-- Profile Dropdown Menu -->
<div class="d-flex">
    <div id="navdrop" class="btn-dark">
        <div class="drop-btn">
            <a id="menu" tooltipped="" href="#!">
                <!-- Dynamically display user's profile photo -->
                <img src="<?php echo htmlspecialchars($profile_photo); ?>" class="profilePicture"
                    style="cursor: pointer !important; border-radius:100%; object-fit:cover; width:40px; height: 40px; margin-left: 1px;">
            </a>
        </div>
        <div class="tooltip"></div>
        <div class="wrapper" style="height: 385px;">
            <ul class="menu-bar" style="margin-left: 0px;">
                <li class="profile-dsf3SD_02-1SAd9">
                    <a href="#">
                        <img src="<?php echo htmlspecialchars($profile_photo); ?>" class="responsive-img">
                        <div>
                            <span id="ch_head_name">
                                <?php echo htmlspecialchars($name); ?>
                            </span><br>
                            <span id="head_manage_acc">Manage Settings</span>
                        </div>
                    </a>
                </li>
                
                <li class="editProfile">
                    <a href="#" data-toggle="modal" data-target="#editProfileModal">
                        <div class="icon"><span class="material-icons">keyboard_arrow_right</span></div>
                        Edit Profile
                    </a>
                </li>
                <li class="forgotPass">
                    <a href="changePassword.php">
                        <div class="icon"><span class="material-icons">keyboard_arrow_right</span></div>
                        Change Password
                    </a>
                </li>
                <!-- Ride History Button (Opens Modal) -->
                <li>
                    <a href="#" data-toggle="modal" data-target="#rideHistoryModal">
                        <div class="icon"><span class="material-icons">group</span></div>
                        Ride History 
                    </a>
                </li>
                <li class="logout">
                    <a href="logout.php">
                        <div class="icon"><span class="material-icons">exit_to_app</span></div>
                        Logout
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>

<!-- Ride History Modal -->
<div class="modal fade" id="rideHistoryModal" tabindex="-1" role="dialog" aria-labelledby="rideHistoryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="rideHistoryModalLabel">Your Ride History</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Ride ID</th>
                            <th>Ride Type</th>
                            <th>Start Location</th>
                            <th>End Location</th>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = mysqli_fetch_assoc($ride_history_result)): ?>
                            <tr>
                                <td><?php echo $row['ride_id']; ?></td>
                                <td><?php echo ucfirst($row['ride_type']); ?></td>
                                <td><?php echo $row['start_location']; ?></td>
                                <td><?php echo $row['end_location']; ?></td>
                                <td><?php echo $row['ride_date']; ?></td>
                                <td><?php echo $row['ride_time']; ?></td>
                                <td><?php echo ucfirst($row['status']); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Profile Modal -->
<div class="modal fade" id="editProfileModal" tabindex="-1" role="dialog" aria-labelledby="editProfileModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editProfileModalLabel">Edit Profile</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="update_profile.php" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($name); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="phone">Phone</label>
                        <input type="text" class="form-control" id="phone" name="phone" value="<?php echo htmlspecialchars($_SESSION['phone']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="profile_photo">Profile Photo</label>
                        <input type="file" class="form-control" id="profile_photo" name="profile_photo">
                        <small class="form-text text-muted">Leave blank to keep current photo.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- Custom CSS for Modals -->
<style>
    /* .modal-header {
        background-color: #075e54;
        color: white;
    }
    .modal-header h5, 
    .modal-header .modal-title { 
        color: white; 
        margin: 0; 
    } */
    .modal-footer button {
        transition: background-color 0.3s ease;
    }
    .modal-footer button:hover {
        background-color: #06433b;
    }
    .form-control:focus {
        border-color: #28a745;
        box-shadow: 0 0 5px rgba(40, 167, 69, 0.5);
    }
</style>

<!-- Include necessary JS and Bootstrap for Modal -->
<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>
