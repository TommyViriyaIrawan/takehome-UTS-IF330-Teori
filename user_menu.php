<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Menu</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .menu-container {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            text-align: center;
            width: 350px;
        }
        .btn-custom {
            width: 150px;
            margin: 10px;
        }
    </style>
</head>
<body>

    <div class="menu-container">
        <h2>Welcome to User Menu</h2>
        <p>Please choose your action:</p>
        <div class="d-flex justify-content-around">
            <a href="login.php" class="btn btn-primary btn-custom">Login</a>
            <a href="register.php" class="btn btn-success btn-custom">Register</a>
        </div>
    </div>

</body>
</html>
