<?php
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $admin_name = $_POST['admin_name'];
    $admin_email = $_POST['admin_email'];
    $admin_password = password_hash($_POST['admin_password'], PASSWORD_BCRYPT);

    // Prepare statement to insert new admin
    $stmt = $conn->prepare("INSERT INTO admins (name, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $admin_name, $admin_email, $admin_password);

    if ($stmt->execute()) {
        // Redirect to login page after successful registration
        header("Location: admin_login.php");
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
    <title>Admin Register</title>
</head>
<body>
    <h2>Admin Registration</h2>
    <form method="post" action="">
        <input type="text" name="admin_name" placeholder="Name" required>
        <input type="email" name="admin_email" placeholder="Email" required>
        <input type="password" name="admin_password" placeholder="Password" required>
        <button type="submit">Register</button>
    </form>
</body>
</html>
