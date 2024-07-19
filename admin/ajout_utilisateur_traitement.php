<?php
include 'includes/session.php';

if(isset($_POST['nom_utilisateur'])){
    $nom_utilisateur = $_POST['nom_utilisateur'];
    $prenom = $_POST['prenom'];
    $email = $_POST['email'];
    $sexe = $_POST['sexe'];
    $adresse = $_POST['adresse'];
    $jour_naissance = $_POST['jour_naissance'];
    $numero_phone = $_POST['numero_phone'];
    $mot_de_passe = password_hash($_POST['mot_de_passe'], PASSWORD_DEFAULT);

    $conn = $pdo->open();

    try{
        $stmt = $conn->prepare("SELECT COUNT(*) AS numrows FROM utilisateurs WHERE email=:email");
        $stmt->execute(['email'=>$email]);
        $row = $stmt->fetch();
        if($row['numrows'] > 0){
            $_SESSION['error'] = 'Email déjà utilisé';
        }
        else{
            $stmt = $conn->prepare("INSERT INTO utilisateurs (nom_utilisateur, prenom, email, sexe, adresse, jour_naissance, numero_phone, mot_de_passe) VALUES (:nom_utilisateur, :prenom, :email, :sexe, :adresse, :jour_naissance, :numero_phone, :mot_de_passe)");
            $stmt->execute(['nom_utilisateur'=>$nom_utilisateur, 'prenom'=>$prenom, 'email'=>$email, 'sexe'=>$sexe, 'adresse'=>$adresse, 'jour_naissance'=>$jour_naissance, 'numero_phone'=>$numero_phone, 'mot_de_passe'=>$mot_de_passe]);
            $_SESSION['success'] = 'Utilisateur ajouté avec succès';
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
