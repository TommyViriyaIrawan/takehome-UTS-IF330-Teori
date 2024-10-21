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
    $event_location = $_POST['event_location'];
    $event_description = $_POST['event_description'];
    $max_participants = $_POST['max_participants'];
    $status = 'open'; // Default status

    // Simpan event baru ke database
    $stmt = $conn->prepare("INSERT INTO events (event_name, event_date, event_location, event_description, max_participants, status) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssis", $event_name, $event_date, $event_location, $event_description, $max_participants, $status);

    if ($stmt->execute()) {
        // Redirect ke event_dashboard.php setelah event berhasil ditambahkan
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
</head>
<body>
    <h2>Add New Event</h2>
    <form method="post" action="">
        <input type="text" name="event_name" placeholder="Event Name" required>
        <input type="date" name="event_date" placeholder="Event Date" required>
        <input type="text" name="event_location" placeholder="Location" required>
        <textarea name="event_description" placeholder="Event Description" required></textarea>
        <input type="number" name="max_participants" placeholder="Max Participants" required>
        <button type="submit">Add Event</button>
    </form>
</body>
</html>
