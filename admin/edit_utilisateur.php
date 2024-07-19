<?php include 'includes/session.php'; ?>
<?php
  $id = $_GET['id'];

  $conn = $pdo->open();

  try{
    $stmt = $conn->prepare("SELECT * FROM utilisateurs WHERE id=:id");
    $stmt->execute(['id'=>$id]);
    $user = $stmt->fetch();
  }
  catch(PDOException $e){
    $_SESSION['error'] = $e->getMessage();
  }

  $pdo->close();
?>

<?php include 'includes/header.php'; ?>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  <?php include 'includes/navbar.php'; ?>
  <?php include 'includes/menubar.php'; ?>
  <div class="content-wrapper">
    <section class="content-header">
      <h1>Modifier utilisateur</h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Accueil</a></li>
        <li><a href="utilisateurs.php">Utilisateurs</a></li>
        <li class="active">Modifier utilisateur</li>
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
              <h3 class="box-title">Modifier utilisateur</h3>
            </div>
            <div class="box-body">
              <form class="form-horizontal" method="POST" action="edit_utilisateur_traitement.php">
                <input type="hidden" name="id" value="<?php echo $user['id']; ?>">
                <div class="form-group">
                  <label for="nom_utilisateur" class="col-sm-3 control-label">Nom d'utilisateur</label>

                  <div class="col-sm-9">
                    <input type="text" class="form-control" id="nom_utilisateur" name="nom_utilisateur" value="<?php echo $user['nom_utilisateur']; ?>" required>
                  </div>
                </div>
                <div class="form-group">
                  <label for="prenom" class="col-sm-3 control-label">Prénom</label>

                  <div class="col-sm-9">
                    <input type="text" class="form-control" id="prenom" name="prenom" value="<?php echo $user['prenom']; ?>" required>
                  </div>
                </div>
                <div class="form-group">
                  <label for="email" class="col-sm-3 control-label">Email</label>

                  <div class="col-sm-9">
                    <input type="email" class="form-control" id="email" name="email" value="<?php echo $user['email']; ?>" required>
                  </div>
                </div>
                <div class="form-group">
                  <label for="sexe" class="col-sm-3 control-label">Sexe</label>

                  <div class="col-sm-9">
                    <select class="form-control" id="sexe" name="sexe" required>
                      <option value="M" <?php if($user['sexe'] == 'M') echo 'selected'; ?>>M</option>
                      <option value="F" <?php if($user['sexe'] == 'F') echo 'selected'; ?>>F</option>
                    </select>
                  </div>
                </div>
                <div class="form-group">
                  <label for="adresse" class="col-sm-3 control-label">Adresse</label>

                  <div class="col-sm-9">
                    <input type="text" class="form-control" id="adresse" name="adresse" value="<?php echo $user['adresse']; ?>" required>
                  </div>
                </div>
                <div class="form-group">
                  <label for="jour_naissance" class="col-sm-3 control-label">Jour de Naissance</label>

                  <div class="col-sm-9">
                    <input type="date" class="form-control" id="jour_naissance" name="jour_naissance" value="<?php echo $user['jour_naissance']; ?>" required>
                  </div>
                </div>
                <div class="form-group">
                  <label for="numero_phone" class="col-sm-3 control-label">Numéro de Téléphone</label>

                  <div class="col-sm-9">
                    <input type="text" class="form-control" id="numero_phone" name="numero_phone" value="<?php echo $user['numero_phone']; ?>" required>
                  </div>
                </div>
                <div class="form-group">
                  <div class="col-sm-offset-3 col-sm-9">
                    <button type="submit" class="btn btn-primary btn-flat">Mettre à jour</button>
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
