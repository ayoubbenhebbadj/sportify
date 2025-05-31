<?php
include('../phpConfig/constants.php');

if(isset($_POST['submit'])){
    $firstname = $_POST['first-name'];
    $lastname = $_POST['last-name'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password = password_hash($_POST['password'],PASSWORD_DEFAULT);
    $clubtype = $_POST['clubtype'];
    
    // SQL query with placeholders (?)
    $sql = "INSERT INTO user (firstname, lastname, username,email, phone, password, type) 
            VALUES (?, ?, ?, ?, ?, ?, ?)";

    // Prepare the statement
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        // Bind parameters (s = string)
        mysqli_stmt_bind_param($stmt, "sssssss", $firstname, $lastname, $username, $email, $phone, $password, $clubtype);

        // Execute the statement
        if (mysqli_stmt_execute($stmt)) {
            // Set session user_id after registration
            $user_id = mysqli_insert_id($conn);
            $_SESSION['user_id'] = $user_id;
            header("location:".SITEURL.'user_dashboard.php');
        } else {
            $_SESSION['register'] = "Something Wrong";
        }

        // Close the statement
        mysqli_stmt_close($stmt);
    } else {
        echo "Query preparation failed: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Form</title>
    <link rel="stylesheet" href="../style/register.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <script src="https://kit.fontawesome.com/f4f0b2af73.js" crossorigin="anonymous"></script>

</head>
<body>

    <div class="container2">
        <div class="left-container2">
        </div>
        <div class="right-container2">
            <form class="information"  action="" method="post">
                <?php
                if(isset($_SESSION['register'])){
                    echo $_SESSION['register'];
                    unset($_SESSION['register']);
                }
                ?>
                <h2>Create an account</h2>

                <div class="input-group">
                    
                    <input type="text" name="first-name" class="input" placeholder="First Name" required>
                    
                    <input type="text" name="last-name" class="input" placeholder="Last Name" required>
                </div>

                <input type="text" name="username" class="input" placeholder="Username" required>
                <input type="email" name="email" class="input" placeholder="Email" required>
                <input type="tel" name="phone" class="input" placeholder="Phone Number" required>
                <input type="password" name="password" class="input" placeholder="Password" required>

                <select name="clubtype" class="input" required>
                    <option value="" disabled selected>Type of Club</option>
                    <option value="association">association</option>
                    <option value="localclub">local club</option>
                    <option value="professionalclub">professional club</option>
                    <option value="trainingside">training side</option>
                </select>
                <div class="terms">
                    <input type="radio" id="terms" name="accept-terms" required>
                    <label for="terms">I accept <a href="#">conditions & terms</a></label>
                </div>
                <button name="submit" type="submit" class="submit-btn">Register</button>
            </form>
        </div>
    </div>

</body>
</html>
