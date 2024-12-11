<?php

namespace App\Utils;

class Validator
{
    private array $errors = [];

    public function validateRequired(string $field, $value, string $message): void
    {
        if (empty($value)) {
            $this->errors[$field] = $message;
        }
    }

    public function validateEmail(string $field, string $email): void
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->errors[$field] = "Email invalide.";
        }
    }

    public function validatePasswordMatch(string $password, string $passwordConfirm): void
    {
        if ($password !== $passwordConfirm) {
            $this->errors['password_confirm'] = "Les mots de passe ne correspondent pas.";
        }
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function isValid(): bool
    {
        return empty($this->errors);
    }
}