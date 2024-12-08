<?php

namespace App\Core;

use App\Core\SQL as S;

class User
{
    private $db;

    public function __construct()
    {
        $this->db = new SQL();
        $this->createTable();  // Appeler la méthode pour créer la table lors de l'instanciation de l'objet User
    }

    // Méthode pour créer la table USERS si elle n'existe pas
    public function createTable(): void
    {
        $query = "
            CREATE TABLE IF NOT EXISTS USERS (
                id INT AUTO_INCREMENT PRIMARY KEY,
                firstname VARCHAR(255) NOT NULL,
                lastname VARCHAR(255) NOT NULL,
                email VARCHAR(255) NOT NULL UNIQUE,
                country VARCHAR(255) NOT NULL,
                password VARCHAR(255) NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            );
        ";

        // Exécution de la requête pour créer la table
        $this->db->getPDO()->exec($query);
    }

    // Méthode pour créer un nouvel utilisateur
    public function createUser(array $data): int
    {
        $fields = ['firstname', 'lastname', 'email', 'country', 'password'];
        $values = [
            $data['firstname'],
            $data['lastname'],
            $data['email'],
            $data['country'],
            $data['password'],
        ];
        return $this->db->generalInsert($fields, $values, 'USERS');
    }

    // Méthode pour récupérer un utilisateur par email
    public function getUserByEmail(string $email): array|false
    {
        $queryPrepared = $this->db->getPDO()->prepare("SELECT * FROM USERS WHERE email = :email");
        $queryPrepared->execute(['email' => $email]);
        return $queryPrepared->fetch();
    }

    // Vérifie si l'utilisateur est connecté
    public function isLogged(): bool
    {
        return false;
    }

    // Récupère les rôles de l'utilisateur
    public function getRoles(): array
    {
        return [];
    }

    // Déconnexion de l'utilisateur
    public function logout(): void
    {
        session_destroy();
    }
}
