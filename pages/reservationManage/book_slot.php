<?php
include("../../phpConfig/constants.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // ✅ Ensure user is authenticated
    if (!isset($_SESSION['user_id'])) {
        echo json_encode([
            "status" => "error",
            "message" => "User not authenticated. Please log in."
        ]);
        exit();
    }

    // ✅ Extract data
    $infrastructure_id = intval($_POST['infrastructure_id']);
    $user_id = $_SESSION['user_id'];
    $start_date = $_POST['start_date'] ?? '';
    $time_slot = $_POST['time_slot'] ?? '';
    $duration_minutes = intval($_POST['duration']);

    $start_time = DateTime::createFromFormat('H:i', $time_slot);
    if ($start_time === false) {
        echo json_encode([
            "status" => "error",
            "message" => "Invalid time format."
        ]);
        exit();
    }

    $end_time = clone $start_time;
    $end_time->modify("+$duration_minutes minutes");
    $end_time_str = $end_time->format('H:i');

    // ❗ Check for overlaps
    $check_sql = "SELECT * FROM reservations
                  WHERE infrastructure_id = ? AND start_date = ?
                  AND NOT (end_time <= ? OR time_slot >= ?)";

    $stmt = $conn->prepare($check_sql);
    $stmt->bind_param("isss", $infrastructure_id, $start_date, $time_slot, $end_time_str);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo json_encode([
            "status" => "error",
            "message" => "This time slot is already booked."
        ]);
    } else {
        $insert_sql = "INSERT INTO reservations (infrastructure_id, user_id, start_date, time_slot, end_time, duration, status, priority)
                       VALUES (?, ?, ?, ?, ?, ?, 'Pending', 2)";
        $insert_stmt = $conn->prepare($insert_sql);
        $insert_stmt->bind_param("iisssi", $infrastructure_id, $user_id, $start_date, $time_slot, $end_time_str, $duration_minutes);

        if ($insert_stmt->execute()) {
            echo json_encode([
                "status" => "success",
                "message" => "Reservation created successfully."
            ]);
        } else {
            echo json_encode([
                "status" => "error",
                "message" => "Error inserting reservation: " . $conn->error
            ]);
        }
        $insert_stmt->close();
    }

    $stmt->close();
    $conn->close();
}
?>