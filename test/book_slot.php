<?php
include("../phpConfig/constants.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $infrastructure_id = $_POST['infrastructure_id'];
    $user_id = 11; // Replace with session value later
    $start_date = $_POST['start_date'];
    $time_slot = $_POST['time_slot'];
    $duration_minutes = (int)$_POST['duration'];

    $start_time = DateTime::createFromFormat('H:i', $time_slot);
    $end_time = clone $start_time;
    $end_time->modify("+$duration_minutes minutes");
    $end_time_str = $end_time->format('H:i');

    // ❗Block if any existing reservation (any status) overlaps
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
            "message" => "This slot is already booked."
        ]);
    } else {
        $insert_sql = "INSERT INTO reservations (infrastructure_id, user_id, start_date, time_slot, end_time, duration, status, priority)
                       VALUES (?, ?, ?, ?, ?, ?, 'Pending', 2)";
        $insert_stmt = $conn->prepare($insert_sql);
        $insert_stmt->bind_param("iisssi", $infrastructure_id, $user_id, $start_date, $time_slot, $end_time_str, $duration_minutes);

        if ($insert_stmt->execute()) {
            echo json_encode([
                "status" => "success",
                "message" => "Reservation successfully created."
            ]);
        } else {
            echo json_encode([
                "status" => "error",
                "message" => "Database error: " . $conn->error
            ]);
        }
    }

    $stmt->close();
    $conn->close();
}
?>
