<?php include 'includes/session.php'; ?>
<?php include 'includes/header.php'; ?>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  <?php include 'includes/navbar.php'; ?>
  <?php include 'includes/menubar.php'; ?>
  <div class="content-wrapper">
    <section class="content-header">
      <h1>Ajouter un utilisateur</h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Accueil</a></li>
        <li><a href="utilisateurs.php">Utilisateurs</a></li>
        <li class="active">Ajouter un utilisateur</li>
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
              <h3 class="box-title">Nouvel utilisateur</h3>
            </div>
            <div class="box-body">
              <form class="form-horizontal" method="POST" action="ajout_utilisateur_traitement.php">
                <div class="form-group">
                  <label for="nom_utilisateur" class="col-sm-3 control-label">Nom d'utilisateur</label>

                  <div class="col-sm-9">
                    <input type="text" class="form-control" id="nom_utilisateur" name="nom_utilisateur" required>
                  </div>
                </div>
                <div class="form-group">
                  <label for="prenom" class="col-sm-3 control-label">Prénom</label>

                  <div class="col-sm-9">
                    <input type="text" class="form-control" id="prenom" name="prenom" required>
                  </div>
                </div>
                <div class="form-group">
                  <label for="email" class="col-sm-3 control-label">Email</label>

                  <div class="col-sm-9">
                    <input type="email" class="form-control" id="email" name="email" required>
                  </div>
                </div>
                <div class="form-group">
                  <label for="sexe" class="col-sm-3 control-label">Sexe</label>

                  <div class="col-sm-9">
                    <select class="form-control" id="sexe" name="sexe" required>
                      <option value="M">M</option>
                      <option value="F">F</option>
                    </select>
                  </div>
                </div>
                <div class="form-group">
                  <label for="adresse" class="col-sm-3 control-label">Adresse</label>

                  <div class="col-sm-9">
                    <input type="text" class="form-control" id="adresse" name="adresse" required>
                  </div>
                </div>
                <div class="form-group">
                  <label for="jour_naissance" class="col-sm-3 control-label">Jour de Naissance</label>

                  <div class="col-sm-9">
                    <input type="date" class="form-control" id="jour_naissance" name="jour_naissance" required>
                  </div>
                </div>
                <div class="form-group">
                  <label for="numero_phone" class="col-sm-3 control-label">Numéro de Téléphone</label>

                  <div class="col-sm-9">
                    <input type="text" class="form-control" id="numero_phone" name="numero_phone" required>
                  </div>
                </div>
                <div class="form-group">
                  <label for="mot_de_passe" class="col-sm-3 control-label">Mot de Passe</label>

                  <div class="col-sm-9">
                    <input type="password" class="form-control" id="mot_de_passe" name="mot_de_passe" required>
                  </div>
                </div>
                <div class="form-group">
                  <div class="col-sm-offset-3 col-sm-9">
                    <button type="submit" class="btn btn-primary btn-flat">Enregistrer</button>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
  <?php include 'includes/footer.php'; ?>
</div>
<?php include 'includes/scripts.php'; ?>
</body>
</html>
