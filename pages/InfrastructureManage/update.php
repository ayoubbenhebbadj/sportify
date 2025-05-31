<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Field Details</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../style/see-more.css">
</head>
<body>

<div class="container mt-5">
    <h2 class="text-center mb-4">Field Details</h2>

    <?php 
    include("../../phpConfig/constants.php");
    // Fetch gestionnaires for dropdown
    $gestionnaires = [];
    $gest_sql = "SELECT id, firstname, lastname FROM gestion";
    $gest_res = mysqli_query($conn, $gest_sql);
    if ($gest_res && mysqli_num_rows($gest_res) > 0) {
        while ($gest_row = mysqli_fetch_assoc($gest_res)) {
            $gestionnaires[] = $gest_row;
        }
    }

    if(isset($_GET['id'])) {
        $id = intval($_GET['id']); // Secure ID
        $image_name = $_GET['image_name'];
        $sql = "SELECT name, type, location, state, image_name, gestionnaire_id, available FROM infrastructure WHERE id = ?";
        $stmt = mysqli_prepare($conn, $sql);

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "i", $id);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_bind_result($stmt, $name, $type, $location, $state, $current_image, $gestionnaire_id, $available);
            mysqli_stmt_store_result($stmt); // Required for fetching

            if (mysqli_stmt_fetch($stmt)) {
                ?>
                <form action="" method="post" enctype="multipart/form-data">
                <div class="row">
                    <!-- Field Image -->
                    <div class="col-md-6">
                        <img src="../../img/infrastructure/<?php echo htmlspecialchars($image_name);?>" class="img-fluid rounded shadow" alt="Field Image"><br><br>
                        <label for="image">Change the picture : </label>
                        <input type="file" name="image">
                    </div>
                    <!-- Field Details Table -->
                    <div class="col-md-6">
                        <table class="table table-bordered">
                            <tr>
                                <th>Name</th>
                                <td><input type="text" value="<?php echo htmlspecialchars($name); ?>" name="name"> </td>
                            </tr>
                            <tr>
                                <th>Gestionnaire</th>
                                <td>
                                    <select name="gestionnaire_id" class="form-select" required>
                                        <option value="" disabled>Select a gestionnaire</option>
                                        <?php foreach($gestionnaires as $gest): ?>
                                            <option value="<?php echo $gest['id']; ?>" <?php if($gestionnaire_id == $gest['id']) echo 'selected'; ?>>
                                                <?php echo htmlspecialchars($gest['firstname'] . ' ' . $gest['lastname']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th>Available</th>
                                <td>
                                    <select name="available" class="form-select">
                                        <option value="1" <?php if($available == 1) echo 'selected'; ?>>Yes</option>
                                        <option value="0" <?php if($available == 0) echo 'selected'; ?>>No</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th>Condition</th>
                                <td>
                                    <select name="state">
                                        <option <?php if($state == 'Good') echo 'selected'; ?>>Good</option>
                                        <option <?php if($state == 'Under Maintenance') echo 'selected'; ?>>Under Maintenance</option>
                                        <option <?php if($state == 'Closed') echo 'selected'; ?>>Closed</option>
                                    </select>
                                </td>
                            </tr>
                        </table>
                        <input type="hidden" name="current_image" value="<?php echo $current_image ;?>">
                        <input type="hidden" name="id" value="<?php echo $id ;?>">
                        <input type="submit" value="update" name="submit" class="btn btn-success">
                        </form>
                        <?php 
                            if(isset($_POST['submit'])){
                                $name = $_POST['name'];
                                $state = $_POST['state'];
                                $id = intval($_POST['id']);
                                $gestionnaire_id = isset($_POST['gestionnaire_id']) ? intval($_POST['gestionnaire_id']) : 0;
                                $available = isset($_POST['available']) ? intval($_POST['available']) : 1;
                                if(isset($_FILES['image']['name']) && $_FILES['image']['name'] != ""){
                                    $image_name = $_FILES['image']['name'];
                                    $ext = pathinfo($image_name, PATHINFO_EXTENSION);
                                    $image_name = "infrastructure_".rand(100,999).".".$ext; // Rename the image
                                    
                                    $source_path = $_FILES['image']['tmp_name'];
                                    $destination_path = "../../img/infrastructure/".$image_name;
                            
                                    if (move_uploaded_file($source_path, $destination_path)) {
                                        if ($current_image != "" && file_exists("../../img/infrastructure/".$current_image)) {
                                            unlink("../../img/infrastructure/".$current_image);
                                        }
                                    } else {
                                        $_SESSION['upload'] = "<p class='alert alert-danger'>Failed to upload the image.</p>";
                                        header('location:'.SITEURL.'inframanage.php');
                                        exit();
                                    }
                                } else {
                                    $image_name = $current_image; // Keep old image if no new one is uploaded
                                }
                            
                                $sql2 = "UPDATE infrastructure SET name = ?, state = ?, image_name = ?, gestionnaire_id = ?, available = ? WHERE id = ?";
                                $stmt2 = mysqli_prepare($conn, $sql2);
                            
                                if ($stmt2) {
                                    mysqli_stmt_bind_param($stmt2, "sssiii", $name, $state, $image_name, $gestionnaire_id, $available, $id);
                                    $res = mysqli_stmt_execute($stmt2);
                                    mysqli_stmt_close($stmt2);
                            
                                    if($res){
                                        $_SESSION['update'] = "<p class='alert alert-success'>Update successful!</p>";
                                        header('location:'.SITEURL.'inframanage.php');
                                        exit();
                                    } else {
                                        $_SESSION['update'] = "<p class='alert alert-danger'>Failed to update.</p>";
                                        header('location:'.SITEURL.'inframanage.php');
                                        exit();
                                    }
                                } else {
                                    $_SESSION['update'] = "<p class='alert alert-danger'>Database error.</p>";
                            }
                        }
                    ?>
                </div>
            </div>
            <?php
        } else {
            echo "<p class='alert alert-danger'>No field found with this ID.</p>";
        }
        mysqli_stmt_close($stmt);
    } else {
        echo "<p class='alert alert-danger'>Failed to prepare the statement.</p>";
    }
} else {
    echo "<p class='alert alert-danger'>Invalid ID.</p>";
}
?>
</div>
</body>
</html>