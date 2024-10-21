<?php
session_start();
require 'config.php';

$user_id = $_SESSION['user_id'];
$event_id = $_POST['event_id'];
$email = $_POST['email'];
$password = $_POST['password'];

// Verifikasi email dan password
$stmt = $conn->prepare("SELECT password FROM users WHERE user_id = ? AND email = ?");
$stmt->bind_param("is", $user_id, $email);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($user && password_verify($password, $user['password'])) {
    // Hapus pendaftaran
    $deleteStmt = $conn->prepare("DELETE FROM registrations WHERE user_id = ? AND event_id = ?");
    $deleteStmt->bind_param("ii", $user_id, $event_id);
    if ($deleteStmt->execute()) {
        echo "Registration cancelled successfully.";
    } else {
        echo "Error: " . $deleteStmt->error;
    }
    $deleteStmt->close();
} else {
    echo "Invalid email or password.";
}

$stmt->close();
$conn->close();
?>
