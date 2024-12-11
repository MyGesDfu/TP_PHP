<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Core\View;

class User
{
    private $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();  // Utilisation du modèle UserModel
    }

    public function register(): void
    {
        $view = new View("User/register.php", "back.php");
        session_start();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $firstname = ucwords(strtolower(trim($_POST['firstname'])));
            $lastname = strtoupper(trim($_POST['lastname']));
            $email = strtolower(trim($_POST['email']));
            $country = ucwords(trim($_POST['country']));
            $password = trim($_POST['password']);
            $passwordConfirm = trim($_POST['passwordConfirm']);
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
            if ($password !== $passwordConfirm)
                $errors['password_confirm'] = "Les mots de passe ne correspondent pas.";
            if (empty($errors) && $this->userModel->getUserByEmail($email)) {
                $errors['email'] = "Un utilisateur avec cet email existe déjà.";
            }

            if (empty($errors)) {
                $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
                $userId = $this->userModel->createUser([
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
            header("Location: /s-inscrire");
            exit;
        }
    }

    public function login(): void
    {
        session_start();
        $view = new View("User/login.php", "front.php");

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email']);
            $password = trim($_POST['password']);
            $errors = [];

            if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL))
                $errors['email'] = "L'email est invalide.";
            if (empty($password))
                $errors['password'] = "Le mot de passe est requis.";

            if (empty($errors)) {
                $user = $this->userModel->getUserByEmail($email);

                if ($user && password_verify($password, $user['password'])) {
                    $_SESSION['user'] = $user;
                    header("Location: /");
                    exit;
                } else {
                    $errors['general'] = "Email ou mot de passe incorrect.";
                }
            }

            $_SESSION['errors'] = $errors;
            $_SESSION['old'] = $_POST;
            header("Location: /se-connecter");
            exit;
        }
    }

    public function logout(): void
    {
        session_start();
        session_unset();
        session_destroy();
        header("Location: /");
        exit;
    }
<<<<<<< Updated upstream
=======

    // Affiche le formulaire de modification de profil
    public function edit(int $id): void
    {

        $this->startSession();
        if (!isset($_SESSION['user']['id'])) {
            header('Location: /login');
            exit;
        }
        $user = $this->userModel->getUserById($id);

        // Afficher la vue du formulaire avec les données de l'utilisateur
        if ($user) {
            // Charger la vue de modification avec les données de l'utilisateur
            $view = new View("User/edit.php", "back.php");
            $view->addData('user', $user);
        } else {
            // L'utilisateur n'a pas été trouvé
            $_SESSION['errors']['general'] = "Utilisateur non trouvé.";
            header("Location: /");
            exit;
        }
    }

    // Met à jour les informations de l'utilisateur
    public function update(int $id): void
    {
        $this->startSession();

        // Vérifier si le formulaire a été soumis
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Validation des données
            $validator = new Validator();

            $firstname = $_POST['firstname'] ?? '';
            $lastname = $_POST['lastname'] ?? '';
            $email = $_POST['email'] ?? '';
            $country = $_POST['country'] ?? '';


            $validator->validateRequired('firstname', $firstname, 'Le prénom est requis.');
            $validator->validateRequired('lastname', $lastname, 'Le nom est requis.');
            $validator->validateEmail('email', $email);
            $validator->validateRequired('email', $email, 'L\'email est requis.');
            $validator->validateRequired('country', $country, 'Le pays est requis.');

            if ($validator->isValid()) {
                // Mettre à jour l'utilisateur dans la base de données
                $updated = $this->userModel->updateUser($id, $firstname, $lastname, $email, $country);

                if ($updated) {
                    $_SESSION['success_message'] = "Vos informations ont été mises à jour avec succès.";
                    header("Location: /");
                    exit;
                } else {
                    $errors['general'] = 'Erreur lors de la mise à jour.';
                }
            } else {
                $_SESSION['errors'] = $validator->getErrors();
                header("Location: /utilisateurs/$id/modifier");
                exit;
            }
            
        }
    }
    public function forgotPassword()
{
    require_once 'views/auth/forgot_password.php';
}

public function sendResetLink()
{
    $email = $_POST['email'];

    // Vérifiez si l'email existe
    $user = User::getUserByEmail($email);
    if (!$user) {
        echo "Aucun compte associé à cet email.";
        return;
    }

    // Génération d'un token unique
    $token = bin2hex(random_bytes(32));
    $expiresAt = date('Y-m-d H:i:s', strtotime('+1 hour'));

    // Enregistrer le token dans la base de données
    User::createResetToken($email, $token, $expiresAt);

    // Envoi de l'email
    $resetLink = "http://yourwebsite.com/auth/resetPassword?token=" . $token;
    mail($email, "Réinitialisation de votre mot de passe", "Cliquez ici pour réinitialiser : $resetLink");

    echo "Un lien de réinitialisation a été envoyé à votre email.";
}


>>>>>>> Stashed changes
}
