<?php
session_start();
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $produit_id = $_POST['produit_id'];
    $utilisateur_id = $_SESSION['user_id']; // Assurez-vous que l'utilisateur est connecté et que l'ID utilisateur est stocké dans la session
    $commentaire = $_POST['commentaire'];
    $note = $_POST['note'];

    // Vérifiez si l'utilisateur a déjà commenté ce produit
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM commentaires WHERE produit_id = :produit_id AND utilisateur_id = :utilisateur_id");
    $stmt->bindValue(':produit_id', $produit_id, PDO::PARAM_INT);
    $stmt->bindValue(':utilisateur_id', $utilisateur_id, PDO::PARAM_INT);
    $stmt->execute();
    $comment_exists = $stmt->fetchColumn();

    if ($comment_exists == 0) {
        $stmt = $pdo->prepare("INSERT INTO commentaires (produit_id, utilisateur_id, commentaire, note) VALUES (:produit_id, :utilisateur_id, :commentaire, :note)");
        $stmt->bindValue(':produit_id', $produit_id, PDO::PARAM_INT);
        $stmt->bindValue(':utilisateur_id', $utilisateur_id, PDO::PARAM_INT);
        $stmt->bindValue(':commentaire', $commentaire, PDO::PARAM_STR);
        $stmt->bindValue(':note', $note, PDO::PARAM_INT);
        $stmt->execute();
    } else {
        $_SESSION['comment_error'] = "Vous avez déjà commenté ce produit.";
    }

    header("Location: shop-detail.php?id=" . $produit_id); // Redirigez vers la page de détail du produit
    exit();
}
?>
