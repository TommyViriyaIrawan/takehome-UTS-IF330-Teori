<?php
session_start();
require 'config.php';

// Cek apakah user sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Ambil event yang telah didaftarkan user
$sql = "SELECT e.event_id, e.event_name, e.event_date, e.event_location 
        FROM registrations r 
        JOIN events e ON r.event_id = e.event_id 
        WHERE r.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registered Events</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <style>
        body {
            background-color: #f8f9fa;
            padding-top: 50px;
        }
        .container {
            max-width: 800px;
        }
        .event-card {
            margin-bottom: 20px;
            padding: 20px;
            border-radius: 10px;
            background-color: white;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        /* Pop-up Modal Styling */
        .modal {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 1050;
            background-color: white;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            border-radius: 8px;
            width: 300px;
        }
        .modal-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1040;
        }
    </style>
</head>
<body>

<div class="container mt-5">
    <h2 class="text-center mb-4">Your Registered Events</h2>
    <?php if ($result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="event-card">
                <h3><?= htmlspecialchars($row['event_name']) ?></h3>
                <p><strong>Date:</strong> <?= $row['event_date'] ?></p>
                <p><strong>Location:</strong> <?= htmlspecialchars($row['event_location']) ?></p>
                <button class="btn btn-danger cancel-btn" data-event-id="<?= $row['event_id'] ?>">Cancel</button>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p class="text-center">You have not registered for any events yet.</p>
    <?php endif; ?>
</div>

<!-- Pop-up Konfirmasi -->
<div class="modal-overlay"></div>
<div class="modal" id="confirmationModal">
    <h4>Are you sure?</h4>
    <form id="cancelForm" method="POST" action="cancel_registration.php">
        <input type="hidden" name="event_id" id="event_id">
        <input type="email" name="email" placeholder="Email" required class="form-control mb-2">
        <input type="password" name="password" placeholder="Password" required class="form-control mb-2">
        <button type="submit" class="btn btn-danger">Yes</button>
        <button type="button" class="btn btn-secondary close-modal">No</button>
    </form>
</div>

<script>
$(document).ready(function() {
    $('.cancel-btn').on('click', function() {
        const eventId = $(this).data('event-id');
        $('#event_id').val(eventId);
        $('.modal-overlay').show();  // Tampilkan overlay
        $('#confirmationModal').fadeIn();  // Tampilkan modal dengan animasi
    });

    $('.close-modal').on('click', function() {
        $('#confirmationModal').fadeOut();  // Sembunyikan modal dengan animasi
        $('.modal-overlay').fadeOut();  // Sembunyikan overlay
    });
});
</script>

</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
