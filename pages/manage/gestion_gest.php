<?php
include("../../phpConfig/constants.php");
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Gestion des Gestionnaires</title>
  <link rel="stylesheet" href="gestion_user.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"/>
</head>
<body>

<header>
  <div class="logo">Gestion des Gestionnaires</div>
</header>

<div class="container">
  <div class="top-bar">
    <input type="text" class="search-bar" placeholder="Rechercher..." id="searchInput" />
    <button class="btn-ajouter" onclick="ouvrirFormulaire()">Ajouter un gestionnaire</button>
  </div>
  <!-- Add Gestionnaire Modal -->
  <div id="addGestModal" class="modal">
    <div class="modal-content">
      <span class="close-btn" onclick="fermerFormulaire()">&times;</span>
      <h2>Ajouter un gestionnaire</h2>
      <form id="addGestForm">
        <div class="input-group">
          <input class="input" type="text" name="firstname" placeholder="Prénom" required>
          <input class="input" type="text" name="lastname" placeholder="Nom" required>
        </div>
        <div class="input-group">
          <input class="input" type="text" name="username" placeholder="Nom d'utilisateur" required>
          <input class="input" type="email" name="email" placeholder="Email" required>
        </div>
        <div class="input-group">
          <input class="input" type="number" name="phone" placeholder="Téléphone" required>
          <input class="input" type="password" name="password" placeholder="Mot de passe" required>
        </div>
        <button type="submit" class="submit-btn">Ajouter</button>
      </form>
    </div>
  </div>

  <table class="table-utilisateurs">
    <thead>
      <tr>
        <th>ID</th>
        <th>Prénom</th>
        <th>Nom</th>
        <th>Nom d'utilisateur</th>
        <th>Email</th>
        <th>Téléphone</th>
        <th>Statut</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php
        $sql = "SELECT * FROM gestion";
        $res = mysqli_query($conn, $sql);
        if ($res && mysqli_num_rows($res) > 0) {
          while ($row = mysqli_fetch_assoc($res)) {
            $id = $row['id'];
            $prenom = htmlspecialchars($row['firstname']);
            $nom = htmlspecialchars($row['lastname']);
            $username = htmlspecialchars($row['username']);
            $email = htmlspecialchars($row['email']);
            $phone = htmlspecialchars($row['phone']);
            $statut = isset($row['state']) ? $row['state'] : 'active';
            $classStatut = "state" . strtolower($statut);
            echo "<tr>
              <td>$id</td>
              <td>$prenom</td>
              <td>$nom</td>
              <td>$username</td>
              <td>$email</td>
              <td>$phone</td>
              <td class='$classStatut'>$statut</td>
              <td>
                <button class='btn-info' onclick='afficherInfos($id)'><i class='fas fa-info-circle'></i></button>
                <button class='btn-suspendre' data-id='$id'><i class='fas fa-pause'></i></button>
                <button class='btn-supprimer'><i class='fas fa-trash-alt'></i></button>
              </td>
            </tr>";
          }
        } else {
          echo "<tr><td colspan='8'>Aucun gestionnaire trouvé</td></tr>";
        }
      ?>
    </tbody>
  </table>
</div>

<!-- Info Modal -->
<div id="infoModal" class="modal">
  <div class="modal-content">
    <span class="close-btn" onclick="fermerInfoModal()">&times;</span>
    <h2>Détails du gestionnaire</h2>
    <div class="user-details">
      <p><strong>ID:</strong> <span id="info-id"></span></p>
      <p><strong>Prénom:</strong> <span id="info-firstname"></span></p>
      <p><strong>Nom:</strong> <span id="info-lastname"></span></p>
      <p><strong>Nom d'utilisateur:</strong> <span id="info-username"></span></p>
      <p><strong>Email:</strong> <span id="info-email"></span></p>
      <p><strong>Téléphone:</strong> <span id="info-phone"></span></p>
    </div>
  </div>
</div>

<script>
  // Search functionality
  document.getElementById('searchInput').addEventListener('keyup', function() {
    const input = this.value.toLowerCase();
    const rows = document.querySelectorAll('.table-utilisateurs tbody tr');
    rows.forEach(row => {
      const text = row.textContent.toLowerCase();
      row.style.display = text.includes(input) ? '' : 'none';
    });
  });

  // Modal open/close
  function ouvrirFormulaire() {
    document.getElementById('addGestModal').style.display = 'block';
  }
  function fermerFormulaire() {
    document.getElementById('addGestModal').style.display = 'none';
  }
  function fermerInfoModal() {
    document.getElementById('infoModal').style.display = 'none';
  }
  window.onclick = function(event) {
    var modal = document.getElementById('addGestModal');
    var infoModal = document.getElementById('infoModal');
    if (event.target == modal) modal.style.display = "none";
    if (event.target == infoModal) infoModal.style.display = "none";
  }

  // Add gestionnaire via AJAX
  document.getElementById('addGestForm').onsubmit = function(e) {
    e.preventDefault();
    var formData = new FormData(this);
    formData.append('submit', '1');
    fetch('ajouter_gest.php', {
      method: 'POST',
      body: formData
    })
    .then(res => res.text())
    .then(response => {
      window.location.reload();
    })
    .catch(() => alert("Erreur lors de l'ajout du gestionnaire"));
  };

  // Delete gestionnaire via AJAX
  document.querySelectorAll('.btn-supprimer').forEach(button => {
    button.addEventListener('click', function () {
      if (!confirm('Voulez-vous vraiment supprimer ce gestionnaire ?')) return;
      const row = this.closest('tr');
      const id = row.querySelector('td').textContent;
      fetch('delete_gest.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: 'id=' + encodeURIComponent(id)
      })
      .then(res => res.json())
      .then(response => {
        if (response.status === 'success') {
          row.remove();
        } else {
          alert("Erreur lors de la suppression : " + (response.message || ''));
          if (response.message) console.error(response.message);
        }
      })
      .catch((err) => {
        alert("Erreur lors de la suppression du gestionnaire");
        console.error(err);
      });
    });
  });

  // Suspend/resume gestionnaire via AJAX
  document.querySelectorAll('.btn-suspendre').forEach(button => {
    button.addEventListener('click', function () {
      const id = this.dataset.id;
      fetch('toggle_gest_status.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: 'id=' + id
      })
      .then(res => res.text())
      .then(state => {
        const row = this.closest('tr');
        const statusCell = row.querySelector('td[class^="state"]');
        statusCell.textContent = state;
        statusCell.className = 'state' + state.toLowerCase();
        this.innerHTML = state === 'suspended' ? '<i class="fas fa-play"></i>' : '<i class="fas fa-pause"></i>';
      });
    });
  });

  // Info gestionnaire via AJAX
  function afficherInfos(gestId) {
    fetch('get_gest.php', {
      method: 'POST',
      headers: {'Content-Type': 'application/x-www-form-urlencoded'},
      body: 'id=' + gestId
    })
    .then(res => res.json())
    .then(gest => {
      document.getElementById('info-id').textContent = gest.id;
      document.getElementById('info-firstname').textContent = gest.firstname;
      document.getElementById('info-lastname').textContent = gest.lastname;
      document.getElementById('info-username').textContent = gest.username;
      document.getElementById('info-email').textContent = gest.email;
      document.getElementById('info-phone').textContent = gest.phone;
      document.getElementById('infoModal').style.display = 'block';
    })
    .catch(() => alert("Erreur lors de la récupération des données"));
  }
</script>
</body>
</html>