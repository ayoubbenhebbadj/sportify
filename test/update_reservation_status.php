<?php
include("../phpConfig/constants.php");

// Include PHPMailer
require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';
require '../PHPMailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $status = isset($_POST['status']) ? $_POST['status'] : '';

    $allowed = ["Pending", "Approved", "Rejected"];
    if (!in_array($status, $allowed)) {
        echo json_encode(["status" => "error", "message" => "Invalid status: " . $status]);
        exit;
    }

    $stmt = $conn->prepare("UPDATE reservations SET status = ? WHERE id = ?");
    if (!$stmt) {
        echo json_encode(["status" => "error", "message" => "Prepare failed: " . $conn->error]);
        exit;
    }

    $stmt->bind_param("si", $status, $id);

    if ($stmt->execute()) {
        // Send email to Ayoub for test
        $mail = new PHPMailer(true);
        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'sportifynotify@gmail.com'; // your Gmail address
            $mail->Password = 'pjofutqlmyngqcfd';   // your Gmail app password
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            // Recipients
            $mail->setFrom('sportifynotify@gmail.com', 'Sportify Admin');
            $mail->addAddress('ayoubbenhebbadj44@gmail.com', 'Ali Mahdi'); // 

            // Content
            $mail->isHTML(true);
            $mail->Subject = 'Reservation Status Updated';
            $mail->Body = "
                Hello Mahdi,<br><br>
                We are here to anonce that status of your reservation (ID: <strong>$id</strong>) has been changed to:
                <span style='color:blue'><strong>$status</strong></span><p>marhba bik 3ndna wla renk hbb m3ndanch lwa9t</p>.<br><br>
                Regards,<br>Li 7wa 
                ";

            $mail->send();
        } catch (Exception $e) {
            error_log("Mailer Error: " . $mail->ErrorInfo);
        }

        echo json_encode(["status" => "success", "message" => "Status updated and email sent."]);
    } else {
        echo json_encode(["status" => "error", "message" => "Execute failed: " . $stmt->error]);
    }

    $stmt->close();
    $conn->close();
}
?>
