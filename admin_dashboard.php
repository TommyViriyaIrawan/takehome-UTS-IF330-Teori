<?php
session_start();
require 'config.php';

// Cek apakah admin sudah login
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

// Ambil daftar event dari database
$stmt = $conn->prepare("SELECT * FROM events");
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Event Dashboard</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .dashboard-container {
            padding: 30px;
            margin-top: 50px;
        }
        h1 {
            margin-bottom: 30px;
        }
        .table {
            margin-top: 20px;
        }
        .btn-custom {
            margin-right: 10px;
        }
    </style>
</head>
<body>

    <div class="container dashboard-container">
        <h1>Admin Event Dashboard</h1>
        <a href="add_event.php" class="btn btn-success btn-custom">Add New Event</a>
        <a href="index.php" class="btn btn-danger btn-custom">Logout</a>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Event Name</th>
                    <th>Date</th>
                    <th>Location</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()) : ?>
                    <tr>
                        <td><?php echo $row['event_name']; ?></td>
                        <td><?php echo $row['event_date']; ?></td>
                        <td><?php echo $row['event_location']; ?></td>
                        <td><?php echo ucfirst($row['status']); ?></td>
                        <td>
                            <a href="edit_event.php?event_id=<?php echo $row['event_id']; ?>" class="btn btn-primary btn-sm">Edit</a>
                            <a href="delete_event.php?event_id=<?php echo $row['event_id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this event?');">Delete</a>
                            <a href="view_event.php?event_id=<?php echo $row['event_id']; ?>" class="btn btn-info btn-sm">View Details</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
