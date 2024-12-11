<pre>
    <?php var_dump($_POST); ?>
</pre>
<form method="POST" action="/utilisateurs/<?= htmlspecialchars($_SESSION['user']['id']) ?>/modifier">
    <h2>Modifier vos informations</h2>

    <label for="firstname">Prénom:</label>
    <input type="text" id="firstname" name="firstname" value="<?= htmlspecialchars($user['firstname']) ?>" required>
    <label for="lastname">Nom:</label>
    <input type="text" id="lastname" name="lastname" value="<?= htmlspecialchars($user['lastname']) ?>" required>
    <label for="email">Email:</label>
    <input type="email" id="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>

    <label for="country">Pays:</label>
    <input type="text" id="country" name="country" value="<?= htmlspecialchars($user['country']) ?>" required>

    <label for="password">Mot de passe:</label>
    <input type="password" id="password" name="password">
    <small>Laissez vide pour ne pas modifier le mot de passe.</small>

    <button type="submit">Enregistrer les modifications</button>
</form>

<?php if (isset($errors)): ?>
    <ul>
        <?php foreach ($errors as $error): ?>
            <li><?= htmlspecialchars($error) ?></li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>