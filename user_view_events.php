<?php
session_start();
require 'config.php';

$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT events.event_name, events.event_date, events.event_location FROM registrations JOIN events ON registrations.event_id = events.event_id WHERE registrations.user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    echo "<p>Event: " . $row['event_name'] . " | Date: " . $row['event_date'] . " | Location: " . $row['event_location'] . "</p>";
}

$stmt->close();
$conn->close();
?>
