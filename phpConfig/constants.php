<?php
    //start a session 
    session_start();

//create constants to store repeating values
define('SITEURL','http://localhost/infrastructuresportives/pages/');
define('LOCALHOST','localhost');
define('DB_USERNAME','root');
define('DB_password','');
define('DB_NAME','sportify');
$conn = mysqli_connect(LOCALHOST,DB_USERNAME,DB_password) or die(mysqli_error());
$db_select = mysqli_select_db($conn,DB_NAME) or die(mysqli_error());

?>