<?php
session_start();
include("../phpConfig/constants.php");

// Check if user is admin/manager
if (!isset($_SESSION['user_type']) || !in_array($_SESSION['user_type'], ['admin', 'manager'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

$reservation_id = (int)$_POST['id'];

// Delete reservation
$stmt = $conn->prepare("DELETE FROM reservations WHERE id = ?");
$stmt->bind_param("i", $reservation_id);
$success = $stmt->execute();
$stmt->close();

if ($success) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Error deleting reservation']);
}
?>