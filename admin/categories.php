<?php include 'includes/session.php'; ?>
<?php include 'includes/header.php'; ?>

<?php
// Code de traitement pour ajouter une catégorie
if(isset($_POST['add_category'])){
    $nom = $_POST['nom'];
    $description = $_POST['description'];

    $conn = $pdo->open();

    try{
        $stmt = $conn->prepare("SELECT COUNT(*) AS numrows FROM categories WHERE nom=:nom");
        $stmt->execute(['nom'=>$nom]);
        $row = $stmt->fetch();
        if($row['numrows'] > 0){
            $_SESSION['error'] = 'Cette catégorie existe déjà';
        }
        else{
            $stmt = $conn->prepare("INSERT INTO categories (nom, description) VALUES (:nom, :description)");
            $stmt->execute(['nom'=>$nom, 'description'=>$description]);
            $_SESSION['success'] = 'Catégorie ajoutée avec succès';
        }
    }
    catch(PDOException $e){
        $_SESSION['error'] = $e->getMessage();
    }

    $pdo->close();
}

// Code de traitement pour modifier une catégorie
if(isset($_POST['edit_category'])){
    $id = $_POST['id'];
    $nom = $_POST['nom'];
    $description = $_POST['description'];

    $conn = $pdo->open();

    try{
        $stmt = $conn->prepare("UPDATE categories SET nom=:nom, description=:description WHERE id=:id");
        $stmt->execute(['nom'=>$nom, 'description'=>$description, 'id'=>$id]);
        $_SESSION['success'] = 'Catégorie mise à jour avec succès';
    }
    catch(PDOException $e){
        $_SESSION['error'] = $e->getMessage();
    }

    $pdo->close();
}

// Code de traitement pour supprimer une catégorie
if(isset($_POST['delete_category'])){
    $id = $_POST['id'];

    $conn = $pdo->open();

    try{
        $stmt = $conn->prepare("DELETE FROM categories WHERE id=:id");
        $stmt->execute(['id'=>$id]);
        $_SESSION['success'] = 'Catégorie supprimée avec succès';
    }
    catch(PDOException $e){
        $_SESSION['error'] = $e->getMessage();
    }

    $pdo->close();
}
?>

<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  <?php include 'includes/navbar.php'; ?>
  <?php include 'includes/menubar.php'; ?>

  <div class="content-wrapper">
    <section class="content-header">
      <h1>Liste des catégories</h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Accueil</a></li>
        <li><a href="categories.php">Catégories</a></li>
        <li class="active">Liste des catégories</li>
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
              <button type="button" class="btn btn-primary btn-sm btn-flat" data-toggle="modal" data-target="#addNewCategory"><i class="fa fa-plus"></i> Nouveau</button>
            </div>
            <div class="box-body">
              <table class="table table-bordered">
                <thead>
                  <th>Nom de la Catégorie</th>
                  <th>Description</th>
                  <th>Date de Création</th>
                  <th>Actions</th>
                </thead>
                <tbody>
                  <?php
                    $conn = $pdo->open();

                    try{
                      $stmt = $conn->prepare("SELECT * FROM categories");
                      $stmt->execute();
                      foreach($stmt as $row){
                        echo "
                          <tr>
                            <td>".$row['nom']."</td>
                            <td>".$row['description']."</td>
                            <td>".$row['cree_le']."</td>
                            <td>
                              <button class='btn btn-success btn-sm edit btn-flat' data-id='".$row['id']."' data-nom='".$row['nom']."' data-description='".$row['description']."' data-toggle='modal' data-target='#editCategory'><i class='fa fa-edit'></i> Éditer</button>
                              <button class='btn btn-danger btn-sm delete btn-flat' data-id='".$row['id']."' data-toggle='modal' data-target='#deleteCategory'><i class='fa fa-trash'></i> Supprimer</button>
                            </td>
                          </tr>
                        ";
                      }
                    }
                    catch(PDOException $e){
                      echo $e->getMessage();
                    }

                    $pdo->close();
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
</div>

<!-- Modals -->
<!-- Add New Category Modal -->
<div class="modal fade" id="addNewCategory">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><b>Ajouter une nouvelle catégorie</b></h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" method="POST" action="categories.php">
                    <div class="form-group">
                        <label for="nom" class="col-sm-3 control-label">Nom de la catégorie</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="nom" name="nom" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="description" class="col-sm-3 control-label">Description</label>
                        <div class="col-sm-9">
                            <textarea class="form-control" id="description" name="description" required></textarea>
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Fermer</button>
                <button type="submit" class="btn btn-primary" name="add_category">Enregistrer</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Edit Category Modal -->
<div class="modal fade" id="editCategory">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><b>Modifier la catégorie</b></h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" method="POST" action="categories.php">
                    <input type="hidden" id="edit_id" name="id">
                    <div class="form-group">
                        <label for="edit_nom" class="col-sm-3 control-label">Nom de la catégorie</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="edit_nom" name="nom" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="edit_description" class="col-sm-3 control-label">Description</label>
                        <div class="col-sm-9">
                            <textarea class="form-control" id="edit_description" name="description" required></textarea>
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Fermer</button>
                <button type="submit" class="btn btn-success" name="edit_category">Mettre à jour</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Delete Category Modal -->
<div class="modal fade" id="deleteCategory">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><b>Supprimer la catégorie</b></h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" method="POST" action="categories.php">
                    <input type="hidden" id="delete_id" name="id">
                    <div class="text-center">
                        <p>Êtes-vous sûr de vouloir supprimer cette catégorie ?</p>
                        <h2 id="delete_nom" class="bold"></h2>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Fermer</button>
                <button type="submit" class="btn btn-danger" name="delete_category">Supprimer</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/scripts.php'; ?>
<script>
$(function(){
    // Edit button
    $(document).on('click', '.edit', function(){
        var id = $(this).data('id');
        var nom = $(this).data('nom');
        var description = $(this).data('description');
        $('#edit_id').val(id);
        $('#edit_nom').val(nom);
        $('#edit_description').val(description);
    });

    // Delete button
    $(document).on('click', '.delete', function(){
        var id = $(this).data('id');
        var nom = $(this).data('nom');
        $('#delete_id').val(id);
        $('#delete_nom').text(nom);
    });
});
</script>
</body>
</html>
