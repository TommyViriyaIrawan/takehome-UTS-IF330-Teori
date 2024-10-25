<?php
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_id'])) {
    $user_id = $_POST['user_id'];

    // Start transaction
    $conn->begin_transaction();

    try {
        // Delete registrations associated with the user
        $stmt = $conn->prepare("DELETE FROM registrations WHERE user_id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $stmt->close();

        // Delete the user
        $stmt = $conn->prepare("DELETE FROM users WHERE user_id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $stmt->close();

        // Commit transaction
        $conn->commit();
        echo "success";
    } catch (Exception $e) {
        // Rollback transaction if something failed
        $conn->rollback();
        echo "error";
    }
}
$conn->close();
?>
