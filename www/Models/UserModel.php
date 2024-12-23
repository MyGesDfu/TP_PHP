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
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            reset_token VARCHAR(64) NULL,
            token_expiry DATETIME NULL
        );";

        $this->db->getPDO()->exec($query);

        // Vérifier si persistent_login_token existe déjà
        $checkColumn = $this->db->getPDO()->query("SHOW COLUMNS FROM USERS LIKE 'persistent_login_token'");
        if ($checkColumn->rowCount() === 0) {
            $this->db->getPDO()->exec("ALTER TABLE USERS ADD COLUMN persistent_login_token VARCHAR(255) DEFAULT NULL;");
        }

        // Vérifier si persistent_login_expiry existe déjà
        $checkColumn = $this->db->getPDO()->query("SHOW COLUMNS FROM USERS LIKE 'persistent_login_expiry'");
        if ($checkColumn->rowCount() === 0) {
            $this->db->getPDO()->exec("ALTER TABLE USERS ADD COLUMN persistent_login_expiry DATETIME DEFAULT NULL;");
        }
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

    public function getUserById(int $id): array|false
    {
        $queryPrepared = $this->db->getPDO()->prepare("SELECT * FROM USERS WHERE id = :id");
        $queryPrepared->execute(['id' => $id]);
        return $queryPrepared->fetch();
    }

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

        $stmt->debugDumpParams();

        if (!$stmt->execute()) {
            error_log(print_r($stmt->errorInfo(), true));
            return false;
        }
        return true;
    }

    public function findByEmail($email)
    {
        $query = "SELECT id FROM USERS WHERE email = :email";
        $stmt = $this->db->getPDO()->prepare($query);
        $stmt->execute(['email' => $email]);
        return $stmt->fetch();
    }

    public function storeResetToken($userId, $token)
    {
        $query = "UPDATE USERS SET reset_token = :token, token_expiry = DATE_ADD(NOW(), INTERVAL 1 HOUR) WHERE id = :id";
        $stmt = $this->db->getPDO()->prepare($query);
        $stmt->bindParam(':token', $token);
        $stmt->bindParam(':id', $userId);
        if (!$stmt->execute()) {
            error_log(print_r($stmt->errorInfo(), true));
            return false;
        }
        return true;
    }

    public function findByResetToken($token)
    {
        $query = "SELECT * FROM USERS WHERE reset_token = :token";
        $stmt = $this->db->getPDO()->prepare($query);
        $stmt->execute(['token' => $token]);
        return $stmt->fetch();
    }

    public function isTokenValid($token): bool
    {
        $query = "SELECT id FROM USERS WHERE reset_token = :token AND token_expiry > NOW()";
        $stmt = $this->db->getPDO()->prepare($query);
        $stmt->bindParam(':token', $token);
        if (!$stmt->execute()) {
            error_log(print_r($stmt->errorInfo(), true));
            return false;
        }
        return true;
    }

    public function updatePassword($userId, $hashedPassword)
    {
        $query = "UPDATE USERS SET password = :password WHERE id = :id";
        $stmt = $this->db->getPDO()->prepare($query);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->bindParam(':id', $userId);
        if (!$stmt->execute()) {
            error_log(print_r($stmt->errorInfo(), true));
            return false;
        }
        return true;
    }

    public function clearResetToken($userId): bool
    {
        $query = "UPDATE USERS SET reset_token = NULL, token_expiry = NULL WHERE id = :id";
        $stmt = $this->db->getPDO()->prepare($query);
        $stmt->bindParam(':id', $userId);
        if (!$stmt->execute()) {
            error_log(print_r($stmt->errorInfo(), true));
            return false;
        }
        return true;
    }


    ///

    public function updateRememberToken(int $userId, ?string $token, ?string $expiry): bool
    {
        $query = "UPDATE USERS SET persistent_login_token = :token, persistent_login_expiry = :expiry WHERE id = :id";
        $stmt = $this->db->getPDO()->prepare($query);
        $stmt->bindParam(':token', $token);
        $stmt->bindParam(':expiry', $expiry);
        $stmt->bindParam(':id', $userId);
        return $stmt->execute();
    }

    public function getUserByRememberToken(string $token): array|false
    {
        $query = "SELECT * FROM USERS WHERE persistent_login_token = :token AND persistent_login_expiry > NOW()";
        $stmt = $this->db->getPDO()->prepare($query);
        $stmt->execute(['token' => $token]);
        return $stmt->fetch();
    }
}
