<?php
require_once 'Sqlite.class.php';
require_once 'User.class.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $db = new SQLite();

    // Vérifier les informations de connexion
    $user = $db->loginUser($email, $password);
    if ($user) {
        // Stocker les informations utilisateur dans la session
        $_SESSION['user_email'] = $user->getEmail();
        $_SESSION['user_firstname'] = $user->getFirstname();
        header('Location: index.php');
        exit();
    } else {
        $error_message = "Erreur : Email ou mot de passe incorrect.";
    }
}
?>

<h1>Se connecter</h1>

    <ul>
        <li><a href="register.php">Inscription</a></li>
        <li><a href="index.php">Page d'accueil</a></li>
    </ul>
    
<?php if (isset($error_message)): ?>
    <div style="background-color: red">
        <ul>
            <li><?php echo $error_message; ?></li>
        </ul>
    </div>
<?php endif; ?>

<form action="login.php" method="POST">
    <input type="email" name="email" placeholder="Votre email" required><br>
    <input type="password" name="password" placeholder="Votre mot de passe" required><br>
    <input type="submit" value="Se connecter">
</form>