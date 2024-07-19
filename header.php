<?php
session_start();
include 'config.php';

// Initialiser le compteur de favoris
$favorites_count = 0;
if (isset($_SESSION['user_id'])) {
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM favoris WHERE idu = :user_id");
    $stmt->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
    $stmt->execute();
    $favorites_count = $stmt->fetchColumn();
}
?>

<style>
/* Styles de base */
.navbar-nav .nav-item {
    display: flex;
    align-items: center;
    position: relative;
    margin-right: 20px; /* Ajout d'espace entre les éléments */
}

.navbar-nav .nav-item .nav-link {
    display: flex;
    align-items: center;
    padding: 0.5rem 1rem;
    position: relative;
    color: #6c757d;
}

.navbar-nav .nav-item .nav-link .icon-label {
    margin-left: 0.5rem; /* Ajustement de l'espacement entre l'icône et le texte */
}

/* Effet de survol */
.navbar-nav .nav-item .nav-link:hover,
.navbar-nav .nav-item .nav-link.active {
    color: yellow;
}

.navbar-nav .nav-item .nav-link:hover i,
.navbar-nav .nav-item .nav-link.active i {
    color: yellow;
}

.navbar {
    justify-content: flex-end;
}

.nav-item .favorite-count,
.nav-item .cart-count {
    top: -10px;
    right: -10px;
    height: 20px;
    min-width: 20px;
    font-size: 0.8rem;
}

.navbar-brand {
    margin-right: auto;
}

/* Positionnement des badges */
.favorite-count,
.cart-count {
    position: absolute;
    top: -5px;
    right: -10px;
    height: 20px;
    min-width: 20px;
    font-size: 0.8rem;
    background-color: #ffc107; /* Couleur de fond */
    color: #000; /* Couleur du texte */
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}
</style>

<div class="container-fluid fixed-top">
    <div class="container topbar bg-primary d-none d-lg-block">
        <div class="d-flex justify-content-between">
            <div class="top-info ps-2">
                <small class="me-3"><i class="fas fa-map-marker-alt me-2 text-secondary"></i> <a href="#" class="text-white">123 Street, New York</a></small>
                <small class="me-3"><i class="fas fa-envelope me-2 text-secondary"></i><a href="#" class="text-white">Email@Example.com</a></small>
            </div>
            <div class="top-link pe-2">
                <a href="#" class="text-white"><small class="text-white mx-2">Privacy Policy</small>/</a>
                <a href="#" class="text-white"><small class="text-white mx-2">Terms of Use</small>/</a>
                <a href="#" class="text-white"><small class="text-white ms-2">Sales and Refunds</small></a>
            </div>
        </div>
    </div>
    <div class="container px-0">
        <nav class="navbar navbar-light bg-white navbar-expand-xl">
            <a href="index.php" class="navbar-brand"><h1 class="text-primary display-6">Fruitables</h1></a>
            <button class="navbar-toggler py-2 px-3" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                <span class="fa fa-bars text-primary"></span>
            </button>
            <div class="collapse navbar-collapse bg-white" id="navbarCollapse">
                <div class="navbar-nav ms-auto">
                    <a href="shop.php" class="position-relative me-4 my-auto d-flex align-items-center nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'shop.php' ? 'active' : ''; ?>">
                        <i class="fa fa-store fa-2x"></i>
                        <span class="icon-label ms-2">Boutique</span>
                    </a>
                    <a href="favoris.php" class="position-relative me-4 my-auto d-flex align-items-center nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'favoris.php' ? 'active' : ''; ?>">
                        <i class="fa fa-heart fa-2x"></i>
                        <span class="icon-label ms-2">Favoris</span>
                        <?php if ($favorites_count > 0): ?>
                            <span class="favorite-count position-absolute">
                                <?= $favorites_count ?>
                            </span>
                        <?php endif; ?>
                    </a>
                    <a href="cart.php" class="position-relative me-4 my-auto d-flex align-items-center nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'cart.php' ? 'active' : ''; ?>">
                        <i class="fa fa-shopping-bag fa-2x"></i>
                        <span class="icon-label ms-2">Panier</span>
                        <span class="cart-count position-absolute">3</span>
                    </a>
                    <a href="orders.php" class="position-relative me-4 my-auto d-flex align-items-center nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'orders.php' ? 'active' : ''; ?>">
                        <i class="fa fa-clipboard-list fa-2x"></i>
                        <span class="icon-label ms-2">Commande</span>
                    </a>
                    <a href="account.php" class="my-auto d-flex align-items-center nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'account.php' ? 'active' : ''; ?>">
                        <i class="fas fa-user fa-2x"></i>
                        <?php if (isset($_SESSION['nom_utilisateur']) && isset($_SESSION['prenom'])): ?>
                            <span class="ms-2"><?php echo htmlspecialchars($_SESSION['nom_utilisateur']) . ' ' . htmlspecialchars($_SESSION['prenom']); ?></span>
                        <?php endif; ?>
                    </a>
                    <?php if (!isset($_SESSION['nom_utilisateur']) && !isset($_SESSION['prenom'])): ?>
                        <a href="login.php" class="btn btn-primary ms-3">Se connecter</a>
                    <?php endif; ?>
                </div>
            </div>
        </nav>
    </div>
</div>
