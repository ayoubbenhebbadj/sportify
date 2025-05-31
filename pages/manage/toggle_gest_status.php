<?php
include("../../phpConfig/constants.php");

if (isset($_POST['id'])) {
    $id = intval($_POST['id']);
    $sql = "SELECT state FROM gestion WHERE id = $id";
    $res = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($res);
    $state = $row['state'];

    $newState = ($state == 'active') ? 'suspended' : 'active';

    $update = "UPDATE gestion SET state='$newState' WHERE id=$id";
    mysqli_query($conn, $update);

    echo $newState;
}
?>
