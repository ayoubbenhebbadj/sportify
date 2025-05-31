<?php
include("../../phpConfig/constants.php");

if (isset($_POST['submit'])) {
    $prenom = $_POST['firstname'];
    $nom = $_POST['lastname'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $sql = "INSERT INTO gestion (firstname, lastname, username, email, phone, password, state)
            VALUES ('$prenom', '$nom', '$username', '$email', '$phone', '$password', 'active')";

    if (mysqli_query($conn, $sql)) {
        echo "success";
    } else {
        echo "Erreur: " . mysqli_error($conn);
    }
}
?>
