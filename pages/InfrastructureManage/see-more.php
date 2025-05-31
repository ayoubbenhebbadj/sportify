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
    // Fetch gestionnaires for display
    $gestionnaires = [];
    $gest_sql = "SELECT id, firstname, lastname FROM gestion";
    $gest_res = mysqli_query($conn, $gest_sql);
    if ($gest_res && mysqli_num_rows($gest_res) > 0) {
        while ($gest_row = mysqli_fetch_assoc($gest_res)) {
            $gestionnaires[$gest_row['id']] = $gest_row['firstname'] . ' ' . $gest_row['lastname'];
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
            mysqli_stmt_bind_result($stmt, $name, $type, $location, $state, $image_name, $gestionnaire_id, $available);
            mysqli_stmt_store_result($stmt); // Required for fetching

            if (mysqli_stmt_fetch($stmt)) {
                ?>
                <div class="row">
                    <!-- Field Image -->
                    <div class="col-md-6">
                        <img src="../../img/infrastructure/<?php echo htmlspecialchars($image_name);?>" class="img-fluid rounded shadow" alt="Field Image">
                    </div>

                    <!-- Field Details Table -->
                    <div class="col-md-6">
                        <table class="table table-bordered">
                            <tr>
                                <th>Name</th>
                                <td><?php echo htmlspecialchars($name); ?></td>
                            </tr>
                            <tr>
                                <th>Type</th>
                                <td><?php echo htmlspecialchars($type); ?></td>
                            </tr>
                            <tr>
                                <th>Location</th>
                                <td><?php echo htmlspecialchars($location); ?></td>
                            </tr>
                            <tr>
                                <th>Gestionnaire</th>
                                <td>
                                    <?php
                                    if (isset($gestionnaires[$gestionnaire_id])) {
                                        echo htmlspecialchars($gestionnaires[$gestionnaire_id]);
                                    } else {
                                        echo "<span class='text-muted'>Non assigné</span>";
                                    }
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <th>Available</th>
                                <td>
                                    <?php
                                        if ($available == 1) {
                                            echo "Yes";
                                        } else {
                                            echo "No";
                                        }
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <th>Condition</th>
                                <td><?php echo htmlspecialchars($state); ?></td>
                            </tr>
                        </table>
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
