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
        $error_message = "<div class='alert alert-danger text-center'>Passwords do not match! Please try again.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .reset-container {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            text-align: center;
            width: 300px;
            position: absolute;
        }
        .btn-custom {
            width: 200px;
            margin: 10px;
        }
        .error-message {
            margin-top: 10px;
            width: 100%;
            position: relative;
            bottom: -10px;
        }
    </style>
</head>
<body>

    <div class="reset-container">
        <h2>Reset Your Password</h2>
        <form method="post" action="">
            <input type="password" name="new_password" placeholder="New Password" required class="form-control mb-3">
            <input type="password" name="confirm_password" placeholder="Confirm New Password" required class="form-control mb-3">
            <button type="submit" class="btn btn-primary btn-custom">Reset Password</button>
        </form>

        <!-- Div untuk menampilkan pesan error di bawah form -->
        <?php if (isset($error_message)): ?>
            <div class="error-message">
                <?php echo $error_message; ?>
            </div>
        <?php endif; ?>
    </div>

</body>
</html>
