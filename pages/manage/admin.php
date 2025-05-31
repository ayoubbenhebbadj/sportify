<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tableau de Bord</title>
  <link rel="stylesheet" href="admin.css">
  <!-- Icônes FontAwesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <style>
  /* ...existing code... */
  .container a,
  .sidebar a,
  .dashboard-btn,
  .home-link a {
    text-decoration: none !important;
  }
  /* ...existing code... */
  </style>
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
      <a href="#"><i class="fas fa-home"></i> Home</a>
    </div>
  </header>

  <div class="container">
    <aside class="sidebar">
      <h1>⚽️ SportApp</h1>
      <ul>
        <li><a href="#"><i class="fas fa-home"></i> Dashboards </a></li>
        <li><a href="#"><i class="fas fa-users"></i> Utilisateurs</a></li>
        <li><a href="#"><i class="fas fa-user-tie"></i> Gestionnaires</a></li>
        <li><a href="#"><i class="fas fa-building"></i> Infrastructures</a></li>
        <li><a href="#"><i class="fas fa-calendar-check"></i> Réservations</a></li>
        <li><a href="#"><i class="fas fa-file-alt"></i> Rapports</a></li>
        <li><a href="#"><i class="fas fa-bell"></i> Notifications</a></li>
        <li><a href="#"><i class="fas fa-cog"></i> Paramètres</a></li>
      </ul>
    </aside>

    <main class="main-content">
      <h2>Tableau de Bord Administrateur</h2>

      <div class="dashboard-container">
        <!-- Utilisateurs Box -->
        <div class="dashboard-box">
          <i class="fas fa-users"></i>
          <h3>Gestion des Utilisateurs</h3>
          <p>Administrez les comptes des utilisateurs et leurs permissions.</p>
          <a href="gestion_user.php" class="dashboard-btn">Accéder</a>
        </div>

        <!-- Gestionnaires Box -->
        <div class="dashboard-box">
          <i class="fas fa-user-tie"></i>
          <h3>Gestion des Gestionnaires</h3>
          <p>Administrez les comptes des gestionnaires et leurs droits d'accès.</p>
          <a href="gestion_gest.php" class="dashboard-btn">Accéder</a>
        </div>

        <!-- Infrastructures Box -->
        <div class="dashboard-box">
          <i class="fas fa-building"></i>
          <h3>Gestion des Infrastructures</h3>
          <p>Contrôlez les installations sportives et leurs disponibilités.</p>
          <a href="../../pages/inframanage.php" class="dashboard-btn">Accéder</a>
        </div>

        <!-- Réservations Box -->
        <div class="dashboard-box">
          <i class="fas fa-calendar-check"></i>
          <h3>Gestion des Réservations</h3>
          <p>Visualisez et gérez toutes les réservations en cours et à venir.</p>
          <a href="admin_reservation_manage.php" class="dashboard-btn">Accéder</a>
        </div>

        <!-- Rapports Box -->
        <div class="dashboard-box">
          <i class="fas fa-file-alt"></i>
          <h3>Gestion des Rapports</h3>
          <p>Générez et consultez les rapports d'activité et de performance.</p>
          <a href="rapport.php" class="dashboard-btn">Accéder</a>
        </div>

        <!-- Statistiques Box -->
        <div class="dashboard-box">
          <i class="fas fa-chart-line"></i>
          <h3>Statistiques</h3>
          <p>Visualisez les données clés et les tendances d'utilisation.</p>
          <button class="dashboard-btn" onclick="openModal('modal-statistics')">Accéder</button>
        </div>
      </div>
    </main>
  </div>

  <!-- Statistiques Modal -->
  <div id="modal-statistics" class="modal" style="display:none; position:fixed; z-index:9999; left:0; top:0; width:100vw; height:100vh; background:rgba(0,0,0,0.25); align-items:center; justify-content:center;">
    <div class="modal-content" style="max-width: 700px; margin:60px auto; background:#fff; border-radius:18px; box-shadow:0 8px 32px rgba(0,191,255,0.10),0 2px 8px rgba(0,0,0,0.06); padding:32px 32px 24px 32px; position:relative;">
      <span class="close" onclick="closeModal('modal-statistics')" style="position:absolute;top:18px;right:24px;font-size:2em;cursor:pointer;color:#00BFFF;">&times;</span>
      <h3 style="text-align:center; color:#00BFFF; font-family:'Poppins',sans-serif; margin-bottom:24px;">Statistiques du site</h3>
      <div id="stats-summary" style="display:flex;justify-content:space-around;margin:30px 0 20px 0;gap:18px;flex-wrap:wrap;">
        <div style="background:#f6fafd;border-radius:10px;padding:18px 28px;text-align:center;box-shadow:0 2px 8px rgba(0,191,255,0.08);min-width:120px;">
          <div style="font-size:2em;color:#00BFFF;"><i class="fas fa-users"></i></div>
          <div style="font-size:1.3em;font-weight:600;" id="stat-users">...</div>
          <div style="font-size:0.95em;color:#666;">Utilisateurs</div>
        </div>
        <div style="background:#f6fafd;border-radius:10px;padding:18px 28px;text-align:center;box-shadow:0 2px 8px rgba(0,191,255,0.08);min-width:120px;">
          <div style="font-size:2em;color:#00BFFF;"><i class="fas fa-calendar-check"></i></div>
          <div style="font-size:1.3em;font-weight:600;" id="stat-reservations">...</div>
          <div style="font-size:0.95em;color:#666;">Réservations</div>
        </div>
        <div style="background:#f6fafd;border-radius:10px;padding:18px 28px;text-align:center;box-shadow:0 2px 8px rgba(0,191,255,0.08);min-width:120px;">
          <div style="font-size:2em;color:#00BFFF;"><i class="fas fa-building"></i></div>
          <div style="font-size:1.3em;font-weight:600;" id="stat-infras">...</div>
          <div style="font-size:0.95em;color:#666;">Infrastructures</div>
        </div>
      </div>
      <div style="margin:30px 0;">
        <canvas id="reservationsChart" height="120"></canvas>
      </div>
      <div style="text-align:center;">
        <button class="dashboard-btn" onclick="closeModal('modal-statistics')" style="margin-top:10px;">Fermer</button>
      </div>
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script>
  function openModal(modalId) {
    document.getElementById(modalId).style.display = "flex";
    if(modalId === 'modal-statistics') loadStatistics();
  }
  function closeModal(modalId) {
    document.getElementById(modalId).style.display = "none";
  }
  window.onclick = function(event) {
    var modal = document.getElementById('modal-statistics');
    if (event.target === modal) modal.style.display = "none";
  }
  // Fetch stats from PHP
  function loadStatistics() {
    fetch('admin_stats_api.php')
      .then(res => res.json())
      .then(data => {
        document.getElementById('stat-users').textContent = data.users;
        document.getElementById('stat-reservations').textContent = data.reservations;
        document.getElementById('stat-infras').textContent = data.infrastructures;
        renderReservationsChart(data.reservations_per_month);
      });
  }

  let reservationsChartInstance = null;
  function renderReservationsChart(monthData) {
    const ctx = document.getElementById('reservationsChart').getContext('2d');
    if (reservationsChartInstance) reservationsChartInstance.destroy();
    reservationsChartInstance = new Chart(ctx, {
      type: 'bar',
      data: {
        labels: monthData.labels,
        datasets: [{
          label: 'Réservations par mois',
          data: monthData.data,
          backgroundColor: 'rgba(0,191,255,0.7)',
          borderRadius: 8,
          maxBarThickness: 38
        }]
      },
      options: {
        plugins: {
          legend: { display: false }
        },
        scales: {
          x: { grid: { display: false } },
          y: { beginAtZero: true, grid: { color: '#e2e8f0' } }
        }
      }
    });
  }
  </script>
</body>
</html>