**Projet Gestion d'Utilisateurs en PHP (MVC) :**

Ce projet est une application PHP construite selon le modèle MVC (Modèle-Vue-Contrôleur), permettant de gérer des utilisateurs via des fonctionnalités pour créer, mettre à jour, visualiser  des utilisateurs via un système d'authentification.

Le projet est conteneurisé avec Docker et utilise une base de données MariaDB pour stocker les informations des utilisateurs. Un environnement complet est fourni avec un serveur web Apache, une base de données MariaDB et une interface phpMyAdmin pour une gestion simplifiée.
Prérequis




**Fonctionnalités :**

Inscription des utilisateurs : Ajout de nouveaux utilisateurs avec des validations.
Connexion/Authentification : Authentification sécurisée avec hashage des mots de passe.
Modification de profil : Mise à jour des informations personnelles de l'utilisateur.
Visualisation des données utilisateur : Consultation des informations depuis la base de données.
Réinitialisation du mot de passe: Formulaire permettant de réinitialiser le mot de passe avec un système de




**Structure du Projet (Modèle MVC) :**

Modèle (Model) : Contient la logique métier et gère les interactions avec la base de données. Exemple : UserModel.php.
Vue (View) : Responsable de l'affichage des données. Exemple : edit.php, register.php.
Contrôleur (Controller) : Traite les requêtes, interagit avec le modèle et envoie les données aux vues. Exemple : User.php.




**Prérequis :**

Docker et Docker Compose installés sur votre machine.
Un éditeur de texte ou un IDE comme Visual Studio Code.



**Installation et Exécution :**

1/ Cloner le dépôt :

git clone https://github.com/MyGesDfu/TP_PHP.git

2/ Lancer l'application avec Docker Compose :

  - docker compose build
  - docker compose up -d

Accéder à l'application :

Application web : http://localhost:80
Interface phpMyAdmin : http://localhost:8080



**Architecture Docker**

Le fichier docker-compose.yml configure les services suivants :

Web (Apache) : Héberge l'application PHP.
MariaDB : Base de données pour stocker les informations utilisateur.
phpMyAdmin : Interface graphique pour interagir avec la base de données.

**Base de Données :**

Le projet crée automatiquement une base de données esgi avec une table USERS contenant les champs suivants :

id : Identifiant unique.
firstname : Prénom.
lastname : Nom.
email : Adresse email unique.
country : Pays.
password : Mot de passe hashé.
created_at : Date de création.
reset_token : Token permettant de reset le mot de passe.
token_expiry : Date d'expiration du token

**Auteurs**

Projet développé dans le cadre d'un projet en groupe avec la participation de : Danny Fu, Théa Colinot, Mariam Bouhassoune
