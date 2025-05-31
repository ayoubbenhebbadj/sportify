<?php
include("../../phpConfig/constants.php");

$users = mysqli_query($conn, "SELECT id, username FROM user");
$gestions = mysqli_query($conn, "SELECT id, username FROM gestion");
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Créer un Rapport</title>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@600;700;800&display=swap">
  <style>
    :root {
      --sportify-primary: #00BFFF;
      --sportify-dark: #1a1a1a;
      --sportify-light-bg: #f0f2f5;
      --sportify-text-light: #ffffff;
      --sportify-text-dark: #333333;
      --sportify-secondary-btn: #6c757d;
      --sportify-success: #28a745;
      --sportify-info: #17a2b8;
      --sportify-danger: #dc3545;
    }
    body {
      font-family: 'Inter', sans-serif;
      background: var(--sportify-light-bg);
      color: var(--sportify-text-dark);
      padding: 40px 0;
    }
    .rapport-form {
      background: var(--sportify-text-light);
      padding: 32px 32px 24px 32px;
      border-radius: 14px;
      max-width: 600px;
      margin: auto;
      box-shadow: 0 8px 20px rgba(0,0,0,0.08);
    }
    h2 {
      font-family: 'Poppins', sans-serif;
      text-align: center;
      margin-bottom: 24px;
      color: var(--sportify-primary);
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 1px;
    }
    label {
      display: block;
      margin: 18px 0 7px;
      font-weight: 600;
      color: var(--sportify-dark);
    }
    .recipient-type-group {
      display: flex;
      gap: 20px;
      margin-bottom: 18px;
      justify-content: center;
    }
    .recipient-type-group label {
      margin: 0;
      font-weight: 500;
      color: var(--sportify-dark);
      cursor: pointer;
      padding: 8px 18px;
      border-radius: 20px;
      border: 2px solid var(--sportify-primary);
      background: var(--sportify-light-bg);
      transition: background 0.2s, color 0.2s;
      user-select: none;
    }
    .recipient-type-group input[type="radio"] {
      display: none;
    }
    .recipient-type-group input[type="radio"]:checked + label {
      background: var(--sportify-primary);
      color: var(--sportify-text-light);
      border-color: var(--sportify-primary);
    }
    .recipient-list {
      margin-bottom: 16px;
      max-height: 180px;
      overflow-y: auto;
      border: 1px solid #ced4da;
      border-radius: 6px;
      background: var(--sportify-light-bg);
      padding: 10px 12px;
      display: flex;
      flex-direction: column;
      gap: 8px;
    }
    .recipient-list label {
      display: flex;
      align-items: center;
      font-weight: 400;
      margin: 0;
      color: var(--sportify-text-dark);
      padding: 0;
      border: none;
      background: none;
    }
    .recipient-list input[type="checkbox"] {
      margin-right: 8px;
      accent-color: var(--sportify-primary);
    }
    select, textarea, button {
      width: 100%;
      padding: 12px;
      border-radius: 6px;
      border: 1px solid #ced4da;
      font-family: 'Inter', sans-serif;
      font-size: 1rem;
      margin-bottom: 8px;
      background: var(--sportify-text-light);
      color: var(--sportify-text-dark);
      transition: border-color 0.2s;
    }
    select:focus, textarea:focus {
      border-color: var(--sportify-primary);
      outline: none;
      box-shadow: 0 0 0 0.2rem rgba(0,191,255,0.10);
    }
    button {
      background: var(--sportify-primary);
      color: var(--sportify-text-light);
      border: none;
      font-weight: 600;
      font-family: 'Inter', sans-serif;
      border-radius: 6px;
      cursor: pointer;
      margin-top: 18px;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      transition: background 0.2s;
    }
    button:hover {
      background: #009acd;
    }
    .hidden { display: none; }
    @media (max-width: 600px) {
      .rapport-form { padding: 15px 5px; }
      h2 { font-size: 1.2em; }
    }
  </style>
</head>
<body>
<div class="rapport-form">
  <h2>Créer un Rapport</h2>
  <form action="add_warning.php" method="POST">
    <label>Envoyer à :</label>
    <div class="recipient-type-group">
      <input type="radio" id="type-user" name="type" value="user" onchange="updateRecipientType()" required>
      <label for="type-user">Utilisateur</label>
      <input type="radio" id="type-gestion" name="type" value="gestion" onchange="updateRecipientType()">
      <label for="type-gestion">Gestion</label>
    </div>

    <div id="userList" class="recipient-list hidden">
      <?php mysqli_data_seek($users, 0); while ($row = mysqli_fetch_assoc($users)) { ?>
        <label>
          <input type="checkbox" name="recipients[]" value="user-<?php echo $row['id']; ?>">
          <?php echo htmlspecialchars($row['username']); ?>
        </label>
      <?php } ?>
    </div>

    <div id="gestionList" class="recipient-list hidden">
      <?php mysqli_data_seek($gestions, 0); while ($row = mysqli_fetch_assoc($gestions)) { ?>
        <label>
          <input type="checkbox" name="recipients[]" value="gestion-<?php echo $row['id']; ?>">
          <?php echo htmlspecialchars($row['username']); ?>
        </label>
      <?php } ?>
    </div>

    <label for="reason">Motif du rapport :</label>
    <textarea name="reason" rows="5" required></textarea>

    <button type="submit">Envoyer le rapport</button>
  </form>
</div>
<script>
function updateRecipientType() {
  const userRadio = document.getElementById("type-user");
  const gestionRadio = document.getElementById("type-gestion");
  document.getElementById("userList").classList.toggle("hidden", !userRadio.checked);
  document.getElementById("gestionList").classList.toggle("hidden", !gestionRadio.checked);
}
</script>
</body>
</html>