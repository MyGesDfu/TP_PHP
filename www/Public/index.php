<?php

spl_autoload_register("myAutoloader");
function myAutoloader(string $class): void
{
    //    App\Core\User
    $class = str_ireplace('App', '..', $class);
    //    ..\Core\User
    $class = str_ireplace('\\', '/', $class) . ".php";
    //    Core/User.php
    if (file_exists($class)) {
        //si le fichier existe : require "../Core/User.php";
        include $class;
    }
}

/*
 *  TP de routing
 *  - Récupérer l'url exemple : http://localhost/user/Add
 *  - Récupérer plus précisement l'URI : /user/Add
 *  - Nettoyer l'URI : exemple minuscules ....
 *  - Faire une relation entre /user/add et controller User et l'acion register
 *      --> Récupérer sous forme de tableau le contenu de routes.yml
 *      --> Récupérer le controller et l'action associé à l'URI
 *  - Appeler de manière dynamique le bon controller et la bonne action, exemple :
 *      --> $controller = new User();
 *      --> $controller->register();
 *
 * (Toujours garder en tête de faire un maximum de vérification avec des affichages d'erreur)
 */

$uri = strtolower($_SERVER["REQUEST_URI"]);
$uri = (strlen($uri) > 1) ? rtrim($uri, "/") : $uri;
$uri = strtok($uri, "?");

if (!file_exists("../routes.yml")) {
    die("Le fichier ../routes.yml n'existe pas");
}
$listOfRoutes = yaml_parse_file("../routes.yml");


$routeFound = false;

foreach ($listOfRoutes as $routePattern => $routeConfig) {
    // Remplacer {id} par (\d+)? pour rendre l'ID optionnel
    $pattern = str_replace(['{id}'], ['(\d+)?'], $routePattern);

    if (preg_match('#^' . $pattern . '$#', $uri, $matches)) {
        // Route correspondante trouvée
        $controller = $routeConfig["controller"];
        $action = $routeConfig["action"];

        // Vérifie si l'ID est présent et l'assigne sinon le met à null
        $id = isset($matches[1]) ? $matches[1] : null;

        // Charge le fichier du contrôleur
        if (!file_exists("../Controllers/" . $controller . ".php")) {
            die("Le fichier controller n'existe pas : ../Controllers/" . $controller . ".php");
        }
        include "../Controllers/" . $controller . ".php";

        $controller = "\\App\\Controllers\\" . $controller;
        if (!class_exists($controller)) {
            die("La classe " . $controller . " n'existe pas");
        }
        $objectController = new $controller();

        // Appelle la méthode dynamique avec l'ID si présent
        if (!method_exists($objectController, $action)) {
            die("La méthode " . $action . " n'existe pas");
        }

        // Appelle la méthode avec ou sans l'ID
        if ($id !== null) {
            $objectController->$action($id);
        } else {
            $objectController->$action();
        }

        $routeFound = true;
        break;
    }
}

// Si aucune route n'est trouvée
if (!$routeFound) {
    die("Page not found : 404");
}
