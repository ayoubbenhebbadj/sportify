<?php include("../../phpConfig/constants.php"); ?>
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
    <input type="text" class="search-bar" placeholder="Rechercher..." />
    <button class="btn-ajouter" onclick="ouvrirFormulaire()">Ajouter un gestionnaire</button>
  </div>
  <!-- Add Gestionnaire Modal -->
  <div id="addUserModal" class="modal">
    <div class="modal-content">
      <span class="close-btn" onclick="fermerFormulaire()">&times;</span>
      <h2>Ajouter un gestionnaire</h2>
      <form id="addUserForm">
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
        <input type="hidden" name="clubtype" value="gestionnaire" />
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
        <th>Statut</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php
        $sql = "SELECT * FROM user WHERE type='gestionnaire'";
        $res = mysqli_query($conn, $sql);
        if ($res && mysqli_num_rows($res) > 0) {
          while ($row = mysqli_fetch_assoc($res)) {
            $id = $row['id'];
            $prenom = $row['firstname'];
            $nom = $row['lastname'];
            $statut = $row['state'];
            $classStatut = "state" . strtolower($statut);
            echo "<tr>
              <td>$id</td>
              <td>$prenom</td>
              <td>$nom</td>
              <td class='$classStatut'>$statut</td>
              <td>
                <button class='btn-modifier'><i class='fas fa-edit'></i></button>
                <button class='btn-suspendre' data-id='$id'><i class='fas fa-pause'></i></button>
                <button class='btn-info' onclick='afficherInfos($id)'><i class='fas fa-info-circle'></i></button>
                <button class='btn-supprimer'><i class='fas fa-trash-alt'></i></button>
              </td>
            </tr>";
          }
        } else {
          echo "<tr><td colspan='5'>Aucun gestionnaire trouvé</td></tr>";
        }
      ?>
    </tbody>
  </table>
</div>
<script>
  document.querySelectorAll('.btn-suspendre').forEach(button => {
    button.addEventListener('click', function () {
      const id = this.dataset.id;
      fetch('toggle_status.php', {
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
  document.querySelectorAll('.btn-supprimer').forEach(button => {
    button.addEventListener('click', function () {
      if (!confirm('Voulez-vous vraiment supprimer ce gestionnaire ?')) return;
      const row = this.closest('tr');
      const id = row.querySelector('td').textContent;
      fetch('delete_user.php', {
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
  function afficherInfos(userId) {
    fetch('get_user.php', {
      method: 'POST',
      headers: {'Content-Type': 'application/x-www-form-urlencoded'},
      body: 'id=' + userId
    })
    .then(res => res.json())
    .then(user => {
      document.getElementById('info-id').textContent = user.id;
      document.getElementById('info-email').textContent = user.email;
      document.getElementById('info-phone').textContent = user.phone;
      document.getElementById('info-type').textContent = user.type;
      document.getElementById('infoModal').style.display = 'block';
    });
  }

  // Modal open/close
  function ouvrirFormulaire() {
    document.getElementById('addUserModal').style.display = 'block';
  }
  function fermerFormulaire() {
    document.getElementById('addUserModal').style.display = 'none';
  }
  window.onclick = function(event) {
    var modal = document.getElementById('addUserModal');
    if (event.target == modal) {
      modal.style.display = "none";
    }
  }

  // Add gestionnaire via AJAX
  document.getElementById('addUserForm').onsubmit = function(e) {
    e.preventDefault();
    var formData = new FormData(this);
    formData.append('submit', '1');
    fetch('ajouter_user.php', {
      method: 'POST',
      body: formData
    })
    .then(res => res.text())
    .then(response => {
      window.location.reload();
    })
    .catch(() => alert("Erreur lors de l'ajout du gestionnaire"));
  };
</script>
</body>
</html>
