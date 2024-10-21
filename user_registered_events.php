<?php
session_start();
require 'config.php';

// Ambil data registrasi untuk user tertentu
$user_id = $_SESSION['user_id'];
$query = "SELECT events.event_id, events.event_name, events.event_date, events.event_location 
          FROM registrations 
          JOIN events ON registrations.event_id = events.event_id 
          WHERE registrations.user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Registered Events</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body class="bg-light">

<div class="container mt-5">
    <h2 class="mb-4">Your Registered Events</h2>
    <div class="row">
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="col-md-4">
                <div class="card mb-3">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $row['event_name']; ?></h5>
                        <p class="card-text">
                            <strong>Date:</strong> <?php echo $row['event_date']; ?><br>
                            <strong>Location:</strong> <?php echo $row['event_location']; ?>
                        </p>
                        <button class="btn btn-danger cancel-btn" data-event-id="<?php echo $row['event_id']; ?>">
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</div>

<!-- Modal Overlay -->
<div class="modal-overlay"></div>

<!-- Pop-up Modal -->
<div class="custom-modal" id="confirmationModal">
    <div class="modal-content">
        <div class="modal-icon">
            <span>&#33;</span> <!-- Exclamation mark -->
        </div>
        <h4>Are you sure?</h4>
        <div class="modal-buttons">
            <button class="btn btn-secondary close-modal">No</button>
            <button id="confirmYes" class="btn btn-warning">Yes</button>
        </div>

        <!-- Form Email & Password (Awalnya Tersembunyi) -->
        <form id="authForm" class="hidden mt-2" method="POST" action="cancel_registration.php">
            <input type="hidden" name="event_id" id="event_id">
            <input type="email" name="email" placeholder="Email" required class="form-control mb-2">
            <input type="password" name="password" placeholder="Password" required class="form-control mb-2">
            <button type="submit" class="btn btn-danger btn-block">Confirm Cancel</button>
        </form>
    </div>
</div>

<style>
    /* Overlay Styling */
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

    /* Modal Box Styling */
    .custom-modal {
        display: none;
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background-color: white;
        border-radius: 12px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        width: 300px;
        padding: 20px;
        text-align: center;
        z-index: 1050;
    }

    .modal-content {
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .modal-icon {
        background-color: #ffc107;
        color: white;
        border-radius: 50%;
        width: 40px;
        height: 40px;
        display: flex;
        justify-content: center;
        align-items: center;
        font-size: 24px;
        margin-bottom: 10px;
    }

    .modal-buttons {
        display: flex;
        justify-content: space-between;
        width: 100%;
        margin-top: 10px;
    }

    .modal-buttons .btn {
        width: 45%;
    }

    .hidden {
        display: none;
    }
</style>

<script>
    $(document).ready(function () {
        // Saat tombol Cancel ditekan, tampilkan modal
        $('.cancel-btn').on('click', function () {
            const eventId = $(this).data('event-id');
            $('#event_id').val(eventId);  // Set event_id di form
            $('.modal-overlay').fadeIn();
            $('#confirmationModal').fadeIn();
        });

        // Saat tombol No ditekan, tutup modal
        $('.close-modal').on('click', function () {
            $('#confirmationModal').fadeOut();
            $('.modal-overlay').fadeOut();
        });

        // Saat tombol Yes ditekan, tampilkan form email dan password
        $('#confirmYes').on('click', function () {
            $('#authForm').removeClass('hidden');  // Tampilkan form
        });
    });
</script>

</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
