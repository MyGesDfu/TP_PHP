<?php
require 'Sqlite.class.php';
require_once 'User.class.php';
//Le code pour s'inscrire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $passwordConfirm = $_POST['passwordConfirm'];

    if ($password === $passwordConfirm) {
        // Initialiser la base de données SQLite
        $db = new SQLite();

        // Inscrire un nouvel utilisateur
        // Créer un nouvel objet User
        $user = new User($firstname, $lastname, $email, $password);

        // Inscrire l'utilisateur dans la base de données
        if ($db->registerUser($user)) {
            echo "Inscription réussie !";
        } else {
            echo "Erreur : L'utilisateur n'a pas pu être inscrit.";
        }
    } else {
        echo "Erreur : Les informations fournies ne sont pas valides.";
    }
}
?>

<h1>S'inscrire</h1>

<ul>
    <li><a href="login.php">Connexion</a></li>
    <li><a href="index.php">Page d'accueil</a></li>
</ul>

<!-- 
<div style="background-color: red">
    <ul>
        <li>Les erreurs</li>
        <li>Les erreurs</li>
    </ul>
</div> -->
<form action="register.php" method="POST">
    <input type="text" name="firstname" placeholder="Votre prénom"><br>
    <input type="text" name="lastname" placeholder="Votre nom"><br>
    <input type="email" name="email" placeholder="Votre email"><br>
    <input type="password" name="password" placeholder="Votre mot de passe"><br>
    <input type="password" name="passwordConfirm" placeholder="Confirmation"><br>
    <input type="submit" value="S'inscrire">
</form>