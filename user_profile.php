<?php
session_start();
require 'config.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user data from the database
$stmt = $conn->prepare("SELECT * FROM users WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body class="bg-light">

<div class="container mt-5">
    <!-- Flexbox container for the header -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Your Profile</h2>
        <!-- Back button on the top-right -->
        <a href="user_event_browsing.php" class="btn btn-secondary">Back</a>
    </div>

    <!-- View Profile -->
    <div class="card mb-4">
        <div class="card-body">
            <h4 class="card-title">Profile Information</h4>
            <p><strong>Name:</strong> <?php echo $user['name']; ?></p>
            <p><strong>Email:</strong> <?php echo $user['email']; ?></p>

            <!-- Logout Button -->
            <button id="logoutBtn" class="btn btn-danger mt-3">Logout</button>
        </div>
    </div>

    <!-- Edit Profile Form -->
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Edit Profile</h4>
            <form id="updateForm" action="index.php" method="POST">
                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" name="name" id="name" class="form-control" 
                           value="<?php echo $user['name']; ?>" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" name="email" id="email" class="form-control" 
                           value="<?php echo $user['email']; ?>" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">New Password (Optional)</label>
                    <input type="password" name="password" id="password" class="form-control">
                </div>
                <button type="button" id="updateBtn" class="btn btn-primary">Update Profile</button>
            </form>
        </div>
    </div>
</div>

<!-- Pop-up Konfirmasi -->
<div id="confirmPopup" class="modal" tabindex="-1" role="dialog" style="display: none;">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Are you sure?</h5>
            </div>
            <div class="modal-body">
                <p>Do you really want to proceed with this action?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" id="confirmYes">Yes</button>
                <button type="button" class="btn btn-secondary" id="confirmNo">No</button>
            </div>
        </div>
    </div>
</div>

<!-- Email and Password Confirmation Modal -->
<div id="authPopup" class="modal" tabindex="-1" role="dialog" style="display: none;">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Please Confirm</h5>
            </div>
            <div class="modal-body">
                <p>Enter your email and password to confirm:</p>
                <input type="email" id="authEmail" class="form-control mb-2" placeholder="Email">
                <input type="password" id="authPassword" class="form-control mb-2" placeholder="Password">
                <p id="authError" class="text-danger" style="display: none;">Invalid email or password.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" id="authConfirm">Confirm</button>
                <button type="button" class="btn btn-secondary" id="cancelAuth">Cancel</button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    let actionType = null;

    // Handle Logout button click
    $("#logoutBtn").on("click", function() {
        actionType = "logout";
        $("#confirmPopup").fadeIn(); // Show confirmation pop-up
    });

    // Handle Update Profile button click
    $("#updateBtn").on("click", function() {
        actionType = "update";
        $("#confirmPopup").fadeIn(); // Show confirmation pop-up
    });

    // Handle confirmation 'Yes' button click
    $("#confirmYes").on("click", function() {
        $("#confirmPopup").fadeOut();
        $("#authPopup").fadeIn(); // Show authentication pop-up
    });

    // Handle authentication 'Cancel' button
    $("#cancelAuth").on("click", function() {
        $("#authPopup").fadeOut(); // Close the authentication modal
    });

    // Handle authentication 'Confirm' button
    $("#authConfirm").on("click", function() {
        const email = $("#authEmail").val();
        const password = $("#authPassword").val();

        // Perform AJAX request to confirm the user's identity
        $.ajax({
            url: "confirm_update_user.php", // A new PHP file to handle the authentication
            type: "POST",
            data: {
                email: email,
                password: password
            },
            success: function(response) {
                if (response === "success") {
                    if (actionType === "logout") {
                        window.location.href = "index.php"; // Redirect to logout page
                    } else if (actionType === "update") {
                        $("#updateForm").submit(); // Submit the update form
                    }
                } else {
                    $("#authError").show(); // Show error message
                }
            }
        });
    });
});
</script>

</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
