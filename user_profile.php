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
            <a href="logout.php" class="btn btn-danger mt-3">Logout</a>
        </div>
    </div>

    <!-- Edit Profile Form -->
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Edit Profile</h4>
            <form action="update_profile.php" method="POST">
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
                <button type="submit" class="btn btn-primary">Update Profile</button>
            </form>
        </div>
    </div>
</div>

</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
