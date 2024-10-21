<?php
session_start();
require 'config.php';

$user_id = $_SESSION['user_id'];
$name = $_POST['name'];
$email = $_POST['email'];
$password = $_POST['password'];

// Jika password diisi, enkripsi dan update, jika tidak hanya update nama dan email
if (!empty($password)) {
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("UPDATE users SET name = ?, email = ?, password = ? WHERE user_id = ?");
    $stmt->bind_param("sssi", $name, $email, $hashed_password, $user_id);
} else {
    $stmt = $conn->prepare("UPDATE users SET name = ?, email = ? WHERE user_id = ?");
    $stmt->bind_param("ssi", $name, $email, $user_id);
}

if ($stmt->execute()) {
    echo "Profile updated successfully!";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
