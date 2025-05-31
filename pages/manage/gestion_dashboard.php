<?php
include('../../phpConfig/constants.php');

// Redirect to login if not logged in
if (!isset($_SESSION['gestion_id'])) {
    header('Location: login_gestion.php'); // change to your actual login page
    exit();
}

$gestion_id = $_SESSION['gestion_id']; // you can now use this in your queries
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tableau de Bord Admin</title>
  <link rel="stylesheet" href="admin.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>

  <header class="header">
    <div class="search-bar">
      <input type="text" placeholder="Rechercher...">
      <button><i class="fas fa-search"></i></button>
    </div>
    <div class="header-buttons">
      <button class="notification-button">
        <i class="fas fa-bell"></i>
        <span class="notification-count">20</span> 
      </button>
      <button class="logout-button">
        <i class="fas fa-sign-out-alt"></i> Déconnexion
      </button>
    </div>
    <div class="logo">
      <img src="#" alt="Sportify">
    </div>
    <div class="home-link">
      <a href="#"><i class="fas fa-home"></i> Accueil</a>
    </div>
  </header>

  <div class="container">
    <aside class="sidebar">
      <h1>⚽️ SportApp</h1>
      <ul>
        <li><a href="#"><i class="fas fa-home"></i> Dashboard</a></li>
        <li><a href="#"><i class="fas fa-calendar-check"></i> Réservations</a></li>
        <li><a href="#"><i class="fas fa-file-alt"></i> Rapports</a></li>
        <li><a href="#"><i class="fas fa-chart-line"></i> Statistiques</a></li>
        <li><a href="#"><i class="fas fa-bell"></i> Notifications</a></li>
        <li><a href="#"><i class="fas fa-cog"></i> Paramètres</a></li>
      </ul>
    </aside>

    <main class="main-content">
      <h2>Tableau de Bord Gestionnaire</h2>

      <div class="dashboard-container">

        <!-- Réservations Box -->
        <div class="dashboard-box">
          <i class="fas fa-calendar-check"></i>
          <h3>Gestion des Réservations</h3>
          <p>Visualisez et gérez toutes les réservations en cours et à venir.</p>
          <a href="gestion_reservation_manage.php" class="dashboard-btn">Accéder</a>
        </div>

        <!-- Rapports Box -->
        <div class="dashboard-box">
          <i class="fas fa-file-alt"></i>
          <h3>Rapports</h3>
          <p>Consultez les rapports d’activité, d’usage et d’historique.</p>
          <a href="rapports.php" class="dashboard-btn">Accéder</a>
        </div>

        <!-- Statistiques Box -->
        <div class="dashboard-box">
          <i class="fas fa-chart-line"></i>
          <h3>Statistiques</h3>
          <p>Analysez les données clés de fréquentation et de performance.</p>
          <a href="statistiques.php" class="dashboard-btn">Accéder</a>
        </div>

      </div>
    </main>
  </div>

</body>
</html>
