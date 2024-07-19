<?php
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom_utilisateur = $_POST['nom_utilisateur'];
    $prenom = $_POST['prenom'];
    $email = $_POST['email'];
    $sexe = $_POST['sexe'];
    $adresse = $_POST['adresse'];
    $jour_naissance = $_POST['jour_naissance'];
    $numero_phone = $_POST['numero_phone'];
    $mot_de_passe = password_hash($_POST['mot_de_passe'], PASSWORD_DEFAULT); // Hash du mot de passe

    // Vérifier si le nom d'utilisateur existe déjà
    $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE nom_utilisateur = :nom_utilisateur");
    $stmt->bindValue(':nom_utilisateur', $nom_utilisateur);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        echo "Le nom d'utilisateur existe déjà. Veuillez en choisir un autre.";
    } else {
        // Insérer les données dans la table
        $stmt = $pdo->prepare("INSERT INTO utilisateurs (nom_utilisateur, prenom, email, sexe, adresse, jour_naissance, numero_phone, mot_de_passe) VALUES (:nom_utilisateur, :prenom, :email, :sexe, :adresse, :jour_naissance, :numero_phone, :mot_de_passe)");

        $stmt->bindValue(':nom_utilisateur', $nom_utilisateur);
        $stmt->bindValue(':prenom', $prenom);
        $stmt->bindValue(':email', $email);
        $stmt->bindValue(':sexe', $sexe);
        $stmt->bindValue(':adresse', $adresse);
        $stmt->bindValue(':jour_naissance', $jour_naissance);
        $stmt->bindValue(':numero_phone', $numero_phone);
        $stmt->bindValue(':mot_de_passe', $mot_de_passe);

        if ($stmt->execute()) {
            echo "Inscription réussie.";
            header("Location: login.php"); // Rediriger vers la page de connexion après l'inscription réussie
            exit();
        } else {
            echo "Erreur lors de l'inscription.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Inscription</title>
    <link rel="stylesheet" href="path_to_your_css_file.css"> <!-- Remplacez par le chemin vers votre fichier CSS -->
    <style>
        /* Ajoutez vos styles CSS ici */
        .container {
            max-width: 500px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #f9f9f9;
        }

        h2 {
            text-align: center;
        }

        label {
            display: block;
            margin-top: 10px;
        }

        input[type="text"], input[type="email"], input[type="password"], input[type="date"], select {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        button {
            display: block;
            width: 100%;
            padding: 10px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: #218838;
        }

        .error {
            color: red;
            margin-top: 10px;
            text-align: center;
        }

    </style>
</head>
<body>

<div class="container">
    <h2>Inscription</h2>
    <form method="post" action="register.php">
        <label for="nom_utilisateur">Nom d'utilisateur</label>
        <input type="text" id="nom_utilisateur" name="nom_utilisateur" required>

        <label for="prenom">Prénom</label>
        <input type="text" id="prenom" name="prenom" required>

        <label for="email">Email</label>
        <input type="email" id="email" name="email" required>

        <label for="sexe">Sexe</label>
        <select id="sexe" name="sexe" required>
            <option value="M">Masculin</option>
            <option value="F">Féminin</option>
        </select>

        <label for="adresse">Adresse</label>
        <input type="text" id="adresse" name="adresse" required>

        <label for="jour_naissance">Date de naissance</label>
        <input type="date" id="jour_naissance" name="jour_naissance" required>

        <label for="numero_phone">Numéro de téléphone</label>
        <input type="text" id="numero_phone" name="numero_phone" required>

        <label for="mot_de_passe">Mot de passe</label>
        <input type="password" id="mot_de_passe" name="mot_de_passe" required>

        <button type="submit">S'inscrire</button>
    </form>
    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($error)) {
        echo '<p class="error">' . $error . '</p>';
    }
    ?>
</div>

</body>
</html>
