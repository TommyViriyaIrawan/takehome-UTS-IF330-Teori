<?php
session_start();
require 'config.php';

// Ambil user_id dari session
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    // Query untuk mendapatkan event yang telah didaftarkan user
    $stmt = $conn->prepare("SELECT r.registration_id, e.event_name, e.event_date, e.event_location 
                            FROM registrations r
                            JOIN events e ON r.event_id = e.event_id
                            WHERE r.user_id = ?");
    
    if ($stmt) {
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        // Periksa apakah ada hasil dari query
        if ($result && $result->num_rows > 0) {
            ?>

            <!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Registered Events</title>
                <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
                <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
            </head>
            <body class="bg-light">

            <div class="container mt-5">
                <!-- Flexbox container untuk header -->
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h2>Your Registered Events</h2>
                    <!-- Tombol Back di kanan atas -->
                    <a href="user_event_browsing.php" class="btn btn-secondary">Back</a>
                </div>

                <div id="event-list" class="row">
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <div class="col-md-4">
                            <div class="card mb-3">
                                <div class="card-body">
                                    <h5 class="card-title"><?php echo $row['event_name']; ?></h5>
                                    <p class="card-text">
                                        <strong>Date:</strong> <?php echo $row['event_date']; ?><br>
                                        <strong>Location:</strong> <?php echo $row['event_location']; ?>
                                    </p>
                                    <button class="btn btn-danger cancel-btn" 
                                            data-registration-id="<?php echo $row['registration_id']; ?>">
                                        Cancel
                                    </button>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>

            <!-- Pop-up Konfirmasi -->
            <div id="confirmPopup" class="modal" tabindex="-1" role="dialog" style="display: none;">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Are you sure?</h5>
                        </div>
                        <div class="modal-body">
                            <p>Do you really want to cancel the registration?</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" id="yesBtn">Yes</button>
                            <button type="button" class="btn btn-secondary" id="closePopup">No</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form untuk input Email dan Password -->
            <div id="loginForm" class="modal" tabindex="-1" role="dialog" style="display: none;">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Confirm Cancellation</h5>
                        </div>
                        <div class="modal-body">
                            <p>Please enter your email and password to confirm:</p>
                            <input type="email" id="email" class="form-control mb-2" placeholder="Email">
                            <input type="password" id="password" class="form-control" placeholder="Password">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" id="confirmCancel">Submit</button>
                            <button type="button" class="btn btn-secondary" id="cancelForm">Cancel</button>
                        </div>
                    </div>
                </div>
            </div>

            <script>
            $(document).ready(function() {
                let registrationId = null;

                // Open pop-up when clicking Cancel
                $(".cancel-btn").on("click", function() {
                    registrationId = $(this).data("registration-id");
                    $("#confirmPopup").fadeIn(); // Tampilkan pop-up pertama
                });

                // Close pop-up
                $("#closePopup").on("click", function() {
                    $("#confirmPopup").fadeOut();
                });

                // When "Yes" is clicked, show the email/password form
                $("#yesBtn").on("click", function() {
                    $("#confirmPopup").fadeOut();  // Tutup pop-up konfirmasi
                    $("#loginForm").fadeIn();      // Tampilkan form login
                });

                // Close form input
                $("#cancelForm").on("click", function() {
                    $("#loginForm").fadeOut(); // Tutup form input email dan password
                });

                // Confirm cancellation via AJAX
                $("#confirmCancel").on("click", function() {
                    const email = $("#email").val();
                    const password = $("#password").val();

                    $.ajax({
                        url: "cancel_registration.php",
                        type: "POST",
                        data: {
                            registration_id: registrationId,
                            email: email,
                            password: password
                        },
                        success: function(response) {
                            if (response === "success") {
                                alert("Registration cancelled successfully.");
                                // Remove the cancelled event card from the DOM
                                $("button[data-registration-id='" + registrationId + "']")
                                    .closest(".col-md-4").remove();
                            } else {
                                alert("Invalid email or password.");
                            }
                            $("#loginForm").fadeOut();
                        }
                    });
                });
            });
            </script>

            </body>
            </html>

            <?php
        } else {
            echo "<p>No registered events found.</p>";
        }

        $stmt->close();
    } else {
        // Jika query gagal, tampilkan pesan error
        echo "Error preparing statement: " . $conn->error;
    }
} else {
    echo "User ID is not set.";
}

$conn->close();
?>
