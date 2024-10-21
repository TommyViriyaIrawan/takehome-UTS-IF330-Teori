<?php
session_start();
require 'config.php';

// Cek apakah admin sudah login
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

$event_id = $_GET['event_id'];

// Ambil data event dari database berdasarkan event_id
$stmt = $conn->prepare("SELECT * FROM events WHERE event_id = ?");
$stmt->bind_param("i", $event_id);
$stmt->execute();
$result = $stmt->get_result();
$event = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Details</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .details-container {
            background-color: white;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        h2 {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

    <div class="details-container">
        <h2>Event Details</h2>
        <p><strong>Name:</strong> <?php echo $event['event_name']; ?></p>
        <p><strong>Date:</strong> <?php echo $event['event_date']; ?></p>
        <p><strong>Location:</strong> <?php echo $event['event_location']; ?></p>
        <p><strong>Description:</strong> <?php echo $event['event_description']; ?></p>
        <p><strong>Max Participants:</strong> <?php echo $event['max_participants']; ?></p>
        <p><strong>Status:</strong> <?php echo ucfirst($event['status']); ?></p>
        <a href="admin_dashboard.php" class="btn btn-primary">Back to Dashboard</a>
    </div>

</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
