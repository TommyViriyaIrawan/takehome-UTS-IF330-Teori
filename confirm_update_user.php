<?php
session_start();
require 'config.php';

if (isset($_POST['email']) && isset($_POST['password'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Fetch user data based on email
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    // Verify the password
    if ($user && password_verify($password, $user['password'])) {
        echo "success";
    } else {
        echo "error";
    }

    $stmt->close();
    $conn->close();
} else {
    echo "error";
}
?>
