<?php 
include("../../phpConfig/constants.php");
if(isset($_GET['id']) AND isset($_GET['image_name'])){
    $id =  $_GET['id'];
    $image_name = $_GET['image_name'];
    //remove image 
    if($image_name !=""){
        $path = "../../img/infrastructure/".$image_name;
        $rm = unlink($path);
        if($rm == false){
            $_SESSION['remove'] = "failed to remove image";
            die();
        }
    }

    $sql = "DELETE FROM infrastructure WHERE id = ?";

    $stmt = mysqli_prepare($conn, $sql);
    
    if ($stmt) {
        // Bind the parameter correctly
        mysqli_stmt_bind_param($stmt, "i", $id);    
        // Execute the statement
        if (mysqli_stmt_execute($stmt)) {
            $_SESSION['delete'] = "You have deleted an infrastructure";
            header('location:'.SITEURL."inframanage.php");
        } else {
            $_SESSION['delete'] = "Failed to delete";
            header('location:'.SITEURL."inframanage.php");
        }
    
        // Close statement
        mysqli_stmt_close($stmt);
    } else {
        $_SESSION['delete'] = "Failed to prepare statement";
    }
    
    // Redirect
    header('location:'.SITEURL."inframanage.php");
    exit();
    
}


else{
    header('location:'.SITEURL."InfrastructureManage/see-more.php");
}





?>