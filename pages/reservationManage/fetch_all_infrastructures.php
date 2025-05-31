<?php
include("../../phpConfig/constants.php");
header('Content-Type: application/json');
$res = mysqli_query($conn, "SELECT id, name FROM infrastructure");
$infras = [];
while ($row = mysqli_fetch_assoc($res)) {
    $infras[] = $row;
}
echo json_encode($infras);
?>
