<?php
session_start();
require 'config.php';

// Cek apakah user sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$event_id = $_GET['event_id'];

// Cek apakah user sudah terdaftar di event
$checkStmt = $conn->prepare("SELECT * FROM registrations WHERE user_id = ? AND event_id = ?");
$checkStmt->bind_param("ii", $user_id, $event_id);
$checkStmt->execute();
$checkResult = $checkStmt->get_result();

$message = ""; // Variabel pesan untuk output

if ($checkResult->num_rows > 0) {
    $message = "You are already registered for this event!";
} else {
    // Tambahkan user ke event
    $stmt = $conn->prepare("INSERT INTO registrations (user_id, event_id) VALUES (?, ?)");
    $stmt->bind_param("ii", $user_id, $event_id);

    if ($stmt->execute()) {
        $message = "You have successfully registered for the event!";
    } else {
        $message = "Error: " . $stmt->error;
    }
    $stmt->close();
}

$checkStmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Registration</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .message-container {
            text-align: center;
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
    </style>
</head>
<body>

<div class="message-container">
    <h2><?= $message ?></h2>
    <a href="user_event_browsing.php" class="btn btn-primary mt-3">Browse Other Events</a>
</div>

</body>
</html>
