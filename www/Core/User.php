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

    public function register($values):bool
    {
        $columns = ["firstname","lastname", "email", "country", "password"]
        $vals = [
            values["firstname"],
            values["lastname"],
            values["email"],
            values["country"],
            hash("sha256",values["password"])
        ]

        $sql = new S();
        $sql->generalInsert(
            $columns,
            $values,
            "USERS"
        )
    }

}