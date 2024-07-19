<?php
session_start();
include 'config.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'User not logged in']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_SESSION['user_id'];
    $productId = $_POST['product_id'];

    // Vérifiez si le produit est déjà dans les favoris
    $stmt = $pdo->prepare("SELECT * FROM favoris WHERE idu = :user_id AND ida = :product_id");
    $stmt->execute(['user_id' => $userId, 'product_id' => $productId]);

    if ($stmt->rowCount() > 0) {
        // Le produit est déjà dans les favoris, donc nous le supprimons
        $stmt = $pdo->prepare("DELETE FROM favoris WHERE idu = :user_id AND ida = :product_id");
        $stmt->execute(['user_id' => $userId, 'product_id' => $productId]);
        echo json_encode(['status' => 'removed']);
    } else {
        // Le produit n'est pas dans les favoris, donc nous l'ajoutons
        $stmt = $pdo->prepare("INSERT INTO favoris (idu, ida) VALUES (:user_id, :product_id)");
        $stmt->execute(['user_id' => $userId, 'product_id' => $productId]);
        echo json_encode(['status' => 'added']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}
?>
