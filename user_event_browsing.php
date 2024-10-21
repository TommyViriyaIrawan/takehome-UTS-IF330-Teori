<?php
session_start();
require 'config.php';

// Cek apakah user sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Ambil daftar event terbuka
$sql = "SELECT event_id, event_name, event_date, event_location, event_description FROM events WHERE status = 'open'";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Browsing</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            padding-top: 50px;
        }
        .container {
            max-width: 800px;
        }
        .event-card {
            margin-bottom: 20px;
            padding: 20px;
            border-radius: 10px;
            background-color: white;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container">
        <a class="navbar-brand" href="#">Event System</a>
        <div class="ml-auto">
            <a href="logout.php" class="btn btn-outline-danger">Logout</a>
        </div>
    </div>
</nav>

<div class="container mt-5">
    <h2 class="text-center mb-4">Available Events</h2>
    <?php if ($result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="event-card">
                <h3><?= htmlspecialchars($row['event_name']) ?></h3>
                <p><strong>Date:</strong> <?= $row['event_date'] ?></p>
                <p><strong>Location:</strong> <?= htmlspecialchars($row['event_location']) ?></p>
                <p><?= htmlspecialchars($row['event_description']) ?></p>
                <a href="user_register_event.php?event_id=<?= $row['event_id'] ?>" class="btn btn-primary">Register</a>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p class="text-center">No events available at the moment.</p>
    <?php endif; ?>

    <div class="text-center mt-4">
        <a href="user_registered_events.php" class="btn btn-success">View Registered Events</a>
    </div>
</div>

</body>
</html>

<?php
$conn->close();
?>
