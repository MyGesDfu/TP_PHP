<?php
namespace App\Controllers;
use App\Core\View;
class Home
{

    public function index(): void
    {
        $view = new View("home.php", "front.php");
        //echo $view;
    }

}