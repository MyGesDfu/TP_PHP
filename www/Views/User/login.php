<h2> Se connecter </h2>

<?php if (!empty($_SESSION['errors'])): ?>
    <ul>
        <?php foreach ($_SESSION['errors'] as $error): ?>
            <li><?= htmlspecialchars($error) ?></li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<form action="/se-connecter" method="POST">
    <input type="email" name="email" placeholder="Votre email" required><br>
    <input type="password" name="password" placeholder="Votre mot de passe" required><br>
    <input type="submit" value="Se connecter">
</form>
<p>Pas encore de compte ? <a href="/s-inscrire">Inscrivez-vous ici</a>.</p>