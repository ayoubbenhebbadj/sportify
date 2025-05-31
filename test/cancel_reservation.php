<?php
include("../phpConfig/constants.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $reservation_id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $user_id = 7; // Replace with $_SESSION['user_id'] later

    // Only cancel if this user owns the reservation
    $stmt = $conn->prepare("UPDATE reservations SET status = 'Cancelled' WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $reservation_id, $user_id);

    if ($stmt->execute() && $stmt->affected_rows > 0) {
        echo json_encode([
            "status" => "success",
            "message" => "Reservation cancelled successfully."
        ]);
    } else {
        echo json_encode([
            "status" => "error",
            "message" => "Cancellation failed. Either reservation not found or not owned by this user."
        ]);
    }

    $stmt->close();
    $conn->close();
}
?>
