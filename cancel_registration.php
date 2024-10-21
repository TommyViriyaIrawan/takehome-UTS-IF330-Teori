<?php
session_start();
require 'config.php';

$registration_id = $_POST['registration_id'];
$email = $_POST['email'];
$password = $_POST['password'];

// Verifikasi email dan password user
$stmt = $conn->prepare("SELECT password FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();
    // Verifikasi password
    if (password_verify($password, $user['password'])) {
        // Hapus registrasi
        $deleteStmt = $conn->prepare("DELETE FROM registrations WHERE registration_id = ?");
        $deleteStmt->bind_param("i", $registration_id);
        if ($deleteStmt->execute()) {
            echo "success";
        } else {
            echo "error";
        }
        $deleteStmt->close();
    } else {
        echo "invalid";
    }
} else {
    echo "invalid";
}

$stmt->close();
$conn->close();
?>
