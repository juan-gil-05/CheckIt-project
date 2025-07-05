<?php

namespace App\Security;

use App\Entity\User;
use App\Repository\UserRepository;

class UserValidator
{
    public function singUpValidate(User $userHydrated): array
    {
        $errors = [];
        $user = $userHydrated;
        $userRepository = new UserRepository;
        // Expresión regular pour la verification du mot de passe sécurisé
        // Regex to verify the set password parameters
        $regex = '/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[!@#$%^&*(),.?":{}|<>]).{12,}$/';

        if (empty($user->getNickname())) {
            $errors['nicknameEmpty'] = 'Le champ nickname ne doit pas être vide';
        }

        if (empty($user->getEmail())) {
            $errors['emailEmpty'] = "Le champ mail ne doit pas être vide";
        } elseif (!filter_var($user->getEmail(), FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = "Le mail n\'est pas valide";
        } elseif ($userRepository->findOneByEmail($user->getEmail())) {
            $errors['emailUsed'] = "Le e-mail est déjà utilisé";
        }

        if (empty($user->getPassword())) {
            $errors['passwordEmpty'] = "Le champ mot de passe ne doit pas être vide";
        } elseif (strlen($_POST['password']) < 12) {
            $errors['passwordLen'] = "Le mot de passe doit comporter au moins 12 caractères";
        } elseif (! preg_match($regex, $_POST['password'])) {
            $errors['passwordInfo'] = "Votre mot de passe doit contenir :
	                                Une lettre majuscule et une lettre minuscule,
	                                un chiffre et
	                                un caractère spécial";
        }

        return $errors;
    }

    public function logInValidate(string $email): array
    {
        $errors = [];

        if (empty($email)) {
            $errors['emailEmpty'] = "Vous devez mettre une adresse e-mail";
        }

        return $errors;
    }

    public function passwordVerify(string $password, User $user)
    {
        if ($user && password_verify($password, $user->getPassword())) {
            return true;
        } else {
            return false;
        }
    }
}
