<?php
session_start();
require 'config.php';

// Cek apakah admin sudah login
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $event_name = $_POST['event_name'];
    $event_date = $_POST['event_date'];
    $event_time = $_POST['event_time'];
    $event_location = $_POST['event_location'];
    $event_description = $_POST['event_description'];
    $max_participants = $_POST['max_participants'];
    $status = 'open'; // Status default

    // Mengunggah file gambar/banner
    $target_dir = "uploads/";
    $event_image = basename($_FILES["event_image"]["name"]);
    $target_file = $target_dir . $event_image;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Validasi: Pastikan file adalah gambar
    $check = getimagesize($_FILES["event_image"]["tmp_name"]);
    if ($check === false) {
        die("File yang diunggah bukan gambar.");
    }

    // Batasi tipe file gambar (jpg, jpeg, png, gif)
    if (!in_array($imageFileType, ['jpg', 'jpeg', 'png', 'gif'])) {
        die("Hanya file JPG, JPEG, PNG, dan GIF yang diizinkan.");
    }

    // Pindahkan file ke folder 'uploads'
    if (!move_uploaded_file($_FILES["event_image"]["tmp_name"], $target_file)) {
        die("Gagal mengunggah gambar.");
    }

    // Simpan data event ke database
    $stmt = $conn->prepare(
        "INSERT INTO events (event_name, event_date, event_time, event_location, event_description, max_participants, event_image, status) 
         VALUES (?, ?, ?, ?, ?, ?, ?, ?)"
    );
    $stmt->bind_param("ssssisss", $event_name, $event_date, $event_time, $event_location, $event_description, $max_participants, $event_image, $status);

    if ($stmt->execute()) {
        // Redirect ke dashboard setelah berhasil
        header("Location: admin_dashboard.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Event</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .form-container {
            max-width: 600px;
            margin: 50px auto;
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        .btn-primary {
            width: 100%;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="form-container">
        <h2>Add New Event</h2>
        <form method="post" action="" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="event_name" class="form-label">Event Name</label>
                <input type="text" class="form-control" id="event_name" name="event_name" required>
            </div>
            <div class="mb-3">
                <label for="event_date" class="form-label">Event Date</label>
                <input type="date" class="form-control" id="event_date" name="event_date" required>
            </div>
            <div class="mb-3">
                <label for="event_time" class="form-label">Event Time</label>
                <input type="time" class="form-control" id="event_time" name="event_time" required>
            </div>
            <div class="mb-3">
                <label for="event_location" class="form-label">Location</label>
                <input type="text" class="form-control" id="event_location" name="event_location" required>
            </div>
            <div class="mb-3">
                <label for="event_description" class="form-label">Event Description</label>
                <textarea class="form-control" id="event_description" name="event_description" rows="3" required></textarea>
            </div>
            <div class="mb-3">
                <label for="max_participants" class="form-label">Max Participants</label>
                <input type="number" class="form-control" id="max_participants" name="max_participants" required>
            </div>
            <div class="mb-3">
                <label for="event_image" class="form-label">Event Banner Image</label>
                <input type="file" class="form-control" id="event_image" name="event_image" required>
            </div>
            <button type="submit" class="btn btn-primary">Add Event</button>
        </form>
    </div>
</div>

</body>
</html>
