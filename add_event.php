<?php
session_start();
require 'config.php';

// Pastikan admin sudah login
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

// Inisialisasi variabel error dan success
$error = '';
$success = '';

// Proses form saat data dikirim
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $event_name = $_POST['event_name'];
    $event_date = $_POST['event_date'];
    $event_time = $_POST['event_time'];
    $event_location = $_POST['event_location'];
    $event_description = $_POST['event_description'];
    $max_participants = $_POST['max_participants'];

    // Validasi input
    if (empty($event_name) || empty($event_date) || empty($event_time) || empty($event_location) || empty($event_description) || empty($max_participants)) {
        $error = 'Please fill out all required fields.';
    } else {
        // Proses upload gambar jika ada
        $image = '';
        if (!empty($_FILES['event_image']['name'])) {
            $target_dir = "uploads/";
            $image = basename($_FILES["event_image"]["name"]);
            $target_file = $target_dir . $image;
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

            // Validasi tipe file gambar
            $valid_extensions = ['jpg', 'png', 'jpeg', 'gif'];
            if (!in_array($imageFileType, $valid_extensions)) {
                $error = "Only JPG, JPEG, PNG & GIF files are allowed.";
            } else {
                // Pindahkan file ke folder uploads
                if (!move_uploaded_file($_FILES["event_image"]["tmp_name"], $target_file)) {
                    $error = "Sorry, there was an error uploading your file.";
                }
            }
        }

        // Jika tidak ada error, insert data ke database
        if (empty($error)) {
            $stmt = $conn->prepare("INSERT INTO events (event_name, event_date, event_time, event_location, event_description, max_participants, event_image) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssssis", $event_name, $event_date, $event_time, $event_location, $event_description, $max_participants, $image);

            if ($stmt->execute()) {
                $success = "Event added successfully!";
            } else {
                $error = "Failed to add event. Please try again.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Event</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Add New Event</h2>

        <?php if (!empty($error)) { ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php } ?>

        <?php if (!empty($success)) { ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
        <?php } ?>

        <form action="add_event.php" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="event_name">Event Name</label>
                <input type="text" class="form-control" id="event_name" name="event_name" required>
            </div>

            <div class="form-group">
                <label for="event_date">Event Date</label>
                <input type="date" class="form-control" id="event_date" name="event_date" required>
            </div>

            <div class="form-group">
                <label for="event_time">Event Time</label>
                <input type="time" class="form-control" id="event_time" name="event_time" required>
            </div>

            <div class="form-group">
                <label for="event_location">Event Location</label>
                <input type="text" class="form-control" id="event_location" name="event_location" required>
            </div>

            <div class="form-group">
                <label for="event_description">Event Description</label>
                <textarea class="form-control" id="event_description" name="event_description" rows="4" required></textarea>
            </div>

            <div class="form-group">
                <label for="max_participants">Max Participants</label>
                <input type="number" class="form-control" id="max_participants" name="max_participants" required>
            </div>

            <div class="form-group">
                <label for="event_image">Event Banner (Optional)</label>
                <input type="file" class="form-control-file" id="event_image" name="event_image">
            </div>

            <button type="submit" class="btn btn-primary">Add Event</button>
            <a href="admin_dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
        </form>
    </div>
</body>
</html>
