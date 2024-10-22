<?php
session_start();
require 'config.php';

// Pastikan admin login
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

// Function untuk mengekspor data ke CSV
function exportToCSV($filename, $data) {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment;filename="' . $filename . '"');

    $output = fopen("php://output", "w");
    foreach ($data as $row) {
        fputcsv($output, $row);
    }
    fclose($output);
    exit();
}

// Cek jika tombol ekspor CSV ditekan
if (isset($_GET['export_registrations'])) {
    $event_id = $_GET['event_id'];
    $stmt = $conn->prepare("SELECT u.name, u.email FROM registrations r JOIN users u ON r.user_id = u.user_id WHERE r.event_id = ?");
    $stmt->bind_param("i", $event_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $registrants = [];
    while ($row = $result->fetch_assoc()) {
        $registrants[] = [$row['name'], $row['email']];
    }

    exportToCSV("event_registrations.csv", $registrants);
}

// Menampilkan daftar event dan pendaftar
$sql_events = "SELECT * FROM events";
$result_events = $conn->query($sql_events);

$sql_users = "SELECT * FROM users";
$result_users = $conn->query($sql_users);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div class="container mt-5">
        <h2>Admin Dashboard</h2>

        <!-- View Event Registrations -->
        <h4>View Event Registrations</h4>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Event Name</th>
                    <th>Date</th>
                    <th>Location</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result_events->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo $row['event_name']; ?></td>
                        <td><?php echo $row['event_date']; ?></td>
                        <td><?php echo $row['event_location']; ?></td>
                        <td>
                            <!-- List Registrants -->
                            <a href="view_registrants.php?event_id=<?php echo $row['event_id']; ?>" class="btn btn-info">View Registrants</a>

                            <!-- Export to CSV -->
                            <a href="admin_dashboard.php?export_registrations=1&event_id=<?php echo $row['event_id']; ?>" class="btn btn-success">Export to CSV</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

        <!-- User Management -->
        <h4>User Management</h4>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result_users->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo $row['name']; ?></td>
                        <td><?php echo $row['email']; ?></td>
                        <td>
                            <!-- View User's Event Registrations -->
                            <a href="view_user_events.php?user_id=<?php echo $row['user_id']; ?>" class="btn btn-info">View User Events</a>
                            <!-- Delete User -->
                            <button class="btn btn-danger delete-user-btn" data-user-id="<?php echo $row['user_id']; ?>">Delete</button>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <script>
        // Fungsi untuk menghapus user dengan AJAX
        $(".delete-user-btn").on("click", function () {
            const userId = $(this).data("user-id");
            if (confirm("Are you sure you want to delete this user?")) {
                $.ajax({
                    url: "delete_user.php",
                    type: "POST",
                    data: { user_id: userId },
                    success: function (response) {
                        if (response === "success") {
                            alert("User deleted successfully.");
                            location.reload();
                        } else {
                            alert("Failed to delete user.");
                        }
                    }
                });
            }
        });
    </script>
</body>
</html>
