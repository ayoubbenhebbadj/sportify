<?php
include("../../phpConfig/constants.php");
header('Content-Type: application/json');

// Remove session check for testing
// if (!isset($_SESSION['user_id'])) {
//     echo json_encode([]);
//     exit();
// }

$infrastructure_id = isset($_GET['infrastructure_id']) ? intval($_GET['infrastructure_id']) : 1; // default to 1 for testing

$sql = "SELECT r.*, u.username, i.name AS infra_name
        FROM reservations r
        JOIN user u ON r.user_id = u.id
        JOIN infrastructure i ON r.infrastructure_id = i.id
        WHERE r.infrastructure_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $infrastructure_id);
$stmt->execute();
$result = $stmt->get_result();

$events = [];

while ($row = $result->fetch_assoc()) {
    $color = match ($row['status']) {
        'Pending' => 'yellow',
        'Approved' => 'green',
        'Rejected' => 'red',
        default => 'gray'
    };

    $events[] = [
        'id' => $row['id'],
        'title' => $row['username'],
        'start' => $row['start_date'] . 'T' . $row['time_slot'],
        'end' => $row['start_date'] . 'T' . $row['end_time'],
        'color' => $color,
        'username' => $row['username'],
        'infrastructure_id' => $row['infrastructure_id'],
        'status' => $row['status']
    ];
}

echo json_encode($events);
$stmt->close();
$conn->close();
