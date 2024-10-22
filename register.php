<?php
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $email, $password);

    if ($stmt->execute()) {
        header("Location: login.php");
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
    <title>User Register</title>
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
        <h2>User Registration</h2>
        <form method="post" action="">
            <input type="text" name="name" placeholder="Name" required class="form-control mb-3">
            <input type="email" name="email" placeholder="Email" required class="form-control mb-3">
            <input type="password" name="password" placeholder="Password" required class="form-control mb-3">
            <button type="submit" class="btn btn-primary btn-custom">Register</button>
        </form>

        <!-- Link to Login -->
        <p class="mt-3">Already have an account? 
            <a href="login.php" class="text-primary">Login</a>
        </p>
    </div>

</body>
</html>
