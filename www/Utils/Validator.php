<?php

namespace App\Utils;

class Validator
{
    private array $errors = [];

    // Vérification de champ requis
    public function validateRequired(string $field, $value, string $message): void
    {
        if (empty($value)) {
            $this->errors[$field] = $message;
        }
    }

    // Vérification de l'email
    public function validateEmail(string $field, string $email): void
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->errors[$field] = "Email invalide.";
        }

        // Vérification des domaines interdits
        $blockedDomains = ['tempmail.com', 'disposablemail.com'];
        $emailDomain = substr(strrchr($email, "@"), 1);
        if (in_array($emailDomain, $blockedDomains)) {
            $this->errors[$field] = "Les emails temporaires ou jetables sont interdits.";
        }
    }

    // Vérification du mot de passe
    public function validatePasswordStrength(string $password): void
    {
        // Liste des mots de passe faibles
        $weakPasswords = ['123456', 'password', 'qwerty', 'letmein', 'welcome123'];

        if (in_array($password, $weakPasswords)) {
            $this->errors['password'] = "Ce mot de passe est trop faible. Choisissez-en un autre.";
        }

        // Vérification de la longueur et complexité du mot de passe
        if (strlen($password) < 8) {
            $this->errors['password'] = "Le mot de passe doit contenir au moins 8 caractères.";
        }
        if (!preg_match("/[A-Z]/", $password)) {
            $this->errors['password'] = "Le mot de passe doit contenir au moins une majuscule.";
        }
        if (!preg_match("/[a-z]/", $password)) {
            $this->errors['password'] = "Le mot de passe doit contenir au moins une minuscule.";
        }
        if (!preg_match("/[0-9]/", $password)) {
            $this->errors['password'] = "Le mot de passe doit contenir au moins un chiffre.";
        }
    }

    // Vérification que les mots de passe correspondent
    public function validatePasswordMatch(string $password, string $passwordConfirm): void
    {
        if ($password !== $passwordConfirm) {
            $this->errors['password_confirm'] = "Les mots de passe ne correspondent pas.";
        }
    }

    // Vérification des noms
    public function validateName(string $field, string $name): void
    {
        $bannedWords = ['admin', 'root', 'super', 'fuck'];
        foreach ($bannedWords as $word) {
            if (stripos($name, $word) !== false) {
                $this->errors[$field] = "Le nom contient des termes interdits.";
            }
        }
    }

    // Ajout d'une erreur manuelle
    public function addError(string $field, string $message): void
    {
        $this->errors[$field] = $message;
    }

    // Récupérer les erreurs
    public function getErrors(): array
    {
        return $this->errors;
    }

    // Vérifier si les validations sont réussies
    public function isValid(): bool
    {
        return empty($this->errors);
    }
}
