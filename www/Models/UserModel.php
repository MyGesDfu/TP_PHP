<?php

namespace App\Models;

use App\Core\SQL;

class UserModel
{
    private $db;

    public function __construct()
    {
        $this->db = new SQL();
        $this->createTable();  // Créer la table si elle n'existe pas
    }

    // Méthode pour créer la table USERS si elle n'existe pas
    private function createTable(): void
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
        return $queryPrepared->fetch(\PDO::FETCH_ASSOC); // S'assurer de renvoyer un tableau associatif
    }
<<<<<<< Updated upstream
=======

    // Méthode pour récupérer un utilisateur par ID
    public function getUserById(int $id): array|false
    {
        $queryPrepared = $this->db->getPDO()->prepare("SELECT * FROM USERS WHERE id = :id");
        $queryPrepared->execute(['id' => $id]);
        return $queryPrepared->fetch(\PDO::FETCH_ASSOC);
    }

    // Méthode pour mettre à jour les informations d'un utilisateur
    public function updateUser(int $id, string $firstname, string $lastname, string $email, string $country): bool
    {
        $query = "UPDATE USERS SET firstname = :firstname, lastname = :lastname, email = :email, country = :country WHERE id = :id";
        $stmt = $this->db->getPDO()->prepare($query);

        // Exécute la requête avec les données
        $stmt->bindParam(':firstname', $firstname);
        $stmt->bindParam(':lastname', $lastname);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':country', $country);
        $stmt->bindParam(':id', $id);

        if (!$stmt->execute()) {
            error_log(print_r($stmt->errorInfo(), true));
            return false;
        }
        return true;
    }

    // Méthode pour mettre à jour le mot de passe d'un utilisateur par email
    public function updatePasswordByEmail(string $email, string $hashedPassword): bool
    {
        $query = "UPDATE USERS SET password = :password WHERE email = :email";
        $stmt = $this->db->getPDO()->prepare($query);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->bindParam(':email', $email);

        if (!$stmt->execute()) {
            error_log(print_r($stmt->errorInfo(), true));
            return false;
        }
        return true;
    }
>>>>>>> Stashed changes
}
