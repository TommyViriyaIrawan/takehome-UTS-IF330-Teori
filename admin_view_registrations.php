<?php
require 'config.php';

$event_id = $_GET['event_id'];

$stmt = $conn->prepare("SELECT users.name, users.email FROM registrations JOIN users ON registrations.user_id = users.user_id WHERE registrations.event_id = ?");
$stmt->bind_param("i", $event_id);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    echo "<p>User: " . $row['name'] . " | Email: " . $row['email'] . "</p>";
}

$stmt->close();
$conn->close();
?>
