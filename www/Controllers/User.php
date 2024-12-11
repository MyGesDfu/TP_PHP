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
                $validator->validateRequired('lastname', $lastname, "Le nom est requis.");
                $validator->validateEmail('email', $email);
                $validator->validateRequired('country', $country, "Le pays est requis.");
                $validator->validateRequired('password', $password, "Le mot de passe est requis.");
                $validator->validatePasswordMatch($password, $passwordConfirm);

                if ($this->userModel->getUserByEmail($email)) {
                    $validator->validateRequired('email', $email, "Un utilisateur avec cet email existe déjà.");
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

            $_SESSION['errors'] = $errors;
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

}
