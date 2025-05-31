<?php
// Optional: Start session and destroy if needed
session_start();
session_destroy();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Account Suspended</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Optional Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        body {
            background-color: #f8f9fa;
        }
        .suspended-container {
            max-width: 600px;
            margin: 100px auto;
            text-align: center;
            padding: 40px;
            background-color: #fff;
            border-radius: 15px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
        }
        .suspended-container h1 {
            color: #dc3545;
        }
        .suspended-container i {
            font-size: 60px;
            color: #dc3545;
            margin-bottom: 20px;
        }
        .suspended-container p {
            font-size: 1.2rem;
            margin-bottom: 30px;
        }
    </style>
</head>
<body>

<div class="suspended-container">
    <i class="fas fa-user-slash"></i>
    <h1>Account Suspended</h1>
    <p>Your account has been suspended. Please contact support or the administrator for more information.</p>
    <a href="login.php" class="btn btn-danger"><i class="fas fa-arrow-left"></i> Back to Login</a>
</div>

</body>
</html>
