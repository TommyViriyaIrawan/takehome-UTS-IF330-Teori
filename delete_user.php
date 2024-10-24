<?php
session_start();
require 'config.php';

// Pastikan admin sudah login
if (!isset($_SESSION['admin_id'])) {
    echo "error";
    exit();
}

// Periksa apakah permintaan POST berisi user_id
if (isset($_POST['user_id'])) {
    $user_id = $_POST['user_id'];

    // Siapkan pernyataan untuk menghapus user berdasarkan user_id
    $stmt = $conn->prepare("DELETE FROM users WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);

    if ($stmt->execute()) {
        echo "success"; // Kembalikan respons sukses
    } else {
        echo "error"; // Kembalikan respons error
    }

    $stmt->close();
} else {
    echo "error";
}
?>
