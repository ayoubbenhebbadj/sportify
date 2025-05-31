<?php
ob_start(); // Buffering to prevent header issues
include("../phpConfig/constants.php");

// Pagination settings
$limit = 10; // Number of records per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Current page number, default to 1
$offset = ($page - 1) * $limit; // Offset for the SQL query

// Filter logic
$where = "WHERE 1=1";
$search = $_GET['search'] ?? '';
$start_date = $_GET['start_date'] ?? '';
$end_date = $_GET['end_date'] ?? '';

if (!empty($search)) {
    $safe_search = mysqli_real_escape_string($conn, $search);
    $where .= " AND (u.firstname LIKE '%$safe_search%' OR u.lastname LIKE '%$safe_search%' OR i.name LIKE '%$safe_search%')";
}
if (!empty($start_date) && !empty($end_date)) {
    $safe_start = mysqli_real_escape_string($conn, $start_date);
    $safe_end = mysqli_real_escape_string($conn, $end_date);
    $where .= " AND r.start_date BETWEEN '$safe_start' AND '$safe_end'";
}

// Get the reservations for the current page
$query = "SELECT r.*, u.firstname, u.lastname, i.name AS infrastructure_name
          FROM reservations r
          JOIN user u ON r.user_id = u.id
          JOIN infrastructure i ON r.infrastructure_id = i.id
          $where
          ORDER BY r.start_date ASC
          LIMIT $limit OFFSET $offset";

$result = mysqli_query($conn, $query);

// Get the total number of reservations for pagination
$total_query = "SELECT COUNT(*) AS total
                FROM reservations r
                JOIN user u ON r.user_id = u.id
                JOIN infrastructure i ON r.infrastructure_id = i.id
                $where";

$total_result = mysqli_query($conn, $total_query);
$total_row = mysqli_fetch_assoc($total_result);
$total_records = $total_row['total'];
$total_pages = ceil($total_records / $limit); // Calculate the total number of pages

// Delete reservations older than 1 month
$current_date = date('Y-m-d');
$last_month_date = date('Y-m-d', strtotime('-1 month'));

$delete_old_reservations_query = "DELETE FROM reservations WHERE start_date < '$last_month_date'";
mysqli_query($conn, $delete_old_reservations_query);

// Delete specific reservation if the delete button is clicked
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $delete_query = "DELETE FROM reservations WHERE id = ?";
    $stmt = mysqli_prepare($conn, $delete_query);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    header("Location: admin_reservation.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Reservation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        h1 {
            text-align: center;
            background-color: #007BFF;
            color: white;
            padding: 20px;
        }

        form {
            text-align: center;
            margin: 20px 0;
        }

        form input, form button {
            padding: 8px;
            margin: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        form button {
            background-color: #28a745;
            color: white;
            cursor: pointer;
        }

        form a {
            margin-left: 10px;
            color: #007BFF;
            text-decoration: none;
        }

        table {
            width: 90%;
            margin: 20px auto;
            border-collapse: collapse;
            background-color: white;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 12px;
            text-align: left;
        }

        th {
            background-color: #007BFF;
            color: white;
        }

        td a {
            color: #007BFF;
            text-decoration: none;
        }

        td a:hover {
            text-decoration: underline;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        .no-reservations {
            text-align: center;
            font-style: italic;
            color: #888;
        }

        /* Pagination Styling */
        .pagination {
            text-align: center;
            margin-top: 20px;
        }

        .pagination a {
            padding: 8px 16px;
            margin: 0 5px;
            background-color: #007BFF;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }

        .pagination a:hover {
            background-color: #0056b3;
        }

        .pagination .current {
            background-color: #28a745;
        }

    </style>
</head>
<body>
    <h1>Reservations Overview</h1>

    <!-- Filter form -->
    <form method="get">
        <input type="text" name="search" placeholder="Search by user or infrastructure" value="<?= htmlspecialchars($search) ?>">
        <input type="date" name="start_date" value="<?= htmlspecialchars($start_date) ?>">
        <input type="date" name="end_date" value="<?= htmlspecialchars($end_date) ?>">
        <button type="submit">Search</button>
        <a href="admin_reservation.php">Reset</a>
    </form>

    <table>
        <thead>
            <tr>
                <th>User</th>
                <th>Infrastructure</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Time Slot</th>
                <th>End Time</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php if (mysqli_num_rows($result) > 0): ?>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?= htmlspecialchars($row['firstname'] . ' ' . $row['lastname']) ?></td>
                    <td><?= htmlspecialchars($row['infrastructure_name']) ?></td>
                    <td><?= htmlspecialchars($row['start_date']) ?></td>
                    <td><?= htmlspecialchars($row['end_date']) ?></td>
                    <td><?= htmlspecialchars($row['time_slot']) ?></td>
                    <td><?= htmlspecialchars($row['end_time']) ?></td>
                    <td><?= htmlspecialchars($row['status']) ?></td>
                    <td>
                        <a href="?approve=<?= $row['id'] ?>">Approve</a> |
                        <a href="?reject=<?= $row['id'] ?>">Reject</a> |
                        <a href="?delete=<?= $row['id'] ?>" onclick="return confirm('Are you sure you want to delete this reservation?')">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="8" class="no-reservations">No reservations found.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>

    <!-- Pagination -->
    <div class="pagination">
        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
            <a href="?page=<?= $i ?>&search=<?= urlencode($search) ?>&start_date=<?= urlencode($start_date) ?>&end_date=<?= urlencode($end_date) ?>"
               class="<?= ($i == $page) ? 'current' : '' ?>">
                <?= $i ?>
            </a>
        <?php endfor; ?>
    </div>

</body>
</html>

<?php
// Approve / Reject logic
if (isset($_GET['approve'])) {
    $id = intval($_GET['approve']);
    $stmt = mysqli_prepare($conn, "UPDATE reservations SET status = 'Approved' WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    header("Location: admin_reservation.php");
    exit();
}

if (isset($_GET['reject'])) {
    $id = intval($_GET['reject']);
    $stmt = mysqli_prepare($conn, "UPDATE reservations SET status = 'Rejected' WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    header("Location: admin_reservation.php");
    exit();
}

ob_end_flush(); // Finish buffering
?>
