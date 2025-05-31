<?php
include("../../phpConfig/constants.php");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../../PHPMailer/src/Exception.php';
require '../../PHPMailer/src/PHPMailer.php';
require '../../PHPMailer/src/SMTP.php';

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['id'])) {
    $reservation_id = intval($_POST['id']);

    // Step 1: Get reservation details
    $sql = "SELECT u.email, u.username, i.name AS infra_name, r.start_date, r.end_date, r.time_slot, r.end_time 
            FROM reservations r
            JOIN user u ON r.user_id = u.id
            JOIN infrastructure i ON r.infrastructure_id = i.id
            WHERE r.id = ?";
    $stmt1 = $conn->prepare($sql);
    $stmt1->bind_param("i", $reservation_id);
    $stmt1->execute();
    $stmt1->bind_result($email, $username, $infraName, $startDate, $endDate, $timeSlot, $endTime);

    if ($stmt1->fetch()) {
        $stmt1->close();

        // Step 2: Send email before deleting
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com'; // Replace with your real SMTP server
            $mail->SMTPAuth = true;
            $mail->Username = 'sportifynotify@gmail.com';     // replace with yours
            $mail->Password = 'xdxvpuijkdialwap'; 
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            $mail->setFrom('sportifynotify@gmail.com', 'Sportify Admin');
            $mail->addAddress($email, $username);

            $mail->isHTML(true);
            $mail->Subject = "Annulation de votre réservation";
            $mail->Body = "
                Bonjour <strong>$username</strong>,<br><br>
                Votre réservation pour l'infrastructure <strong>$infraName</strong><br>
                Du <strong>$startDate</strong> au <strong>$endDate</strong> de <strong>$timeSlot</strong> à <strong>$endTime</strong><br>
                a été <strong>annulée</strong> par l'administration.<br><br>
                Merci de votre compréhension.<br>
                L'équipe Sportify.
            ";

            $mail->send();
            // Step 3: Now delete the reservation
            $stmt2 = $conn->prepare("DELETE FROM reservations WHERE id = ?");
            $stmt2->bind_param("i", $reservation_id);
            if ($stmt2->execute()) {
                echo "Email envoyé. Réservation annulée avec succès.";
            } else {
                echo "Email envoyé mais erreur lors de l'annulation.";
            }
            $stmt2->close();

        } catch (Exception $e) {
            echo "Erreur d'envoi de l'email: {$mail->ErrorInfo}. Réservation non annulée.";
        }

    } else {
        echo "Réservation introuvable.";
    }

} else {
    echo "Requête invalide.";
}
?>
