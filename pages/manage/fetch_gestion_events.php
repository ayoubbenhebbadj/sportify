<?php
include("../../phpConfig/constants.php");

header('Content-Type: application/json');

$infrastructure_id = isset($_GET['infrastructure_id']) ? intval($_GET['infrastructure_id']) : 0;

if ($infrastructure_id > 0) {
    $sql = "SELECT r.*, u.username, i.name AS infrastructure
            FROM reservations r
            JOIN user u ON r.user_id = u.id
            JOIN infrastructure i ON r.infrastructure_id = i.id
            WHERE r.infrastructure_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $infrastructure_id);
} else {
    $sql = "SELECT r.*, u.username, i.name AS infrastructure
            FROM reservations r
            JOIN user u ON r.user_id = u.id
            JOIN infrastructure i ON r.infrastructure_id = i.id";
    $stmt = $conn->prepare($sql);
}

$stmt->execute();
$result = $stmt->get_result();

$events = [];

while ($row = $result->fetch_assoc()) {
    // Use the correct color mapping and status values
    $color = match ($row['status']) {
        'Pending' => 'yellow',
        'Approved' => 'green',
        'Rejected' => 'red',
        'Cancelled' => 'gray',
        default => 'gray'
    };

    $events[] = [
        'id' => $row['id'],
        'title' => $row['username'],
        'start' => $row['start_date'] . 'T' . $row['time_slot'],
        'end' => $row['start_date'] . 'T' . $row['end_time'],
        'color' => $color,
        'username' => $row['username'],
        'user_id' => $row['user_id'],
        'infrastructure' => $row['infrastructure'],
        'status' => $row['status'],
        'duration' => $row['duration'],
        'infrastructure_id' => $row['infrastructure_id'],
        'can_manage' => true // Always true for admin, add logic for gestion if needed
    ];
}

echo json_encode($events);
$stmt->close();
$conn->close();
?>
