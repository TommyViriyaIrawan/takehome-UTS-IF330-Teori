<?php
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];

    // Cek apakah username dan email ada dalam database
    $stmt = $conn->prepare("SELECT * FROM users WHERE name = ? AND email = ?");
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        // Jika ada, redirect ke halaman reset password
        session_start();
        $_SESSION['reset_user'] = $username;
        header("Location: reset_password.php");
        exit();
    } else {
        $error_message = "<div class='alert alert-danger text-center'>Username atau email tidak ditemukan!</div>";
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
    <title>Forgot Password</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .forgot-container {
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

    <div class="forgot-container">
        <h2>Forgot Password</h2>
        <form method="post" action="">
            <input type="text" name="username" placeholder="Username" required class="form-control mb-3">
            <input type="email" name="email" placeholder="Email" required class="form-control mb-3">
            <button type="submit" class="btn btn-primary btn-custom">Submit</button>
        </form>
        <a href="login.php" class="btn btn-link">Back to Login</a>

        <!-- Div untuk menampilkan pesan error di bawah form -->
        <?php if (isset($error_message)): ?>
            <div class="error-message">
                <?php echo $error_message; ?>
            </div>
        <?php endif; ?>
    </div>

</body>
</html>
