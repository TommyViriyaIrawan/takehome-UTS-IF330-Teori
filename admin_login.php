<?php
session_start();
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $admin_email = $_POST['admin_email'];
    $admin_password = $_POST['admin_password'];

    // Check if the email and password match
    $stmt = $conn->prepare("SELECT * FROM admins WHERE email = ?");
    $stmt->bind_param("s", $admin_email);
    $stmt->execute();
    $result = $stmt->get_result();
    $admin = $result->fetch_assoc();

    if ($admin && password_verify($admin_password, $admin['password'])) {
        // Set session and redirect to admin dashboard
        $_SESSION['admin_id'] = $admin['admin_id'];
        header("Location: admin_dashboard.php");
        exit();
    } else {
        echo "Invalid email or password!";
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
    <title>Admin Login</title>
</head>
<body>
    <h2>Admin Login</h2>
    <form method="post" action="">
        <input type="email" name="admin_email" placeholder="Email" required>
        <input type="password" name="admin_password" placeholder="Password" required>
        <button type="submit">Login</button>
    </form>
</body>
</html>
