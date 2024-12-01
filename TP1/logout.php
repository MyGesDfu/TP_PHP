
<?php
session_start();  // Démarrer ou reprendre la session

// Supprimer toutes les variables de session
$_SESSION = [];

// Détruire la session
session_destroy();

// Rediriger l'utilisateur vers la page d'accueil (index.php)
header('Location: index.php');
exit();