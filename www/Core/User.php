<?php

namespace App\Core;

use App\Models\UserModel;

class User
{
    private $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();  // Utilise le modèle UserModel
    }

    // Vérifier si un utilisateur est connecté
    public function isLogged(): bool
    {
        return isset($_SESSION['user']);
    }

    // Récupérer l'utilisateur connecté
    public function getLoggedUser(): array|false
    {
        return $_SESSION['user'] ?? false;
    }

    // Récupérer les rôles de l'utilisateur connecté (si applicable)
    public function getRoles(): array
    {
        return $_SESSION['user']['roles'] ?? [];
    }

    // Déconnexion de l'utilisateur
    public function logout(): void
    {
        session_start();
        session_unset();
        session_destroy();
    }
}
