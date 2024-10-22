<?php
session_start();
require 'config.php';

$error_message = ""; // Variabel untuk menyimpan pesan error

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $admin_email = $_POST['admin_email'];
    $admin_password = $_POST['admin_password'];

    // Cek apakah email ada di database admin
    $stmt = $conn->prepare("SELECT * FROM admins WHERE email = ?");
    $stmt->bind_param("s", $admin_email);
    $stmt->execute();
    $result = $stmt->get_result();
    $admin = $result->fetch_assoc();

    if ($admin && password_verify($admin_password, $admin['password'])) {
        // Simpan admin_id ke session setelah berhasil login
        $_SESSION['admin_id'] = $admin['admin_id'];
        header("Location: admin_dashboard.php");
        exit();
    } else {
        $error_message = "<div class='alert alert-danger text-center'>Invalid email or password!</div>";
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
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .login-container {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            text-align: center;
            width: 300px;
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

    <div class="login-container">
        <h2>Admin Login</h2>
        <form method="post" action="">
            <input type="email" name="admin_email" placeholder="Email" required class="form-control mb-3">
            <input type="password" name="admin_password" placeholder="Password" required class="form-control mb-3">
            <button type="submit" class="btn btn-primary btn-custom">Login</button>
        </form>
        <a href="forgot_password.php" class="btn btn-link">Forgot Password?</a>

        <!-- Div untuk menampilkan pesan error di bawah login container -->
        <?php if ($error_message != ""): ?>
            <div class="error-message">
                <?php echo $error_message; ?>
            </div>
        <?php endif; ?>
    </div>

</body>
</html>
