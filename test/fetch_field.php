<?php
include("../phpConfig/constants.php");

$sql = "SELECT id, name FROM infrastructure ORDER BY name ASC";
$result = $conn->query($sql);

$fields = [];

while ($row = $result->fetch_assoc()) {
    $fields[] = [
        'id' => $row['id'],
        'name' => $row['name']
    ];
}

header('Content-Type: application/json');
echo json_encode($fields);
?>