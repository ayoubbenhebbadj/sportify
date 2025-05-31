<?php include("../phpConfig/constants.php");?>
<?php
            // Fetch gestionnaires for dropdown
            $gestionnaires = [];
            $gest_sql = "SELECT id, firstname, lastname FROM gestion";
            $gest_res = mysqli_query($conn, $gest_sql);
            if ($gest_res && mysqli_num_rows($gest_res) > 0) {
                while ($gest_row = mysqli_fetch_assoc($gest_res)) {
                    $gestionnaires[] = $gest_row;
                }
            }

            if(isset($_POST['submit'])){
                $name = $_POST['name'];
                $type = $_POST['type'];
                $available = $_POST['available'];
                if($available == "Yes"){
                    $available = 1;
                }else{
                    $available = 0;
                }
                $description = $_POST['description'];
                $location = $_POST['location'];
                $state = $_POST['state'];
                $gestionnaire_id = isset($_POST['gestionnaire_id']) ? intval($_POST['gestionnaire_id']) : 0;

                //upload Image by checking if we selected image 
                /*print_r($_FILES['image']);
                die();*/
                if(isset($_FILES['image']['name'])){
                    $image_name = $_FILES['image']['name'];
                    if($image_name !=""){
                        //rename a picture automatically
                        //get the extension
                        $ext_array = explode('.', $image_name);
                        $ext = end($ext_array);
                        // then here we rename = infrastructure(000-999).extension
                        $image_name = "infrastructure_".rand(100,999).".".$ext;       
                        $source_path = $_FILES['image']['tmp_name'];
                        $destination_path = "../img/infrastructure/".$image_name; 
                        //upload image
                        $upload = move_uploaded_file($source_path,$destination_path);
                        //check if the image is uploaded 
                        if($upload == false){
                            $_SESSION['upload'] = "Failed to upload the image";
                            die();
                        }
                  }
                }else{
                    $image_name = "";
                }

                //sql query 
                $sql = "INSERT INTO infrastructure (gestionnaire_id, name, image_name, type, available, description, location, state) VALUES(?,?,?,?,?,?,?,?)";
                $stmt = mysqli_prepare($conn,$sql);
                if($stmt){
                    mysqli_stmt_bind_param($stmt,"isssssss",$gestionnaire_id,$name,$image_name,$type,$available,$description,$location,$state);
                    $res = mysqli_stmt_execute($stmt);
                    if($res){
                        $_SESSION['add'] = "added succesefuly";
                    }else{
                        $_SESSION['add'] = "failed to add";
                    }
                }
            }
        ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Manage Fields</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="inframanage.css"> <!-- External CSS -->
</head>
<body>

<div class="container mt-5">
    <h1 class="text-center">Manage Infrastructures</h1>
    
    <div class="text-end mb-3">
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addFieldModal">Add New Field</button>
        <a href="reservations.html" class="btn btn-secondary">View Reservations</a><br><br><br><br>

        <?php
        if(isset($_SESSION['add'])){
            echo ($_SESSION['add']);
            unset($_SESSION['add']);
        }
        if(isset($_SESSION['upload'])){
            echo ($_SESSION['upload']);
            unset($_SESSION['upload']);
        }
        if(isset($_SESSION['remove'])){
          echo ($_SESSION['remove']);
          unset($_SESSION['remove']);
      }
        if(isset($_SESSION['delete'])){
          echo ($_SESSION['delete']);
          unset($_SESSION['delete']);
      }
      if(isset($_SESSION['update'])){
          echo $_SESSION['update'];
          unset($_SESSION['update']);
      }
      if(isset($_SESSION['upload'])){
        echo ($_SESSION['upload']);
        unset($_SESSION['upload']);
    }

    ?>

    </div>
<?php
// SQL query using prepared statements to fetch infrastructure data
$sql = "SELECT id,name,description,location,image_name FROM infrastructure";

// Prepare the SQL statement
$stmt = mysqli_prepare($conn, $sql);

// Execute the statement
mysqli_stmt_execute($stmt);

// Bind the result to variables (make sure the number of variables matches the number of columns)
mysqli_stmt_bind_result($stmt,$id,$name,$description,$location,$image_name);

// Check if there are results
echo '<div class="row">'; // Open row container

while (mysqli_stmt_fetch($stmt)) {
    ?>
    <!-- Card Layout -->
    <div class="col-md-4 mb-4">
        <div class="card">
            <img src="../img/infrastructure/<?php echo htmlspecialchars($image_name); ?>" class="card-img-top" alt="Infrastructure Image">
            <div class="card-body">
                <h5 class="card-title"><?php echo htmlspecialchars($name); ?></h5>
                <p class="card-text"><?php echo htmlspecialchars($description); ?></p>
                <div class="d-flex justify-content-between">
                    <a href="<?php SITEURL;?>InfrastructureManage/see-more.php?id=<?php echo $id;?>&image_name=<?php echo $image_name;?>" class="btn btn-info">See More</a>
                    <a href="<?php SITEURL;?>InfrastructureManage/delete.php?id=<?php echo $id;?>&image_name=<?php echo $image_name;?>" class="btn btn-secondary">Delete</a>
                    <a href="<?php SITEURL;?>InfrastructureManage/update.php?id=<?php echo $id;?>&image_name=<?php echo $image_name;?>" class="btn btn-secondary">Update</a>
                </div>
            </div>
        </div>
    </div>
    <?php
}
echo '</div>'; // Close row container

// Close the statement
mysqli_stmt_close($stmt);
?>

<!-- Modal to Add New Field -->
<div class="modal fade" id="addFieldModal" tabindex="-1" aria-labelledby="addFieldModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addFieldModalLabel">Add New Infrastructure</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form  action="#" method="post" enctype="multipart/form-data">
          <div class="mb-3">
            <label class="form-label">Gestionnaire</label>
            <select class="form-select" name="gestionnaire_id" required>
                <option value="" disabled selected>Select a gestionnaire</option>
                <?php foreach($gestionnaires as $gest): ?>
                  <option value="<?php echo $gest['id']; ?>">
                    <?php echo htmlspecialchars($gest['firstname'] . ' ' . $gest['lastname']); ?>
                  </option>
                <?php endforeach; ?>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label">Name</label>
            <input type="text" class="form-control" placeholder="Enter field name" name="name">
          </div>
          <div class="mb-3">
            <label class="form-label">Type</label>
            <select class="form-select" name="type">
                <option>Football</option>
                <option>Basketball</option>
                <option>Tennis</option>
                <option>Volleyball</option>
                <option>Other</option>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label">Available</label>
            <select class="form-select" name="available">
                <option>Yes</option>
                <option>No</option>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea class="form-control" rows="3" placeholder="Enter field description" name="description"></textarea>
          </div>
          <div class="mb-3">
            <label class="form-label">Location</label>
            <textarea class="form-control" rows="3" placeholder="Enter field Location" name="location"></textarea>
          </div>
          <div class="mb-3">
            <label class="form-label">State</label>
            <select class="form-select" name="state">
                <option>Good</option>
                <option>Under Maintenance</option>
                <option>Closed</option>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label">Photo</label>
            <input type="file" class="form-control" name="image">
          </div>
          <button type="submit" class="btn btn-success" name="submit">Add Field</button>
        </form>
      </div>
    </div>
  </div>
</div>



<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
