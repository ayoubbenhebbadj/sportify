<?php
include("../../phpConfig/constants.php");;

if (isset($_POST['submit'])) {
    $prenom = $_POST['firstname'];
    $nom = $_POST['lastname'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $clubtype = $_POST['clubtype'];
    
    $sql = "INSERT INTO user (firstname, lastname, username, email, phone, password, type, state)
            VALUES ('$prenom', '$nom', '$username', '$email', '$phone', '$password', '$clubtype', 'active')";
    
    if (mysqli_query($conn, $sql)) {
        header("Location: gestion_user.php");
    } else {
        echo "Erreur: " . mysqli_error($conn);
    }
}
?>
