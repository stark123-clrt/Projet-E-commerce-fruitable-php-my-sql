<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'User not logged in']);
    exit;
}

$productId = $_POST['product_id'];
$userId = $_SESSION['user_id'];

// Enlever le produit des favoris
$stmt = $pdo->prepare("DELETE FROM favoris WHERE ida = :product_id AND idu = :user_id");
$stmt->execute(['product_id' => $productId, 'user_id' => $userId]);

echo json_encode(['status' => 'success']);
