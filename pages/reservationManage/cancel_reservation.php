<?php
include("../../phpConfig/constants.php");

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
    exit;
}

$reservation_id = isset($_POST['id']) ? intval($_POST['id']) : 0;
$user_id = isset($_POST['user_id']) ? intval($_POST['user_id']) : 0;

if ($reservation_id <= 0 || $user_id <= 0) {
    echo json_encode(['status' => 'error', 'message' => 'Missing or invalid reservation ID or user ID.']);
    exit;
}


if (!$conn) {
    echo json_encode(['status' => 'error', 'message' => 'Database connection failed.']);
    exit;
}

// Optional: Check if reservation belongs to the user
$check_query = "SELECT * FROM reservations WHERE id = ? AND user_id = ?";
$stmt = $conn->prepare($check_query);
$stmt->bind_param("ii", $reservation_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(['status' => 'error', 'message' => 'Reservation not found or unauthorized.']);
    $stmt->close();
    $conn->close();
    exit;
}
$stmt->close();

// Delete the reservation
$delete_query = "DELETE FROM reservations WHERE id = ?";
$stmt = $conn->prepare($delete_query);
$stmt->bind_param("i", $reservation_id);

if ($stmt->execute()) {
    echo json_encode(['status' => 'success', 'message' => 'Reservation cancelled successfully.']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to cancel reservation.']);
}

$stmt->close();
$conn->close();
