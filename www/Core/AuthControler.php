<?php
namespace App\Core;
use App\Core\User;

class AuthController
{
    private Object $user;

    public function __construct()
    {
        $this->user = new User();
    }

    public function register()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $firstname = trim($_POST['firstname']);
            $lastname = trim($_POST['lastname']);
            $email = trim($_POST['email']);
            $country = trim($_POST['country']);
            $password = trim($_POST['password']);
            $errors = [];

            if (empty($firstname))
                $errors['firstname'] = "Le nom est requis.";
            if (empty($lastname))
                $errors['lastname'] = "Le prénom est requis.";
            if (!filter_var($email, FILTER_VALIDATE_EMAIL))
                $errors['email'] = "L'email est invalide.";
            if (empty($country))
                $errors['country'] = "Le pays est requis.";
            if (empty($password) || strlen($password) < 6)
                $errors['password'] = "Le mot de passe doit contenir au moins 6 caractères.";

            if (empty($errors) && $this->user->getUserByEmail($email)) {
                $errors['email'] = "Un utilisateur avec cet email existe déjà.";
            }

            if (empty($errors)) {
                $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
                $userId = $this->user->createUser([
                    'firstname' => $firstname,
                    'lastname' => $lastname,
                    'email' => $email,
                    'country' => $country,
                    'password' => $hashedPassword
                ]);

                if ($userId > 0) {
                    header("Location: /se-connecter");
                    exit;
                } else {
                    $errors['general'] = "Erreur lors de l'inscription.";
                }
            }

            $_SESSION['errors'] = $errors;
            $_SESSION['old'] = $_POST;
            header("Location: /register");
            exit;
        }
    }
}