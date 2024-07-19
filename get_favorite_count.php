<?php
session_start();
include 'config.php';

$response = ['status' => 'error', 'count' => 0];

if (isset($_SESSION['user_id'])) {
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM favoris WHERE idu = :user_id");
    $stmt->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
    $stmt->execute();
    $count = $stmt->fetchColumn();

    $response['status'] = 'success';
    $response['count'] = $count;
}

header('Content-Type: application/json');
echo json_encode($response);
?>
