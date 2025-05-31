<?php
include("../../phpConfig/constants.php");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../../PHPMailer/src/Exception.php';
require '../../PHPMailer/src/PHPMailer.php';
require '../../PHPMailer/src/SMTP.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $reservationId = $_POST['id'];
    $newStatus = $_POST['status']; // accepted, rejected, cancelled

    // Update reservation status in DB
    $stmt = $conn->prepare("UPDATE reservations SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $newStatus, $reservationId);

    if ($stmt->execute()) {
        // Fetch user email and details
        $sql = "SELECT u.email, u.username, i.name AS infra_name, r.start_date, r.end_date, r.time_slot, r.end_time   
                FROM reservations r
                JOIN user u ON r.user_id = u.id
                JOIN infrastructure i ON r.infrastructure_id = i.id
                WHERE r.id = ?";
        $stmt2 = $conn->prepare($sql);
        $stmt2->bind_param("i", $reservationId);
        $stmt2->execute();
        $stmt2->bind_result($email, $username, $infraName, $startDate, $endDate, $time_slot, $end_time);
        $stmt2->fetch();

        // Setup email
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'sportifynotify@gmail.com';     // replace with yours
            $mail->Password = 'xdxvpuijkdialwap';        // replace with yours
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            $mail->setFrom('sportifynotify@gmail.com', 'Sportify Admin');
            $mail->addAddress($email, $username);
            $mail->isHTML(true);

            // Custom message depending on status
            if ($newStatus === "cancelled") {
                $subject = "Annulation de votre réservation";
                $body = "
                    Bonjour <strong>$username</strong>,<br><br>
                    Votre réservation pour <strong>$infraName</strong> du <strong>$startDate</strong> au <strong>$endDate</strong>
                    de <strong>$time_slot</strong> à <strong>$end_time</strong> a été <span style='color:red;'><strong>annulée</strong></span> par l'administration.<br><br>
                    Merci de vérifier ou reprogrammer une nouvelle réservation si besoin.<br>
                    <em>L'équipe Sportify</em>.
                ";
            } else {
                $subject = "Mise à jour de votre réservation";
                $body = "
                    Bonjour <strong>$username</strong>,<br><br>
                    Votre réservation pour <strong>$infraName</strong> du <strong>$startDate</strong> au <strong>$endDate</strong>
                    de <strong>$time_slot</strong> à <strong>$end_time</strong> a été <strong>$newStatus</strong>.<br><br>
                    Merci de votre compréhension.<br>
                    L'équipe Sportify.
                ";
            }

            $mail->Subject = $subject;
            $mail->Body = $body;

            $mail->send();
            echo "Statut mis à jour et email envoyé à $email.";
        } catch (Exception $e) {
            echo "Statut mis à jour, mais l'email n'a pas pu être envoyé. Erreur: {$mail->ErrorInfo}";
        }

        $stmt2->close();
    } else {
        echo "Échec de la mise à jour du statut.";
    }

    $stmt->close();
    $conn->close();
}
?>
