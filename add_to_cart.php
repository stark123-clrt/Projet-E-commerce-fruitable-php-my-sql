<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'User not logged in']);
    exit;
}

$productId = $_POST['product_id'];
$userId = $_SESSION['user_id'];

// Vérifiez si le produit est déjà dans le panier
$stmt = $pdo->prepare("SELECT * FROM cart WHERE product_id = :product_id AND user_id = :user_id");
$stmt->execute(['product_id' => $productId, 'user_id' => $userId]);

if ($stmt->rowCount() == 0) {
    // Ajouter le produit au panier
    $stmt = $pdo->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (:user_id, :product_id, 1)");
    $stmt->execute(['user_id' => $userId, 'product_id' => $productId]);
}

echo json_encode(['status' => 'success']);
