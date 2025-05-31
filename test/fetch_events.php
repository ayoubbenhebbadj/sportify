<?php
include("../phpConfig/constants.php");

$sql = "SELECT r.*, u.username, i.name AS infrastructure_name
        FROM reservations r
        JOIN user u ON r.user_id = u.id
        JOIN infrastructure i ON r.infrastructure_id = i.id";

$result = $conn->query($sql);
$events = [];

while ($row = $result->fetch_assoc()) {
    $color = "#f1c40f"; // yellow
    if ($row['status'] === "Approved") $color = "#2ecc71"; // green
    if ($row['status'] === "Rejected") $color = "#e74c3c"; // red
    if ($row['status'] === "Cancelled") $color = "#7f8c8d"; // grey

    $events[] = [
        'id' => $row['id'],
        'title' => $row['username'] . " (" . $row['status'] . ")",
        'start' => $row['start_date'] . "T" . $row['time_slot'],
        'end' => $row['start_date'] . "T" . $row['end_time'],
        'color' => $color,
        'username' => $row['username'],
        'infrastructure' => $row['infrastructure_name'],
        'status' => $row['status']
    ];
}

echo json_encode($events);
$conn->close();
?>
