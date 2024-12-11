<h1>Bienvenue sur la page d'accueil</h1>

<?php

?>

<?php if (isset($_SESSION['user'])): ?>
    <p>Bienvenue,
        <?= htmlspecialchars($_SESSION['user']['firstname']) . ' ' . htmlspecialchars($_SESSION['user']['lastname']); ?> !
    </p>
    <a href="/utilisateurs/<?= htmlspecialchars($_SESSION['user']['id']) ?>/modifier">Modifier mes informations</a>
    <a href="/se-deconnecter">Se déconnecter</a>
<?php else: ?>
    <a href="/se-connecter">Connecte-toi !</a>
    <a href="/s-inscrire">Inscris-toi !</a>
    <a href="/oublie-motdepasse">Mot de passe oublié ?</a>
<?php endif; ?>