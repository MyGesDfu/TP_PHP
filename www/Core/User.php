<?php
namespace App\Core;
use App\Core\SQL as S;
class User
{

    public function isLogged(): bool
    {
        return false;
    }

    public function getRoles():array
    {
        return [];
    }

    public function logout():void
    {
        session_destroy();
    }

    public static function register($values):bool
    {
        $timestamp = date("Y-m-d H:i:s");
        $columns = ["firstname","lastname", "email", "country", "password", "createdat", "updatedat"]
        $vals = [
            $values["firstname"],
            $values["lastname"],
            $values["email"],
            $values["country"],
            hash("sha256",$values["password"]),
            $timestamp,
            $timestamp,
        ]

        $sql = new S();
        $sql->generalInsert(
            $columns,
            $vals,
            "USERS"
        )
    }

}