<?php

spl_autoload_register("myAutoloader");
function myAutoloader(string $class): void
{
    $class = str_ireplace('App', '..', $class);
    $class = str_ireplace('\\', '/', $class) . ".php";
    if (file_exists($class)) {
        include $class;
    }
}

// Démarre la session
session_start();

// Gestion du "Se souvenir de moi"
if (!isset($_SESSION['user']) && isset($_COOKIE['persistent_login_token'])) {
    $userModel = new \App\Models\UserModel();
    $userFromToken = $userModel->getUserByRememberToken($_COOKIE['persistent_login_token']);

    if ($userFromToken) {
        $_SESSION['user'] = $userFromToken;
    } else {
        // Token invalide ou expiré, on le supprime
        setcookie('persistent_login_token', '', time() - 3600, '/');
    }
}

// Nettoyage de l'URI
$uri = strtolower($_SERVER["REQUEST_URI"]);
$uri = (strlen($uri) > 1) ? rtrim($uri, "/") : $uri;
$uri = strtok($uri, "?");

// Vérification de l'existence de routes.yml
if (!file_exists("../routes.yml")) {
    die("Le fichier ../routes.yml n'existe pas");
}

$listOfRoutes = yaml_parse_file("../routes.yml");
$routeFound = false;

// Parcours des routes pour trouver une correspondance
foreach ($listOfRoutes as $routePattern => $routeConfig) {
    // Remplacer {id} par (\d+)? pour rendre l'ID optionnel
    $pattern = str_replace(['{id}'], ['(\d+)?'], $routePattern);

    if (preg_match('#^' . $pattern . '$#', $uri, $matches)) {
        // Route trouvée
        $controller = $routeConfig["controller"];
        $action = $routeConfig["action"];

        // Récupération de l'ID si présent
        $id = isset($matches[1]) ? $matches[1] : null;

        // Vérification du fichier contrôleur
        if (!file_exists("../Controllers/" . $controller . ".php")) {
            die("Le fichier controller n'existe pas : ../Controllers/" . $controller . ".php");
        }
        include "../Controllers/" . $controller . ".php";

        $controller = "\\App\\Controllers\\" . $controller;
        if (!class_exists($controller)) {
            die("La classe " . $controller . " n'existe pas");
        }

        $objectController = new $controller();

        // Vérification de la méthode
        if (!method_exists($objectController, $action)) {
            die("La méthode " . $action . " n'existe pas");
        }

        // Appel de l'action avec ou sans ID
        if ($id !== null) {
            $objectController->$action($id);
        } else {
            $objectController->$action();
        }

        $routeFound = true;
        break;
    }
}

// Si aucune route ne correspond
if (!$routeFound) {
    die("Page not found : 404");
}
