<?php
include("../../phpConfig/constants.php");
header('Content-Type: application/json');
$res = mysqli_query($conn, "SELECT id, firstname, lastname, username FROM user");
$users = [];
while ($row = mysqli_fetch_assoc($res)) {
    $users[] = $row;
}
echo json_encode($users);
?>
