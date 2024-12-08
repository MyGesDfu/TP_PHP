<h1>Bienvenue sur la page d'accueil</h1>

<?php
session_start();
?>

<?php if (isset($_SESSION['user'])): ?>
    <p>Bienvenue,
        <?= htmlspecialchars($_SESSION['user']['firstname']) . ' ' . htmlspecialchars($_SESSION['user']['lastname']); ?> !
    </p>
    <a href="/se-deconnecter">Se déconnecter</a>
<?php else: ?>
    <a href="/se-connecter">Connecte-toi !</a>
    <a href="/s-incrire">Inscris-toi !</a>
<?php endif; ?>