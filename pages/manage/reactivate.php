<?php
include("../../phpConfig/constants.php");

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['type'], $_POST['id'])) {
    $type = $_POST['type']; // 'user' or 'gestion'
    $id = intval($_POST['id']);
    $table = ($type === 'user') ? 'user' : 'gestion';

    // 1. Reactivate the account
    $update = $conn->prepare("UPDATE $table SET state = 'active' WHERE id = ?");
    $update->bind_param("i", $id);
    $success = $update->execute();

    if ($success) {
        // 2. Archive all previous warnings
        $archive = $conn->prepare("UPDATE warnings SET is_archived = 1 WHERE target_type = ? AND target_id = ?");
        $archive->bind_param("si", $type, $id);
        $archive->execute();

        echo "<script>alert('Compte réactivé et avertissements archivés avec succès.'); window.location.href='manage_accounts.php';</script>";
    } else {
        echo "<script>alert('Erreur lors de la réactivation du compte.'); history.back();</script>";
    }
} else {
    echo "Requête invalide.";
}
?>
