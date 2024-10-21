<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "event_registration";

// Membuat koneksi
$conn = new mysqli($servername, $username, $password, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set karakter encoding agar mendukung UTF-8
$conn->set_charset("utf8mb4");
?>
