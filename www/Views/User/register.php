<?php if (!empty($_SESSION['errors'])): ?>
    <ul>
        <?php foreach ($_SESSION['errors'] as $error): ?>
            <li><?= htmlspecialchars($error) ?></li>
        <?php endforeach; ?>
    </ul>
    <?php unset($_SESSION['errors']); ?>
<?php endif; ?>

<form action="/s-inscrire" method="POST">
    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
    <input type="text" name="firstname" placeholder="Votre prénom" required minlength="2"><br>
    <input type="text" name="lastname" placeholder="Votre nom" required minlength="2"><br>
    <input type="email" name="email" placeholder="Votre email" required><br>
    <input type="password" name="password" placeholder="Votre mot de passe" required minlength="6"><br>
    <input type="password" name="passwordConfirm" placeholder="Confirmation" required minlength="6"><br>
    <input type="text" name="country" placeholder="Votre pays" required><br>
    <input type="submit" value="S'inscrire"><br>
</form>
<p>Déjà un compte ? <a href="/se-connecter">Connectez-vous ici</a>.</p>
<p><a href="/">Accueil</a>.</p>