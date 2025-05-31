<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tableau de Bord Gestionnaire</title>
  <link rel="stylesheet" href="Gestionnaires.css">
  <!-- Icônes FontAwesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
  <header class="header">
    <!-- Barre de recherche -->
    <div class="search-bar">
      <input type="text" placeholder="Rechercher...">
      <button><i class="fas fa-search"></i></button>
    </div>

    <div class="header-buttons">
      <button class="notification-button">
        <i class="fas fa-bell"></i>
        <span class="notification-count">5</span>
      </button>
      <button class="logout-button">
        <i class="fas fa-sign-out-alt"></i> Déconnexion
      </button>
    </div>

    <!-- Logo -->
    <div class="logo">
      <img src="logo.png" alt="Sportify">
    </div>

    <!-- Lien vers la page d'accueil -->
    <div class="home-link">
      <a href="#"><i class="fas fa-home"></i> Home</a>
    </div>
  </header>

  <div class="container">
    <!-- Barre latérale -->
    <aside class="sidebar">
      <h1>⚽️ SportApp</h1>
      <ul>
        <li><a href="#"><i class="fas fa-home"></i> Dashboard</a></li>
        <li><a href="#"><i class="fas fa-calendar-check"></i> Réservations</a></li>
        <li><a href="#"><i class="fas fa-building"></i> Infrastructures</a></li>
        <li><a href="#"><i class="fas fa-file-alt"></i> Rapports</a></li>
        <li><a href="#"><i class="fas fa-bell"></i> Notifications</a></li>
      </ul>
    </aside>

    <!-- Main Content Area -->
    <main class="main-content">
      <h2>Tableau de Bord Gestionnaire</h2>
      
      
      <div class="dashboard-container">
        <!-- Réservations Box -->
        <div class="dashboard-box">
          <i class="fas fa-calendar-check"></i>
          <h3>Gestion des Réservations</h3>
          <p>Validez ou annulez les réservations pour vos infrastructures.</p>
          <button class="dashboard-btn" onclick="location.href='#'">Accéder</button>
        </div>
        
        <!-- Infrastructures Box -->
        <div class="dashboard-box">
          <i class="fas fa-building"></i>
          <h3>Gestion des Infrastructures</h3>
          <p>Modifiez la disponibilité et les informations de vos infrastructures.</p>
          <button class="dashboard-btn" onclick="location.href='#'">Accéder</button>
        </div>
        
        <!-- Rapports Box -->
        <div class="dashboard-box">
          <i class="fas fa-file-alt"></i>
          <h3>Génération de Rapports</h3>
          <p>Créez et envoyez des rapports d'activité à l'administrateur.</p>
          <button class="dashboard-btn" onclick="location.href='#'">Accéder</button>
        </div>
        
        <!-- Notifications Box -->
       <!-- Statistiques Box -->
       <div class="dashboard-box">
        <i class="fas fa-chart-line"></i>
        <h3>Statistiques</h3>
        <p>Visualisez les données clés et les tendances d'utilisation.</p>
        <button class="dashboard-btn" onclick="location.href='#'">Accéder</button>
      </div>
      </div>
    </main>
  </div>
</body>
</html>