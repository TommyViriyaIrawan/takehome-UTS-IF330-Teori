<?php
session_start();
require 'config.php';

$user_id = $_GET['user_id'];

// Ambil daftar event yang diikuti oleh user
$stmt = $conn->prepare("SELECT e.event_name, e.event_date, e.event_location 
                        FROM registrations r
                        JOIN events e ON r.event_id = e.event_id
                        WHERE r.user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User's Events</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>User's Registered Events</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Event Name</th>
                    <th>Date</th>
                    <th>Location</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo $row['event_name']; ?></td>
                        <td><?php echo $row['event_date']; ?></td>
                        <td><?php echo $row['event_location']; ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</body>
</html>
