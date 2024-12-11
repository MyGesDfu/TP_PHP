<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Core\View;
use App\Utils\Validator;

class User
{
    private $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();  // Utilisation du modèle UserModel
    }

    private function startSession(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    private function generateCsrfToken(): string
    {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    private function validateCsrfToken(): void
    {
        if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'] ?? '')) {
            die("Token CSRF invalide.");
        }
    }

    public function register(): void
    {
        $this->startSession();
        $this->generateCsrfToken();

        $view = new View("User/register.php", "back.php");

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $this->validateCsrfToken();

            $validator = new Validator();

            if (empty($errors)) {
                $firstname = ucwords(strtolower(trim($_POST['firstname'])));
                $lastname = strtoupper(trim($_POST['lastname']));
                $email = strtolower(trim($_POST['email']));
                $country = ucwords(trim($_POST['country']));
                $password = trim($_POST['password']);
                $passwordConfirm = trim($_POST['passwordConfirm']);
                $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

                $validator->validateRequired('firstname', $firstname, "Le prénom est requis.");
                $validator->validateName('firstname', $firstname);
                $validator->validateRequired('lastname', $lastname, "Le nom est requis.");
                $validator->validateEmail('email', $email);
                $validator->validateRequired('country', $country, "Le pays est requis.");
                $validator->validateRequired('password', $password, "Le mot de passe est requis.");
                $validator->validatePasswordStrength($password);
                $validator->validatePasswordMatch($password, $passwordConfirm);

                if ($this->userModel->getUserByEmail($email)) {
                    $validator->addError('email', "Un utilisateur avec cet email existe déjà.");
                }

                if ($validator->isValid()) {
                    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
                    $userId = $this->userModel->createUser([
                        'firstname' => $firstname,
                        'lastname' => $lastname,
                        'email' => $email,
                        'country' => $country,
                        'password' => $hashedPassword
                    ]);

                    if ($userId > 0) {
                        $_SESSION['success_message'] = "Votre inscription a été réussie. Vous pouvez maintenant vous connecter.";
                        header("Location: /se-connecter");
                        exit;
                    } else {
                        $errors['general'] = "Erreur lors de l'inscription.";
                    }
                }
            }

            $_SESSION['errors'] = $validator->getErrors();
            session_write_close();
            header("Location: /s-inscrire");
            exit;
        }
    }


    public function login(): void
    {
        $this->startSession();
        $this->generateCsrfToken();

        $view = new View("User/login.php", "front.php");

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->validateCsrfToken();
            $email = trim($_POST['email'] ?? '');
            $password = trim($_POST['password'] ?? '');

            $validator = new Validator();

            $validator->validateRequired('email', $email, "L'email est requis.");
            $validator->validateEmail('email', $email);
            $validator->validateRequired('password', $password, "Le mot de passe est requis.");

            if ($validator->isValid()) {
                $user = $this->userModel->getUserByEmail($email);

                if ($user && password_verify($password, $user['password'])) {
                    $_SESSION['user'] = $user;
                    header("Location: /");
                    exit;
                } else {
                    $validator->validateRequired('general', '', "Email ou mot de passe incorrect.");
                }
            }

            $_SESSION['errors'] = $validator->getErrors();
            session_write_close();
            header("Location: /se-connecter");
            exit;
        }
    }

    public function logout(): void
    {
        $this->startSession();
        session_unset();
        session_destroy();
        header("Location: /");
        exit;
    }

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
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'];
            $user = $this->userModel->findByEmail($email);

            if ($user) {
                $token = bin2hex(random_bytes(32));
                $this->userModel->storeResetToken($user, $token);

                $resetLink = "http://localhost:90/reset-password?token=$token";
                //mail($email, "Reset your password", "Click here to reset your password: $resetLink");

                echo "<h3>Un liens permettant de changer votre mot de passe à été envoyer à l'email</h3>";
                echo "<a href='".$resetLink."'>Reset link</a>";
            } else {
                echo "No user found with that email.";
            }
        } else {
            $view = new View("User/forgot_password.php", "front.php");
        }
    }

    public function resetPassword()
    {

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $token = $_POST['token'];
            $newPassword = $_POST['password'];

            $user = $this->userModel->findByResetToken($token);
            if ($user && $this->userModel->isTokenValid($token)) {
                $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);
                $this->userModel->updatePassword($user, $hashedPassword);
                echo "<h3>Le mot de passe a été changer avec succès</h3>";
                echo "<a href='/'>Retourner à l'acceuil</a>";
            } else {
                echo "Token invalide";
            }
        } else {
            new View("User/reset_password.php", "front.php");
        }
    }
}
