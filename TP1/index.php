z<?php
session_start();  // Démarrer ou reprendre une session
?>

<h1>Page d'accueil</h1>

<?php if (isset($_SESSION['user_email'])): ?>
    <!-- Si l'utilisateur est connecté, afficher le message de bienvenue et le bouton de déconnexion -->
    <h2>Bienvenue, <?php echo htmlspecialchars($_SESSION['user_firstname']); ?> !</h2>
    <ul>
        <li><a href="logout.php">Déconnexion</a></li>
    </ul>
<?php else: ?>
    <!-- Si l'utilisateur n'est pas connecté, afficher les options de connexion et d'inscription -->
    <ul>
        <li><a href="login.php">Connexion</a></li>
        <li><a href="register.php">Inscription</a></li>
    </ul>
<?php endif; ?>
