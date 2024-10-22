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
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .register-container {
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
    </style>
</head>
<body>

    <div class="register-container">
        <h2>Admin Registration</h2>
        <form method="post" action="">
            <input type="text" name="admin_name" placeholder="Name" required class="form-control mb-3">
            <input type="email" name="admin_email" placeholder="Email" required class="form-control mb-3">
            <input type="password" name="admin_password" placeholder="Password" required class="form-control mb-3">
            <button type="submit" class="btn btn-primary btn-custom">Register</button>
        </form>

        <!-- Link to Login -->
        <p class="mt-3">Sudah punya akun? 
            <a href="admin_login.php" class="text-primary">Login</a>
        </p>
    </div>

</body>
</html>
