<?php
include("../../phpConfig/constants.php");
header('Content-Type: application/json');

// Total users
$users = mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) FROM user"))[0];
// Total reservations
$reservations = mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) FROM reservation"))[0];
// Total infrastructures
$infras = mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) FROM infrastructure"))[0];

// Reservations per month (last 6 months)
$months = [];
$data = [];
$res = mysqli_query($conn, "
    SELECT DATE_FORMAT(date, '%b %Y') as month, COUNT(*) as count
    FROM reservation
    WHERE date >= DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 5 MONTH), '%Y-%m-01')
    GROUP BY month
    ORDER BY MIN(date)
");
$monthMap = [];
while($row = mysqli_fetch_assoc($res)) {
    $monthMap[$row['month']] = (int)$row['count'];
}

// Build last 6 months labels
$labels = [];
$dataArr = [];
$now = new DateTime();
for ($i = 5; $i >= 0; $i--) {
    $label = $now->modify($i == 5 ? 'now' : '-1 month')->format('M Y');
    $labels[] = $label;
}
$labels = array_reverse($labels);
foreach ($labels as $label) {
    $dataArr[] = isset($monthMap[$label]) ? $monthMap[$label] : 0;
}

echo json_encode([
    "users" => $users,
    "reservations" => $reservations,
    "infrastructures" => $infras,
    "reservations_per_month" => [
        "labels" => $labels,
        "data" => $dataArr
    ]
]);
