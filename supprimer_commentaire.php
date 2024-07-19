<?php
session_start();
include 'config.php';

if (isset($_GET['id'])) {
    $comment_id = $_GET['id'];
    $utilisateur_id = $_SESSION['user_id']; // Assurez-vous que l'utilisateur est connecté et que l'ID utilisateur est stocké dans la session

    // Vérifiez si le commentaire appartient à l'utilisateur connecté
    $stmt = $pdo->prepare("SELECT * FROM commentaires WHERE id = :comment_id AND utilisateur_id = :utilisateur_id");
    $stmt->bindValue(':comment_id', $comment_id, PDO::PARAM_INT);
    $stmt->bindValue(':utilisateur_id', $utilisateur_id, PDO::PARAM_INT);
    $stmt->execute();
    $comment = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($comment) {
        // Supprimer le commentaire
        $stmt = $pdo->prepare("DELETE FROM commentaires WHERE id = :comment_id");
        $stmt->bindValue(':comment_id', $comment_id, PDO::PARAM_INT);
        $stmt->execute();
    }
}

header("Location: shop-detail.php?id=" . $comment['produit_id']); // Redirigez vers la page de détail du produit
exit();
?>
