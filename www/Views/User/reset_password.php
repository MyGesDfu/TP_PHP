<form method="POST">
    <input type="hidden" name="token" value="<?= htmlspecialchars($_GET['token']) ?>">
    <label for="password">Nouveau mot de passe :</label>
    <input type="password" id="password" name="password" required>
    <button type="submit">Réinitialiser</button>
</form>
