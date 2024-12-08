<?php

namespace App\Core;

class SQL
{
    private $pdo;

    public function __construct()
    {
        try {
            $this->pdo = new \PDO("mysql:host=mariadb;dbname=esgi", "esgi", "esgipwd");
            $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        } catch (\Exception $e) {
            die("Erreur SQL : " . $e->getMessage());
        }
    }

    // Récupérer un enregistrement par ID
    public function getOneById(string $table, int $id): array
    {
        $queryPrepared = $this->pdo->prepare("SELECT * FROM " . $table . " WHERE id=:id");
        $queryPrepared->execute(["id" => $id]);
        return $queryPrepared->fetch();
    }

    // Récupérer une instance PDO
    public function getPDO(): \PDO
    {
        return $this->pdo;
    }

    // Insérer des données dans une table générique
    public function generalInsert(array $fields, array $values, string $table)
    {
        $query = "INSERT INTO " . $table . "(" . implode(", ", $fields) . ") VALUES (" . implode(", ", array_fill(0, count($fields), '?')) . ")";
        $stmt = $this->pdo->prepare($query);

        if ($stmt->execute($values)) {
            return $this->pdo->lastInsertId();
        }
        return -1;
    }
}
