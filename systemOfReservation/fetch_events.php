<?php
include("../phpConfig/constants.php");

$events = [];

$sql = "SELECT r.*, u.username, i.name AS infrastructure_name
        FROM reservations r
        JOIN user u ON r.user_id = u.id
        JOIN infrastructure i ON r.infrastructure_id = i.id";

$result = mysqli_query($conn, $sql);

while ($row = mysqli_fetch_assoc($result)) {
    $start = $row['date'] . 'T' . $row['time_slot'];
    $end = $row['date'] . 'T' . $row['end_time'];

    $events[] = [
        'title' => $row['username'] . ' - ' . $row['infrastructure_name'],
        'start' => $start,
        'end' => $end,
        'color' => '#3788d8'
    ];
}

header('Content-Type: application/json');
echo json_encode($events);
