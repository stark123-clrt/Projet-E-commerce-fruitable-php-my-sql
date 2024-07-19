<?php 
session_start();
include 'config.php'; 
include 'head.php'; 
?>

<body>
<?php include 'header.php'; ?>

<!-- Modal Search Start -->
<div class="modal fade" id="searchModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content rounded-0">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Search by keyword</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body d-flex align-items-center">
                <div class="input-group w-75 mx-auto d-flex">
                    <input type="search" class="form-control p-3" placeholder="keywords" aria-describedby="search-icon-1">
                    <span id="search-icon-1" class="input-group-text p-3"><i class="fa fa-search"></i></span>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal Search End -->

<!-- Single Page Header start -->
<div class="container-fluid page-header py-5">
    <h1 class="text-center text-white display-6">Shop Detail</h1>
    <ol class="breadcrumb justify-content-center mb-0">
        <li class="breadcrumb-item"><a href="#">Home</a></li>
        <li class="breadcrumb-item"><a href="#">Pages</a></li>
        <li class="breadcrumb-item active text-white">Shop Detail</li>
    </ol>
</div>
<!-- Single Page Header End -->

<!-- Single Product Start -->
<div class="container-fluid py-5 mt-5">
    <div class="container py-5">
        <?php
        if (isset($_GET['id'])) {
            $stmt = $pdo->prepare('SELECT p.*, c.nom AS categorie_nom FROM produits p JOIN categories c ON p.categorie_id = c.id WHERE p.id = :id');
            $stmt->execute(['id' => $_GET['id']]);
            $produit = $stmt->fetch(PDO::FETCH_ASSOC);

            // Calcul de la moyenne des évaluations
            $stmt_avg = $pdo->prepare('SELECT AVG(note) as average_rating FROM commentaires WHERE produit_id = :produit_id');
            $stmt_avg->execute(['produit_id' => $produit['id']]);
            $avg_rating = $stmt_avg->fetchColumn();

            // Vérifier si le produit est déjà dans les favoris
            $isFavorited = false;
            if (isset($_SESSION['user_id'])) {
                $favStmt = $pdo->prepare("SELECT * FROM favoris WHERE idu = :user_id AND ida = :product_id");
                $favStmt->execute(['user_id' => $_SESSION['user_id'], 'product_id' => $produit['id']]);
                $isFavorited = $favStmt->rowCount() > 0;
            }

            if ($produit) {
                echo '
                <div class="row g-4 mb-5">
                    <div class="col-lg-6">
                        <div class="border rounded">
                            <img src="images/' . htmlspecialchars($produit['image']) . '" class="img-fluid rounded" alt="Image">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <h4 class="fw-bold mb-3">' . htmlspecialchars($produit['nom']) . '</h4>
                        <p class="mb-3">Category: ' . htmlspecialchars($produit['categorie_nom']) . '</p>
                        <h5 class="fw-bold mb-3">' . htmlspecialchars($produit['prix']) . ' $</h5>
                        <div class="d-flex mb-4">';
                            for ($i = 1; $i <= 5; $i++) {
                                echo '<i class="fa fa-star ' . ($i <= round($avg_rating) ? 'text-secondary' : 'text-muted') . '"></i>';
                            }
                echo    '</div>
                        <p class="mb-4">' . htmlspecialchars($produit['description']) . '</p>
                        <a href="#" class="btn border border-secondary rounded-pill px-4 py-2 mb-4 text-primary favorite-icon" data-product-id="' . $produit['id'] . '">
                            <i class="fa fa-heart' . ($isFavorited ? ' favorited' : '') . '"></i> Add to favorites
                        </a>
                        <a href="add_to_cart.php?id=' . $produit['id'] . '" class="btn border border-secondary rounded-pill px-4 py-2 mb-4 text-primary">
                            <i class="fa fa-shopping-cart me-2 text-primary"></i> Add to cart
                        </a>
                        <a href="buy_now.php?id=' . $produit['id'] . '" class="btn border border-secondary rounded-pill px-4 py-2 mb-4 text-primary">
                            <i class="fa fa-money-bill me-2 text-primary"></i> Buy now
                        </a>
                    </div>
                </div>';
            } else {
                echo '<p>Produit non trouvé.</p>';
            }
        } else {
            echo '<p>Aucun produit sélectionné.</p>';
        }
        ?>

        <!-- Section des commentaires -->
        <div class="row g-4 mb-5">
            <div class="col-lg-8">
                <h4 class="fw-bold mb-4">Avis</h4>
                <div style="max-height: 400px; overflow-y: auto;">
                <?php
                if (isset($produit)) {
                    $stmt = $pdo->prepare('SELECT c.id, c.utilisateur_id, c.commentaire, c.note, u.nom_utilisateur, c.cree_le FROM commentaires c JOIN utilisateurs u ON c.utilisateur_id = u.id WHERE c.produit_id = :produit_id ORDER BY c.cree_le DESC');
                    $stmt->execute(['produit_id' => $produit['id']]);
                    $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    foreach ($comments as $comment) {
                        echo '
                        <div class="d-flex mb-3">
                            <img src="img/avatar.jpg" class="img-fluid rounded-circle p-3" style="width: 50px; height: 50px;" alt="">
                            <div class="ms-3">
                                <div class="d-flex justify-content-between">
                                    <h6 class="mb-0">' . htmlspecialchars($comment['nom_utilisateur']) . '</h6>
                                    <small class="text-muted">' . date('d M Y', strtotime($comment['cree_le'])) . '</small>
                                </div>
                                <div class="d-flex mb-2">';
                        for ($i = 1; $i <= 5; $i++) {
                            echo '<i class="fa fa-star ' . ($i <= $comment['note'] ? 'text-secondary' : 'text-muted') . '"></i>';
                        }
                        if ($comment['utilisateur_id'] == $_SESSION['user_id']) {
                            echo '<a href="supprimer_commentaire.php?id=' . $comment['id'] . '" class="btn btn-sm btn-danger ms-3">Supprimer</a>';
                        }
                        echo '</div>
                                <p>' . htmlspecialchars($comment['commentaire']) . '</p>
                            </div>
                        </div>';
                    }
                }
                ?>
                </div>
            </div>
            <div class="col-lg-4">
                <form action="ajouter_commentaire.php" method="post">
                    <h4 class="mb-4 fw-bold">Laisser un commentaire</h4>
                    <input type="hidden" name="produit_id" value="<?php echo $produit['id']; ?>">
                    <div class="mb-3">
                        <label for="commentaire" class="form-label">Votre commentaire</label>
                        <textarea class="form-control" id="commentaire" name="commentaire" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="note" class="form-label">Votre note</label>
                        <select class="form-select" id="note" name="note" required>
                            <option value="5">5 étoiles</option>
                            <option value="4">4 étoiles</option>
                            <option value="3">3 étoiles</option>
                            <option value="2">2 étoiles</option>
                            <option value="1">1 étoile</option>
                        </select>
                    </div>
                    <?php
                    if (isset($_SESSION['comment_error'])) {
                        echo '<div class="alert alert-danger">' . $_SESSION['comment_error'] . '</div>';
                        unset($_SESSION['comment_error']);
                    }
                    ?>
                    <button type="submit" class="btn border border-secondary text-primary rounded-pill px-4 py-3">Envoyer le commentaire</button>
                </form>
            </div>
        </div>

        <h1 class="fw-bold mb-0">Produits associés</h1>
        <div class="vesitable">
            <div class="owl-carousel vegetable-carousel justify-content-center">
                <?php
                if (isset($produit)) {
                    $stmt = $pdo->prepare('SELECT * FROM produits WHERE categorie_id = :categorie_id AND id != :id');
                    $stmt->execute(['categorie_id' => $produit['categorie_id'], 'id' => $produit['id']]);
                    while ($related = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        echo '
                        <div class="border border-primary rounded position-relative vesitable-item">
                            <div class="vesitable-img">
                                <img src="images/' . htmlspecialchars($related['image']) . '" class="img-fluid rounded" alt="Image">
                            </div>
                            <div class="text-white bg-primary px-3 py-1 rounded position-absolute" style="top: 10px; right: 10px;">' . htmlspecialchars($related['categorie_nom']) . '</div>
                            <div class="p-4 pb-0 rounded-bottom">
                                <h4>' . htmlspecialchars($related['nom']) . '</h4>
                                <p>' . htmlspecialchars($related['description']) . '</p>
                                <div class="d-flex justify-content-between flex-lg-wrap">
                                    <p class="text-dark fs-5 fw-bold">$' . htmlspecialchars($related['prix']) . '</p>
                                    <a href="shop-detail.php?id=' . htmlspecialchars($related['id']) . '" class="btn border border-secondary rounded-pill px-3 py-1 mb-4 text-primary"><i class="fa fa-shopping-bag me-2 text-primary"></i> Voir plus</a>
                                </div>
                            </div>
                        </div>';
                    }
                }
                ?>
            </div>
        </div>
    </div>
</div>
<!-- Single Product End -->

<!-- Footer Start -->
<div class="container-fluid bg-dark text-white-50 footer pt-5 mt-5">
    <div class="container py-5">
        <div class="pb-4 mb-4" style="border-bottom: 1px solid rgba(226, 175, 24, 0.5) ;">
            <div class="row g-4">
                <div class="col-lg-3">
                    <a href="#">
                        <h1 class="text-primary mb-0">Fruitables</h1>
                        <p class="text-secondary mb-0">Fresh products</p>
                    </a>
                </div>
                <div class="col-lg-6">
                    <div class="position-relative mx-auto">
                        <input class="form-control border-0 w-100 py-3 px-4 rounded-pill" type="number" placeholder="Your Email">
                        <button type="submit" class="btn btn-primary border-0 border-secondary py-3 px-4 position-absolute rounded-pill text-white" style="top: 0; right: 0;">Subscribe Now</button>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="d-flex justify-content-end pt-3">
                        <a class="btn  btn-outline-secondary me-2 btn-md-square rounded-circle" href=""><i class="fab fa-twitter"></i></a>
                        <a class="btn btn-outline-secondary me-2 btn-md-square rounded-circle" href=""><i class="fab fa-facebook-f"></i></a>
                        <a class="btn btn-outline-secondary me-2 btn-md-square rounded-circle" href=""><i class="fab fa-youtube"></i></a>
                        <a class="btn btn-outline-secondary btn-md-square rounded-circle" href=""><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
            </div>
        </div>
        <div class="row g-5">
            <div class="col-lg-3 col-md-6">
                <div class="footer-item">
                    <h4 class="text-light mb-3">Why People Like us!</h4>
                    <p class="mb-4">typesetting, remaining essentially unchanged. It was popularised in the 1960s with the like Aldus PageMaker including of Lorem Ipsum.</p>
                    <a href="" class="btn border-secondary py-2 px-4 rounded-pill text-primary">Read More</a>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="d-flex flex-column text-start footer-item">
                    <h4 class="text-light mb-3">Shop Info</h4>
                    <a class="btn-link" href="">About Us</a>
                    <a class="btn-link" href="">Contact Us</a>
                    <a class="btn-link" href="">Privacy Policy</a>
                    <a class="btn-link" href="">Terms & Condition</a>
                    <a class="btn-link" href="">Return Policy</a>
                    <a class="btn-link" href="">FAQs & Help</a>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="d-flex flex-column text-start footer-item">
                    <h4 class="text-light mb-3">Account</h4>
                    <a class="btn-link" href="">My Account</a>
                    <a class="btn-link" href="">Shop details</a>
                    <a class="btn-link" href="">Shopping Cart</a>
                    <a class="btn-link" href="">Wishlist</a>
                    <a class="btn-link" href="">Order History</a>
                    <a class="btn-link" href="">International Orders</a>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="footer-item">
                    <h4 class="text-light mb-3">Contact</h4>
                    <p>Address: 1429 Netus Rd, NY 48247</p>
                    <p>Email: Example@gmail.com</p>
                    <p>Phone: +0123 4567 8910</p>
                    <p>Payment Accepted</p>
                    <img src="img/payment.png" class="img-fluid" alt="">
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Footer End -->

<!-- Copyright Start -->
<div class="container-fluid copyright bg-dark py-4">
    <div class="container">
        <div class="row">
            <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                <span class="text-light"><a href="#"><i class="fas fa-copyright text-light me-2"></i>Your Site Name</a>, All right reserved.</span>
            </div>
            <div class="col-md-6 my-auto text-center text-md-end text-white">
                Designed By <a class="border-bottom" href="https://htmlcodex.com">HTML Codex</a> Distributed By <a class="border-bottom" href="https://themewagon.com">ThemeWagon</a>
            </div>
        </div>
    </div>
</div>
<!-- Copyright End -->

<!-- Back to Top -->
<a href="#" class="btn btn-primary border-3 border-primary rounded-circle back-to-top"><i class="fa fa-arrow-up"></i></a>

<!-- JavaScript Libraries -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="lib/easing/easing.min.js"></script>
<script src="lib/waypoints/waypoints.min.js"></script>
<script src="lib/lightbox/js/lightbox.min.js"></script>
<script src="lib/owlcarousel/owl.carousel.min.js"></script>

<!-- Template Javascript -->
<script src="js/main.js"></script>

<script>
$(document).ready(function() {
    $('.favorite-icon').on('click', function() {
        var productId = $(this).data('product-id');
        var $icon = $(this).find('i');
        $.ajax({
            url: 'toggle_favorite.php',
            method: 'POST',
            data: { product_id: productId },
            success: function(response) {
                if (response.status === 'added') {
                    $icon.addClass('favorited');
                    showNotification('Produit ajouté aux favoris');
                } else if (response.status === 'removed') {
                    $icon.removeClass('favorited');
                    showNotification('Produit retiré des favoris');
                } else {
                    showNotification('Erreur', 'error');
                }
                updateFavoriteCount(); // Met à jour le compteur de favoris
            }
        });
    });

    function updateFavoriteCount() {
        $.ajax({
            url: 'get_favorite_count.php',
            method: 'GET',
            success: function(response) {
                if (response.status === 'success') {
                    $('.favorite-count').text(response.count);
                }
            }
        });
    }
});

function showNotification(message, type = 'success') {
    var $notification = $('.notification');
    $notification.text(message).addClass(type).fadeIn();
    setTimeout(function() {
        $notification.fadeOut().removeClass(type);
    }, 3000);
}
</script>

<div class="notification"></div>

</body>
</html>
