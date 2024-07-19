<?php include 'includes/session.php'; ?>
<?php include 'includes/header.php'; ?>
<style>
    /* Ajoutez ce style pour aligner les boutons d'action */
    .btn-action {
        display: inline-block;
        width: 100%; /* Prendre toute la largeur de la cellule */
        margin: 2px 0; /* Ajouter une marge en haut et en bas */
    }

    /* Éliminer le défilement dans le panneau latéral */
    .sidebar {
        overflow: hidden; /* Éliminer le défilement */
    }

    /* Ajuster la largeur du tableau */
    .table {
        width: 100%; /* Utiliser toute la largeur disponible */
        table-layout: fixed; /* Assurer que les colonnes sont de largeur fixe */
    }

    .content-wrapper {
        margin-left: 180px; /* Ajuster la marge de contenu en fonction de la nouvelle largeur du panneau */
        padding: 0 20px; /* Ajouter des marges à gauche et à droite */
    }

    .table th, .table td {
        font-size: 12px; /* Taille de la police ajustée */
        padding: 6px; /* Ajuster le padding */
        word-wrap: break-word; /* Forcer les mots longs à se briser et s'afficher sur plusieurs lignes */
        white-space: normal; /* Permettre le retour à la ligne */
    }

    .btn-sm {
        padding: 4px 8px; /* Ajuster la taille des boutons */
        font-size: 12px; /* Taille de la police des boutons */
    }

    /* Ajouter une largeur minimale pour le container de la boîte */
    .box {
        min-width: 1000px; /* Ajuster la largeur minimale de la boîte pour forcer la largeur du tableau */
    }
</style>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  <?php include 'includes/navbar.php'; ?>
  <?php include 'includes/menubar.php'; ?>

  <div class="content-wrapper">
    <section class="content-header">
      <h1>Liste des utilisateurs</h1>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#"><i class="fa fa-dashboard"></i> Accueil</a></li>
        <li class="breadcrumb-item"><a href="utilisateurs.php">Utilisateurs</a></li>
        <li class="breadcrumb-item active">Liste des utilisateurs</li>
      </ol>
    </section>

    <section class="content">
      <?php
        if(isset($_SESSION['error'])){
          echo "
            <div class='alert alert-danger alert-dismissible'>
              <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
              <h4><i class='icon fa fa-warning'></i> Erreur!</h4>
              ".$_SESSION['error']."
            </div>
          ";
          unset($_SESSION['error']);
        }
        if(isset($_SESSION['success'])){
          echo "
            <div class='alert alert-success alert-dismissible'>
              <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
              <h4><i class='icon fa fa-check'></i> Succès!</h4>
              ".$_SESSION['success']."
            </div>
          ";
          unset($_SESSION['success']);
        }
      ?>
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header with-border">
              <div class="row">
                <div class="col-md-6">
                  <a href="ajout_utilisateur.php" class="btn btn-primary btn-sm btn-flat"><i class="fa fa-plus"></i> Nouveau</a>
                </div>
                <div class="col-md-6">
                  <div class="input-group input-group-sm" style="width: 300px; float: right;">
                    <input type="text" name="table_search" class="form-control pull-right" placeholder="Rechercher un utilisateur" id="searchUser">
                    <div class="input-group-btn">
                      <button type="button" class="btn btn-default"><i class="fa fa-search"></i></button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="box-body">
              <div id="noResults" style="display: none;">Aucun utilisateur trouvé</div>
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                  <th>Nom</th>
                  <th>Prénom</th>
                  <th>Email</th>
                  <th>Sexe</th>
                  <th>Adresse</th>
                  <th>Jour de Naissance</th>
                  <th>Numéro de Téléphone</th>
                  <th>Actions</th>
                </thead>
                <tbody id="userTable">
                  <?php
                    $conn = new mysqli("localhost", "root", "", "ecoblessing");

                    if ($conn->connect_error) {
                        die("Connection failed: " . $conn->connect_error);
                    }

                    $sql = "SELECT * FROM utilisateurs";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()){
                          echo "
                            <tr>
                              <td>".$row['nom_utilisateur']."</td>
                              <td>".$row['prenom']."</td>
                              <td>".$row['email']."</td>
                              <td>".$row['sexe']."</td>
                              <td>".$row['adresse']."</td>
                              <td>".$row['jour_naissance']."</td>
                              <td>".$row['numero_phone']."</td>
                              <td>
                                <a href='edit_utilisateur.php?id=".$row['id']."' class='btn btn-success btn-sm btn-action'><i class='fa fa-edit'></i> Éditer</a>
                                <button class='btn btn-danger btn-sm btn-action delete' data-id='".$row['id']."'><i class='fa fa-trash'></i> Supprimer</button>
                              </td>
                            </tr>
                          ";
                        }
                    } else {
                        echo "<tr><td colspan='8'>Aucun utilisateur trouvé</td></tr>";
                    }

                    $conn->close();
                  ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
  <?php include 'includes/footer.php'; ?>
  <?php include 'includes/scripts.php'; ?>
  <script>
    // Filtrer les utilisateurs par nom
    document.getElementById('searchUser').addEventListener('keyup', function() {
        var searchValue = this.value.toLowerCase();
        var rows = document.getElementById('userTable').getElementsByTagName('tr');
        var noResults = document.getElementById('noResults');
        var found = false;

        for (var i = 0; i < rows.length; i++) {
            var name = rows[i].getElementsByTagName('td')[0].textContent.toLowerCase();
            if (name.indexOf(searchValue) > -1) {
                rows[i].style.display = '';
                found = true;
            } else {
                rows[i].style.display = 'none';
            }
        }

        if (found) {
            noResults.style.display = 'none';
        } else {
            noResults.style.display = '';
        }
    });

    // Confirmation de suppression
    document.querySelectorAll('.delete').forEach(button => {
        button.addEventListener('click', function() {
            var userId = this.getAttribute('data-id');
            var confirmDelete = confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?');
            if (confirmDelete) {
                window.location.href = 'delete_utilisateur.php?id=' + userId;
            }
        });
    });
  </script>
</body>
</html>
