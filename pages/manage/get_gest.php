<?php
include("../../phpConfig/constants.php");

if (isset($_POST['id'])) {
    $id = intval($_POST['id']);
    $sql = "SELECT id, firstname, lastname, username, email, phone, state FROM gestion WHERE id=$id";
    $res = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($res);
    echo json_encode($row);
}
?>
