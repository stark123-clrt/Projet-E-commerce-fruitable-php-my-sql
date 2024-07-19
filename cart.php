<?php
session_start();
include 'config.php';
include 'head.php'; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_to_cart'])) {
        $product_id = $_POST['product_id'];
        $quantity = $_POST['quantity'];
        // Ajouter la logique pour ajouter le produit au panier
        $_SESSION['cart'][] = ['product_id' => $product_id, 'quantity' => $quantity];
        header('Location: cart.php');
        exit();
    } elseif (isset($_POST['buy_now'])) {
        $product_id = $_POST['product_id'];
        $quantity = $_POST['quantity'];
        // Ajouter la logique pour acheter le produit directement
        $_SESSION['cart'][] = ['product_id' => $product_id, 'quantity' => $quantity];
        header('Location: checkout.php');
        exit();
    }
}

// Afficher les produits dans le panier
$cart_items = [];
if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        $stmt = $pdo->prepare("SELECT * FROM produits WHERE id = :id");
        $stmt->execute(['id' => $item['product_id']]);
        $product = $stmt->fetch(PDO::FETCH_ASSOC);
        $product['quantity'] = $item['quantity'];
        $cart_items[] = $product;
    }
}
?>

<body>
<?php include 'header.php'; ?>

<div class="container mt-5">
    <h2>Mon Panier</h2>
    <div class="row">
        <?php foreach ($cart_items as $item): ?>
            <div class="col-md-4">
                <div class="card mb-4">
                    <img src="images/<?= htmlspecialchars($item['image']) ?>" class="card-img-top" alt="<?= htmlspecialchars($item['nom']) ?>">
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($item['nom']) ?></h5>
                        <p class="card-text"><?= htmlspecialchars($item['prix']) ?> $</p>
                        <p class="card-text">Quantité: <?= htmlspecialchars($item['quantity']) ?></p>
                        <p class="card-text">Total: <?= htmlspecialchars($item['prix'] * $item['quantity']) ?> $</p>
                        <form action="remove_from_cart.php" method="post">
                            <input type="hidden" name="product_id" value="<?= htmlspecialchars($item['id']) ?>">
                            <button type="submit" name="remove_from_cart" class="btn btn-danger">Retirer du panier</button>
                        </form>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- JavaScript Libraries -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
