<?php
session_start();
require 'config.php';

// Cek apakah user sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Browsing</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
    <div class="container">
        <a class="navbar-brand" href="#">Event Registration</a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="user_registered_events.php">View Registered Events</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="user_profile.php">Profile</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-5">
    <h2>Browse Events</h2>
    <div class="row">
        <?php
        // Query untuk mendapatkan data event termasuk gambar, waktu, dan deskripsi
        $query = "SELECT event_id, event_name, event_date, event_time, event_location, event_description, event_image 
                  FROM events";
        $result = $conn->query($query);

        while ($row = $result->fetch_assoc()): ?>
            <div class="col-md-4">
                <div class="card mb-4 shadow-sm">
                    <!-- Menampilkan gambar event -->
                    <?php if (!empty($row['event_image'])): ?>
                        <img src="uploads/<?php echo $row['event_image']; ?>" 
                             class="card-img-top" 
                             alt="Event Image" 
                             style="height: 200px; object-fit: cover;">
                    <?php else: ?>
                        <img src="default-image.jpg" 
                             class="card-img-top" 
                             alt="No Image Available" 
                             style="height: 200px; object-fit: cover;">
                    <?php endif; ?>

                    <div class="card-body">
                        <h5 class="card-title"><?php echo $row['event_name']; ?></h5>
                        <p class="card-text">
                            <strong>Date:</strong> <?php echo $row['event_date']; ?><br>
                            <strong>Time:</strong> <?php echo $row['event_time']; ?><br>
                            <strong>Location:</strong> <?php echo $row['event_location']; ?><br>
                            <strong>Description:</strong> <?php echo $row['event_description']; ?> <!-- Tampilkan deskripsi -->
                        </p>
                        <a href="user_register_event.php?event_id=<?php echo $row['event_id']; ?>" 
                           class="btn btn-primary">Register</a>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</div>

</body>
</html>

<?php
$conn->close();
?>
