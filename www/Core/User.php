<?php
namespace App\Core;
use App\Core\SQL as S;
class User
{
    private $db;

    public function __construct()
    {
        $this->db = new SQL();
    }

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
    public function getUserByEmail(string $email): array|false
    {
        $queryPrepared = $this->db->getPDO()->prepare("SELECT * FROM users WHERE email = :email");
        $queryPrepared->execute(['email' => $email]);
        return $queryPrepared->fetch();
    }


    public function isLogged(): bool
    {
        return false;
    }

    public function getRoles(): array
    {
        return [];
    }

    public function logout(): void
    {
        session_destroy();
    }


}