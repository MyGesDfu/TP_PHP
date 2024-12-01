<?php
class SQLite {
    private $db;

    public function __construct($dbname = 'users.db') {
        $this->db = new SQLite3($dbname);
        $this->createTable();
    }

    private function createTable(): void {
        $this->db->exec('
            CREATE TABLE IF NOT EXISTS users (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                firstname TEXT NOT NULL,
                lastname TEXT NOT NULL,
                email TEXT NOT NULL UNIQUE,
                password TEXT NOT NULL
            )
        ');
    }

    public function registerUser(User $user): bool {
        $stmt = $this->db->prepare('
            INSERT INTO users (firstname, lastname, email, password)
            VALUES (:firstname, :lastname, :email, :password)
        ');
        $stmt->bindValue(':firstname', $user->getFirstname(), SQLITE3_TEXT);
        $stmt->bindValue(':lastname', $user->getLastname(), SQLITE3_TEXT);
        $stmt->bindValue(':email', $user->getEmail(), SQLITE3_TEXT);
        $stmt->bindValue(':password', $user->getPassword(), SQLITE3_TEXT);
        return $stmt->execute() !== false;
    }

    public function loginUser(string $email, string $password): ?User {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE email = :email');
        $stmt->bindValue(':email', $email, SQLITE3_TEXT);
        $result = $stmt->execute();
        $userData = $result->fetchArray(SQLITE3_ASSOC);

        if ($userData && password_verify($password, $userData['password'])) {
            return new User($userData['firstname'], $userData['lastname'], $userData['email'], $userData['password']);
        }

        return null;
    }
}
?>
