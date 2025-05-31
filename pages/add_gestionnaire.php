<?php
include('../phpConfig/constants.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstname = mysqli_real_escape_string($conn, $_POST['firstname']);
    $lastname = mysqli_real_escape_string($conn, $_POST['lastname']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // secure hash

    // Insert query
    $sql = "INSERT INTO gestion (firstname, lastname, username, email, password) 
            VALUES ('$firstname', '$lastname', '$username', '$email', '$password')";

    $res = mysqli_query($conn, $sql);

    if ($res) {
        echo "<script>alert('Gestionnaire added successfully'); window.location.href='add_gestionnaire.php';</script>";
    } else {
        echo "<script>alert('Failed to add gestionnaire'); window.history.back();</script>";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Admin - Add Gestionnaire</title>
  <link rel="stylesheet" href="../style/add_gestionnaire.css" />
</head>
<body>

  <header>
    <h1>Admin Dashboard</h1>
  </header>

  <form action="add_gestionnaire.php" method="POST">
  <div class="form-group">
    <label for="firstname">First Name</label>
    <input type="text" id="firstname" name="firstname" placeholder="Enter first name" required />
  </div>

  <div class="form-group">
    <label for="lastname">Last Name</label>
    <input type="text" id="lastname" name="lastname" placeholder="Enter last name" required />
  </div>

  <div class="form-group">
    <label for="username">Username</label>
    <input type="text" id="username" name="username" placeholder="Enter username" required />
  </div>

  <div class="form-group">
    <label for="email">Email Address</label>
    <input type="email" id="email" name="email" placeholder="Enter email" required />
  </div>

  <div class="form-group">
    <label for="password">Password</label>
    <input type="password" id="password" name="password" placeholder="Enter password" required />
  </div>

  <button type="submit">Add Gestionnaire</button>
</form>



</body>
</html>
