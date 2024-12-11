<h2> Se connecter </h2>

<?php if (!empty($_SESSION['errors'])): ?>
    <ul>
        <?php foreach ($_SESSION['errors'] as $error): ?>
            <li><?= htmlspecialchars($error) ?></li>
        <?php endforeach; ?>
    </ul>
    <?php unset($_SESSION['errors']); ?>
<?php endif; ?>
<?php
if (isset($_SESSION['success_message'])) {
    echo '<div class="alert alert-success">' . $_SESSION['success_message'] . '</div>';
    unset($_SESSION['success_message']);
}
?>

<form method="post" action="/se-connecter">
    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
    <label>Email :</label>
    <input type="email" name="email" required>
    <label>Mot de passe :</label>
    <input type="password" name="password" required>

    <label>
        <input type="checkbox" name="remember" value="1"> Se souvenir de moi
    </label>

    <button type="submit">Se connecter</button>
</form>

<p>Pas encore de compte ? <a href="/s-inscrire">Inscrivez-vous ici</a>.</p>
<p><a href="/">Accueil</a>.</p>