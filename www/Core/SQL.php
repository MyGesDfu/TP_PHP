<?php

namespace App\Core;

class SQL
{

    private $pdo;

    public function __construct(){
        try{
            $this->pdo = new \PDO("mysql:host=mariadb;dbname=esgi","esgi","esgipwd");
        }catch(\Exception $e){
            die("Erreur SQL :".$e->getMessage());
        }
    }

    public function getOneById(string $table,int $id): array
    {
       $queryPrepared = $this->pdo->prepare("SELECT * FROM ".$table." WHERE id=:id");
       $queryPrepared->execute([
               "id"=>$id
           ]);
       return $queryPrepared->fetch();
    }

    
    public function generalInsert(array $fields, array $values, string $table) 
    {
        /// fonction qui permet d'inserer dans la table en paramètre les valeurs présentent en paramètre
        /// param1 => liste des colonnes (ex: ["firstname", "lastname"...])
        /// param2 => liste des valeurs (ex: ["Théo", "l'asticot"....])
        /// parma3 => nom de la table dans laquelle faire l'insertion
        
        $query = "INSERT INTO " .$table . "(";

        $i = 0;
        foreach ($fields as $value) {
            if ($i > 0) {
                $query .= ', ';
            }
            $query .= $value;

            $i++;
        }
        $query .= ")";

        $prepareString = "(";
        $i = 0;
        foreach ($fields as $value) {
            if ($i > 0) {
                $prepareString .= ', ';
            }
            $prepareString .= '?';
            $i++;
        }

        $prepareString .= ")";

        $query .= " VALUES ";
        $query .= $prepareString;


        $prep = $this->pdo->prepare($query);

        if ($prep->execute($values)) {
            return $this->pdo->lastInsertId();
        }
        return (-1);
    }
}