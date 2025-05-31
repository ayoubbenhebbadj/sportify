<?php
include("../../phpConfig/constants.php");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../../PHPMailer/src/Exception.php';
require '../../PHPMailer/src/PHPMailer.php';
require '../../PHPMailer/src/SMTP.php';

if (isset($_POST['id'])) {
    $id = intval($_POST['id']);
    $sql = "SELECT email, username, state FROM user WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($email, $username, $state);
    
    if ($stmt->fetch()) {
        $stmt->close();

        $newState = ($state == 'active') ? 'suspended' : 'active';

        // Prepare email
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

            $mail->Subject = "Mise à jour de l'état de votre compte";
            $mail->Body = "
                Bonjour <strong>$username</strong>,<br><br>
                Votre compte a été <strong>" . ($newState === 'suspended' ? 'suspendu' : 'réactivé') . "</strong> par l'administration de Sportify.<br><br>
                Merci de votre compréhension.<br>
                L'équipe Sportify.
            ";

            $mail->send();

            // Update state in database only after email is sent
            $update = "UPDATE user SET state=? WHERE id=?";
            $stmt2 = $conn->prepare($update);
            $stmt2->bind_param("si", $newState, $id);
            $stmt2->execute();
            $stmt2->close();

            echo $newState;
        } catch (Exception $e) {
            echo "Erreur: l'email n'a pas pu être envoyé. {$mail->ErrorInfo}";
        }

    } else {
        echo "Utilisateur introuvable.";
    }

    $conn->close();
}
?>
