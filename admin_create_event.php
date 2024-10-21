<?php
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['event_name'];
    $date = $_POST['event_date'];
    $time = $_POST['event_time'];
    $location = $_POST['event_location'];
    $description = $_POST['event_description'];
    $max_participants = $_POST['max_participants'];
    $status = 'open'; // Default status

    $stmt = $conn->prepare("INSERT INTO events (event_name, event_date, event_time, event_location, event_description, max_participants, status) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssis", $name, $date, $time, $location, $description, $max_participants, $status);

    if ($stmt->execute()) {
        echo "Event created successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
<form method="post" action="">
    <input type="text" name="event_name" placeholder="Event Name" required>
    <input type="date" name="event_date" placeholder="Event Date" required>
    <input type="time" name="event_time" placeholder="Event Time" required>
    <input type="text" name="event_location" placeholder="Location" required>
    <textarea name="event_description" placeholder="Event Description" required></textarea>
    <input type="number" name="max_participants" placeholder="Max Participants" required>
    <button type="submit">Create Event</button>
</form>
