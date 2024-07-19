<?php
session_start();
include 'config.php';
include 'auth.php';
include 'head.php'; 

$favorites = [];
$stmt = $pdo->prepare("SELECT p.id, p.nom, p.description, p.prix, p.image 
                       FROM favoris f 
                       JOIN produits p ON f.ida = p.id 
                       WHERE f.idu = :user_id");
$stmt->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
$stmt->execute();
$favorites = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>


    <!-- <style>
        .custom-margin-top {
            padding-top: 100px; 
        }
    </style> -->

<body>
<?php include 'header.php'; ?>

<div class="container custom-margin-top" style=" padding-top: 5rem;">
    <h2>Mes favoris</h2>
    <div class="table-responsive mt-5">
        <table class="table">
            <thead>
              <tr>
                <th scope="col">Produits</th>
                <th scope="col">Nom</th>
                <th scope="col">Prix</th>
                <th scope="col">Actions</th>
              </tr>
            </thead>
            <tbody>
                <?php foreach ($favorites as $product): ?>
                    <tr>
                        <th scope="row">
                            <div class="d-flex align-items-center">
                                <img src="images/<?= htmlspecialchars($product['image']) ?>" class="img-fluid me-5 rounded-circle" style="width: 80px; height: 80px;" alt="<?= htmlspecialchars($product['nom']) ?>">
                            </div>
                        </th>
                        <td>
                            <p class="mb-0 mt-4"><?= htmlspecialchars($product['nom']) ?></p>
                        </td>
                        <td>
                            <p class="mb-0 mt-4"><?= htmlspecialchars($product['prix']) ?> $</p>
                        </td>
                        <td>
                            <button class="btn btn-md rounded-circle bg-light border me-2" onclick="addToCart(<?= htmlspecialchars($product['id']) ?>)">
                                <i class="fa fa-shopping-cart text-primary"></i>
                            </button>
                            <button class="btn btn-md rounded-circle bg-light border" onclick="removeFromFavorites(<?= htmlspecialchars($product['id']) ?>)">
                                <i class="fa fa-times text-danger"></i>
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <button class="btn btn-primary mt-4" onclick="addAllToCart()">Ajouter tous les favoris au panier</button>
    </div>
</div>

<!-- JavaScript Libraries -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function addToCart(productId) {
        $.post('add_to_cart.php', { product_id: productId }, function(response) {
            alert('Produit ajouté au panier');
        });
    }

    function removeFromFavorites(productId) {
        $.post('remove_from_favorites.php', { product_id: productId }, function(response) {
            location.reload();
        });
    }

    function addAllToCart() {
        $.post('add_all_to_cart.php', function(response) {
            alert('Tous les produits ont été ajoutés au panier');
        });
    }
</script>
</body>
</html>
