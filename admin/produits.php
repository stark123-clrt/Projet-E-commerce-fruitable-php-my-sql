<?php include 'includes/session.php'; ?>
<?php include 'includes/header.php'; ?>

<?php
// Traitement des formulaires

if (isset($_POST['add_product'])) {
    $nom = $_POST['nom'];
    $description = $_POST['description'];
    $prix = $_POST['prix'];
    $categorie_id = $_POST['categorie_id'];
    $image = '';

    if (!empty($_FILES['image']['name'])) {
        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $new_filename = uniqid() . "." . $ext;
        move_uploaded_file($_FILES['image']['tmp_name'], '../images/' . $new_filename);
        $image = $new_filename;
    }

    $conn = $pdo->open();

    try {
        $stmt = $conn->prepare("INSERT INTO produits (nom, description, prix, image, categorie_id) VALUES (:nom, :description, :prix, :image, :categorie_id)");
        $stmt->execute(['nom' => $nom, 'description' => $description, 'prix' => $prix, 'image' => $image, 'categorie_id' => $categorie_id]);
        $_SESSION['success'] = 'Produit ajouté avec succès';
    } catch (PDOException $e) {
        $_SESSION['error'] = $e->getMessage();
    }

    $pdo->close();

    header('location: produits.php');
    exit();
}

if (isset($_POST['edit_product'])) {
    $id = $_POST['id'];
    $nom = $_POST['nom'];
    $description = $_POST['description'];
    $prix = $_POST['prix'];
    $categorie_id = $_POST['categorie_id'];
    $image = $_POST['image'];

    if (!empty($_FILES['image']['name'])) {
        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $new_filename = uniqid() . "." . $ext;
        move_uploaded_file($_FILES['image']['tmp_name'], '../images/' . $new_filename);
        $image = $new_filename;
    }

    $conn = $pdo->open();

    try {
        $stmt = $conn->prepare("UPDATE produits SET nom=:nom, description=:description, prix=:prix, image=:image, categorie_id=:categorie_id WHERE id=:id");
        $stmt->execute(['nom' => $nom, 'description' => $description, 'prix' => $prix, 'image' => $image, 'categorie_id' => $categorie_id, 'id' => $id]);
        $_SESSION['success'] = 'Produit mis à jour avec succès';
    } catch (PDOException $e) {
        $_SESSION['error'] = $e->getMessage();
    }

    $pdo->close();

    header('location: produits.php');
    exit();
}

if (isset($_POST['delete_product'])) {
    $id = $_POST['id'];

    $conn = $pdo->open();

    try {
        $stmt = $conn->prepare("DELETE FROM produits WHERE id=:id");
        $stmt->execute(['id' => $id]);
        $_SESSION['success'] = 'Produit supprimé avec succès';
    } catch (PDOException $e) {
        $_SESSION['error'] = $e->getMessage();
    }

    $pdo->close();

    header('location: produits.php');
    exit();
}

// Préparation des filtres
$where = '';
if (isset($_GET['category'])) {
    $catid = $_GET['category'];
    $where = 'WHERE produits.categorie_id =' . $catid;
}
if (isset($_GET['search'])) {
    $search = $_GET['search'];
    if ($where) {
        $where .= " AND produits.nom LIKE '%" . $search . "%'";
    } else {
        $where = "WHERE produits.nom LIKE '%" . $search . "%'";
    }
}
?>

<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  <?php include 'includes/navbar.php'; ?>
  <?php include 'includes/menubar.php'; ?>

  <div class="content-wrapper">
    <section class="content-header">
      <h1>Liste des produits</h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Accueil</a></li>
        <li><a href="produits.php">Produits</a></li>
        <li class="active">Liste des produits</li>
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
                <div class="col-md-3">
                  <button type="button" class="btn btn-primary btn-sm btn-flat" data-toggle="modal" data-target="#addNewProduct"><i class="fa fa-plus"></i> Nouveau</button>
                </div>
                <div class="col-md-9">
                  <div class="row">
                    <div class="col-md-6">
                      <form class="form-inline">
                        <div class="form-group">
                          <label>Catégorie: </label>
                          <select class="form-control input-sm" id="select_category">
                            <option value="0">TOUTES</option>
                            <?php
                              $conn = $pdo->open();

                              $stmt = $conn->prepare("SELECT * FROM categories");
                              $stmt->execute();

                              foreach($stmt as $crow){
                                $selected = (isset($catid) && $crow['id'] == $catid) ? 'selected' : ''; 
                                echo "
                                  <option value='".$crow['id']."' ".$selected.">".$crow['nom']."</option>
                                ";
                              }

                              $pdo->close();
                            ?>
                          </select>
                        </div>
                      </form>
                    </div>
                    <div class="col-md-6">
                      <form class="form-inline">
                        <div class="form-group">
                          <label>Rechercher: </label>
                          <input type="text" class="form-control input-sm" id="search_product" placeholder="Rechercher un produit" value="<?php echo isset($search) ? $search : ''; ?>">
                          <button type="button" class="btn btn-default btn-sm btn-flat" id="search_button"><i class="fa fa-search"></i></button>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="box-body">
              <table class="table table-bordered">
                <thead>
                  <th>Nom du Produit</th>
                  <th>Description</th>
                  <th>Prix</th>
                  <th>Image</th>
                  <th>Catégorie</th>
                  <th>Date de Création</th>
                  <th>Actions</th>
                </thead>
                <tbody>
                  <?php
                    $conn = $pdo->open();

                    try{
                      $stmt = $conn->prepare("SELECT produits.*, categories.nom AS nom_categorie FROM produits LEFT JOIN categories ON categories.id=produits.categorie_id $where");
                      $stmt->execute();
                      foreach($stmt as $row){
                        $image = (!empty($row['image'])) ? '../images/'.$row['image'] : '../images/noimage.jpg';
                        echo "
                          <tr>
                            <td>".$row['nom']."</td>
                            <td>".$row['description']."</td>
                            <td>".$row['prix']."</td>
                            <td><img src='".$image."' height='30px' width='30px'></td>
                            <td>".$row['nom_categorie']."</td>
                            <td>".$row['cree_le']."</td>
                            <td>
                              <button class='btn btn-success btn-sm edit btn-flat' data-id='".$row['id']."' data-nom='".$row['nom']."' data-description='".$row['description']."' data-prix='".$row['prix']."' data-image='".$row['image']."' data-categorie_id='".$row['categorie_id']."' data-toggle='modal' data-target='#editProduct'><i class='fa fa-edit'></i> Éditer</button>
                              <button class='btn btn-danger btn-sm delete btn-flat' data-id='".$row['id']."' data-toggle='modal' data-target='#deleteProduct'><i class='fa fa-trash'></i> Supprimer</button>
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
<!-- Add New Product Modal -->
<div class="modal fade" id="addNewProduct">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><b>Ajouter un nouveau produit</b></h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" method="POST" action="produits.php" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="nom" class="col-sm-3 control-label">Nom du produit</label>
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
                    <div class="form-group">
                        <label for="prix" class="col-sm-3 control-label">Prix</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="prix" name="prix" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="image" class="col-sm-3 control-label">Image</label>
                        <div class="col-sm-9">
                            <input type="file" class="form-control" id="image" name="image">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="categorie_id" class="col-sm-3 control-label">Catégorie</label>
                        <div class="col-sm-9">
                            <select class="form-control" id="categorie_id" name="categorie_id" required>
                              <?php
                                $conn = $pdo->open();

                                $stmt = $conn->prepare("SELECT * FROM categories");
                                $stmt->execute();

                                foreach($stmt as $crow){
                                  echo "
                                    <option value='".$crow['id']."'>".$crow['nom']."</option>
                                  ";
                                }

                                $pdo->close();
                              ?>
                            </select>
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Fermer</button>
                <button type="submit" class="btn btn-primary" name="add_product">Enregistrer</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Edit Product Modal -->
<div class="modal fade" id="editProduct">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><b>Modifier le produit</b></h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" method="POST" action="produits.php" enctype="multipart/form-data">
                    <input type="hidden" id="edit_id" name="id">
                    <div class="form-group">
                        <label for="edit_nom" class="col-sm-3 control-label">Nom du produit</label>
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
                    <div class="form-group">
                        <label for="edit_prix" class="col-sm-3 control-label">Prix</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="edit_prix" name="prix" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="edit_image" class="col-sm-3 control-label">Image</label>
                        <div class="col-sm-9">
                            <input type="file" class="form-control" id="edit_image" name="image">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="edit_categorie_id" class="col-sm-3 control-label">Catégorie</label>
                        <div class="col-sm-9">
                            <select class="form-control" id="edit_categorie_id" name="categorie_id" required>
                              <?php
                                $conn = $pdo->open();

                                $stmt = $conn->prepare("SELECT * FROM categories");
                                $stmt->execute();

                                foreach($stmt as $crow){
                                  echo "
                                    <option value='".$crow['id']."'>".$crow['nom']."</option>
                                  ";
                                }

                                $pdo->close();
                              ?>
                            </select>
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Fermer</button>
                <button type="submit" class="btn btn-success" name="edit_product">Mettre à jour</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Delete Product Modal -->
<div class="modal fade" id="deleteProduct">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss='modal' aria-label='Close'>
                    <span aria-hidden='true'>&times;</span></button>
                <h4 class='modal-title'><b>Supprimer le produit</b></h4>
            </div>
            <div class='modal-body'>
                <form class='form-horizontal' method='POST' action='produits.php'>
                    <input type='hidden' id='delete_id' name='id'>
                    <div class='text-center'>
                        <p>Êtes-vous sûr de vouloir supprimer ce produit ?</p>
                        <h2 id='delete_nom' class='bold'></h2>
                    </div>
            </div>
            <div class='modal-footer'>
                <button type='button' class='btn btn-default pull-left' data-dismiss='modal'>Fermer</button>
                <button type='submit' class='btn btn-danger' name='delete_product'>Supprimer</button>
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
        var prix = $(this).data('prix');
        var image = $(this).data('image');
        var categorie_id = $(this).data('categorie_id');
        $('#edit_id').val(id);
        $('#edit_nom').val(nom);
        $('#edit_description').val(description);
        $('#edit_prix').val(prix);
        $('#edit_categorie_id').val(categorie_id);
    });

    // Delete button
    $(document).on('click', '.delete', function(){
        var id = $(this).data('id');
        var nom = $(this).data('nom');
        $('#delete_id').val(id);
        $('#delete_nom').text(nom);
    });

    // Category filter
    $('#select_category').change(function(){
        var category = $(this).val();
        if(category == 0){
            window.location = 'produits.php';
        }
        else{
            window.location = 'produits.php?category='+category;
        }
    });

    // Search functionality
    $('#search_button').click(function(){
        var search = $('#search_product').val();
        if(search != ''){
            window.location = 'produits.php?search='+search;
        }
    });
});
</script>
</body>
</html>
