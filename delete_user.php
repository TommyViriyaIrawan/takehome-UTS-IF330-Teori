<?php
session_start();
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_POST['user_id'];

    // Hapus user dan semua pendaftarannya
    $stmt = $conn->prepare("DELETE r, u FROM registrations r JOIN users u ON r.user_id = u.user_id WHERE u.user_id = ?");
    $stmt->bind_param("i", $user_id);

    if ($stmt->execute()) {
        echo "success";
    } else {
        echo "error";
    }
}
?>
