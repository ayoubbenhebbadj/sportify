<?php
include("../phpConfig/constants.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $user_id = $_POST['user_id'];
    $start_date = $_POST['start_date'];
    $end_date = isset($_POST['end_date']) ? $_POST['end_date'] : $start_date;
    $time_slot = $_POST['time_slot']; // Format: H:i
    $duration = floatval($_POST['duration']); // e.g., 1.5
    $infrastructure_id = $_POST['infrastructure_id'];

    // Calculate end time
    $start_time = DateTime::createFromFormat('H:i', $time_slot);
    $hours = floor($duration);
    $minutes = ($duration - $hours) * 60;

    $end_time = clone $start_time;
    $end_time->add(new DateInterval('PT' . $hours . 'H' . $minutes . 'M'));

    $start_time_str = $start_time->format("H:i");
    $end_time_str = $end_time->format("H:i");

    // Insert only once for the whole range
    $stmt = $conn->prepare("
        INSERT INTO reservations (user_id, infrastructure_id, start_date, end_date, time_slot, end_time, status) 
        VALUES (?, ?, ?, ?, ?, ?, 'pending')
    ");

    $stmt->bind_param(
        "iissss",
        $user_id,
        $infrastructure_id,
        $start_date,
        $end_date,
        $start_time_str,
        $end_time_str
    );

    if ($stmt->execute()) {
        echo "Reservation saved successfully.";
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>
