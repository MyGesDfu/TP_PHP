<?php

namespace App\Core;

class View
{
    private string $view;
    private string $template;
    private array $data = [];

    public function __construct(string $view, string $template = "front.php")
    {
        $this->view = "../Views/" . $view;
        $this->template = "../Views/" . $template;
    }

    // Méthode pour ajouter des données à la vue
    public function addData(string $key, $value): void
    {
        $this->data[$key] = $value;
    }

    // Conversion de l'objet en chaîne pour afficher un message
    public function __toString()
    {
        return "Nous sommes sur le template " . $this->template . " dans lequel sera inclus la vue " . $this->view;
    }

    // Rendu de la vue avec les données
    public function render()
    {
        extract($this->data); // Extrait les variables du tableau $data
        include $this->template;
    }

    // Destructeur pour inclure le template
    public function __destruct()
    {
        $this->render(); // Affiche le contenu du template
    }
}
