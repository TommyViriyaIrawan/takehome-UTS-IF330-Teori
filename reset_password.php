<?php
session_start();
require 'config.php';

// Check if reset_user session exists (valid user for resetting password)
if (!isset($_SESSION['reset_user'])) {
    header("Location: forgot_password.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Check if new password and confirm password match
    if ($new_password === $confirm_password) {
        // Encrypt the new password
        $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);
        $username = $_SESSION['reset_user'];

        // Update the password in the database
        $stmt = $conn->prepare("UPDATE users SET password = ? WHERE name = ?");
        $stmt->bind_param("ss", $hashed_password, $username);

        if ($stmt->execute()) {
            echo "Password successfully reset!";
            // After resetting, unset session and redirect to login page
            unset($_SESSION['reset_user']);
            header("Location: login.php");
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
        $conn->close();
    } else {
        echo "Passwords do not match! Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
</head>
<body>
    <h2>Reset Your Password</h2>
    <form method="post" action="">
        <input type="password" name="new_password" placeholder="New Password" required>
        <input type="password" name="confirm_password" placeholder="Confirm New Password" required>
        <button type="submit">Reset Password</button>
    </form>
</body>
</html>
