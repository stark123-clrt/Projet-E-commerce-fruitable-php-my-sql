<?php
include 'includes/session.php';

if(isset($_POST['id'])){
    $id = $_POST['id'];
    $nom_utilisateur = $_POST['nom_utilisateur'];
    $prenom = $_POST['prenom'];
    $email = $_POST['email'];
    $sexe = $_POST['sexe'];
    $adresse = $_POST['adresse'];
    $jour_naissance = $_POST['jour_naissance'];
    $numero_phone = $_POST['numero_phone'];

    $conn = $pdo->open();

    try{
        // Vérifier si l'email existe déjà pour un autre utilisateur
        $stmt = $conn->prepare("SELECT COUNT(*) AS numrows FROM utilisateurs WHERE email=:email AND id != :id");
        $stmt->execute(['email'=>$email, 'id'=>$id]);
        $row = $stmt->fetch();
        if($row['numrows'] > 0){
            $_SESSION['error'] = 'Email déjà utilisé par un autre utilisateur';
        }
        else{
            $stmt = $conn->prepare("UPDATE utilisateurs SET nom_utilisateur=:nom_utilisateur, prenom=:prenom, email=:email, sexe=:sexe, adresse=:adresse, jour_naissance=:jour_naissance, numero_phone=:numero_phone WHERE id=:id");
            $stmt->execute(['nom_utilisateur'=>$nom_utilisateur, 'prenom'=>$prenom, 'email'=>$email, 'sexe'=>$sexe, 'adresse'=>$adresse, 'jour_naissance'=>$jour_naissance, 'numero_phone'=>$numero_phone, 'id'=>$id]);
            $_SESSION['success'] = 'Utilisateur mis à jour avec succès';
        }
    }
    catch(PDOException $e){
        $_SESSION['error'] = $e->getMessage();
    }

    $pdo->close();
}
else{
    $_SESSION['error'] = 'Remplissez le formulaire d\'abord';
}

header('location: utilisateurs.php');
?>
