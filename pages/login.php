<?php 
include("../phpConfig/constants.php");

if (isset($_POST['submit'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Try user table
    $sql = "SELECT id, password, state FROM user WHERE email = ?";

// Try user table
$sql = "SELECT id, password, state FROM user WHERE email = ?";
$stmt = mysqli_prepare($conn, $sql);
if ($stmt) {
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {
        if (password_verify($password, $row['password'])) {
            if ($row['state'] === 'active') {
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['role'] = 'user';
                header('location:' . SITEURL . 'user_dashboard.php');
            } else {
                header('location:' . SITEURL . 'suspended.php');
            }
            exit();
        }
    }
    mysqli_stmt_close($stmt);
}


    // Try gestion table
    $sql = "SELECT id, password, state FROM gestion WHERE email = ?";

// Try gestion table
$sql = "SELECT id, password, state FROM gestion WHERE email = ?";
$stmt = mysqli_prepare($conn, $sql);
if ($stmt) {
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {
        if (password_verify($password, $row['password'])) {
            if ($row['state'] === 'active') {
                $_SESSION['gestion_id'] = $row['id'];
                $_SESSION['role'] = 'gestion';
                header('location:' . SITEURL . 'manage/gestion_dashboard.php');
            } else {
                header('location:' . SITEURL . 'suspended.php');
            }
            exit();
        }
    }
    mysqli_stmt_close($stmt);
}


    // Try admin table
    $sql = "SELECT id, password FROM admin WHERE email = ?";
    $stmt = mysqli_prepare($conn, $sql);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($row = mysqli_fetch_assoc($result)) {
            if (password_verify($password, $row['password'])) {
                $_SESSION['admin_id'] = $row['id'];
                $_SESSION['role'] = 'admin';
                header('location:' . SITEURL . 'manage/admin.php');
                exit();
            }
        }
        mysqli_stmt_close($stmt);
    }

    $_SESSION['login'] = "email or password is wrong";
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Form</title>
    <link rel="stylesheet" href="../style/login.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <script src="https://kit.fontawesome.com/f4f0b2af73.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&icon_names=mail" />
</head>
<body>

    <div class="container2">
        <div class="left-container2">
            <form action="#" method="post">
            
            <?php
            if(isset($_SESSION['login'])){
                    echo $_SESSION['login'];
                    unset($_SESSION['login']);
                }
            ?>

            <div class="texts">
            <h1>Welcome back ! </h1>
            <p>Fill your details to sign in </p>
            </div>

            <div class="login">
            <div class="login-input">
                <label for="email" >Email</label><br>
                <i class="fa-solid fa-envelope"></i>
                <input class="email-password" type="email" name="email" placeholder="Your email..." require><br>
                <label for="password">Password</label><br>
                <i class="fa-solid fa-lock"></i>
                <input class="email-password" type="password" name="password" placeholder="Your password..." require><br>
                <a href="#">Forget password ?</a><br>
                <input type="radio" name="radio" class="radio">
                <p>Remember me</p>
            </div>
                <div class="link">
                    
                    <i class="fa-brands fa-facebook"></i>
                    <a href="#" id="facebook"> Facebook</a>
                    <i class="fa-brands fa-google"></i>
                    <a href="#" id="google"> Google</a>
                </div>
                <input type="submit" name="submit" value="submit" id="submit"><br><br>
                <div class="register">
                <p>You don't have an account ?<a href="register.php"> Register.</a></p>
                </div>
            </form>
            </div>
        </div>

        <div class="right-container2">
            <img src="../img/login.jpg" alt="">
        </div>
    </div>

</body>
</html>