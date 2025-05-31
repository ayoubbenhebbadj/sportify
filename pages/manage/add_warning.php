<?php
include("../../phpConfig/constants.php");
require '../../PHPMailer/src/Exception.php';
require '../../PHPMailer/src/PHPMailer.php';
require '../../PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function sendWarningEmail($email, $username, $reason, $isSuspended) {
  $mail = new PHPMailer(true);
  try {
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'sportifynotify@gmail.com';
    $mail->Password = 'xdxvpuijkdialwap';
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    $mail->setFrom('sportifynotify@gmail.com', 'Sportify Admin');
    $mail->addAddress($email, $username);

    $mail->isHTML(true);
    $mail->Subject = 'Avertissement de comportement';
    $body = "Bonjour <strong>$username</strong>,<br><br>
            Vous avez reçu un avertissement pour la raison suivante :<br>
            <em>$reason</em><br><br>";
    if ($isSuspended) {
      $body .= "Votre compte a été <strong>suspendu</strong> après 3 avertissements.<br>";
    } else {
      $body .= "Ceci est un avertissement officiel. Merci de respecter les règles.<br>";
    }
    $body .= "<br>Admin Sportify.";
    $mail->Body = $body;
    $mail->send();
  } catch (Exception $e) {
    echo "Erreur d'envoi d'email à $email: {$mail->ErrorInfo}";
  }
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['type'], $_POST['recipients'], $_POST['reason'])) {
  $type = $_POST['type'];
  $recipients = $_POST['recipients'];
  $reason = mysqli_real_escape_string($conn, $_POST['reason']);

  foreach ($recipients as $item) {
    list($targetType, $id) = explode("-", $item);
    $id = intval($id);
    $table = ($targetType === "user") ? "user" : "gestion";

    $sql = "SELECT email, username, state FROM $table WHERE id = $id";
    $res = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($res);
    $email = $row['email'];
    $username = $row['username'];
    $currentState = $row['state'];

    // Insert new warning
    $insert = $conn->prepare("INSERT INTO warnings (target_type, target_id, reason, issued_by, is_archived) VALUES (?, ?, ?, 'admin', 0)");
    $insert->bind_param("sis", $targetType, $id, $reason);
    $insert->execute();

    // Count only active (non-archived) warnings
    $check = $conn->prepare("SELECT COUNT(*) FROM warnings WHERE target_type = ? AND target_id = ? AND is_archived = 0");
    $check->bind_param("si", $targetType, $id);
    $check->execute();
    $check->bind_result($count);
    $check->fetch();
    $check->close();

    $isSuspended = false;
    if ($count >= 3 && $currentState !== 'suspended') {
      $conn->query("UPDATE $table SET state = 'suspended' AND is_archived = 1 WHERE id = $id");
      $isSuspended = true;
    }

    sendWarningEmail($email, $username, $reason, $isSuspended);
  }

  echo "<script>alert('Rapport envoyé avec succès.'); window.location.href='rapport.php';</script>";
} else {
  echo "Requête invalide.";
}
?>
