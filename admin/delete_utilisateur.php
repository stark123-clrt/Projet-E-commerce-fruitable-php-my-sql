<?php
session_start();
$id = $_GET['id'];

$conn = new mysqli("localhost", "root", "", "ecoblessing");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "DELETE FROM utilisateurs WHERE id=$id";

if ($conn->query($sql) === TRUE) {
    $_SESSION['success'] = "Utilisateur supprimé avec succès";
} else {
    $_SESSION['error'] = "Erreur : " . $sql . "<br>" . $conn->error;
}

$conn->close();
header('location: utilisateurs.php');
exit();
?>
