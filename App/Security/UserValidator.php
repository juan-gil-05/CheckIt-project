<?php

namespace App\Security;

use App\Entity\User;
use App\Repository\UserRepository;

class UserValidator
{
    // Fonction de validation du formulaire pour la création d'un compte utilisateur
    public function singUpValidate(User $userHydrated): array
    {
        // Tableau d'erreurs
        $errors = [];
        // Variable de l'utilisateur passé dans le formulaire 
        $user = $userHydrated;
        // Appel de la classe avec les requêtes SQL
        $userRepository = new UserRepository;
        // Expresión regular pour la verification du mot de passe sécurisé
        $regex = '/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[!@#$%^&*(),.?":{}|<>]).{12,}$/';

        // Si le champ d'utilisateur est vide
        if (empty($user->getNickname())) {
            $errors['nicknameEmpty'] = 'Le champ nickname ne doit pas être vide';
        }

        // Si le champ du mail est vide
        if (empty($user->getEmail())) {
            $errors['emailEmpty'] = "Le champ mail ne doit pas être vide";
        } elseif (!filter_var($user->getEmail(), FILTER_VALIDATE_EMAIL)) { // Si le mail n'est pas un mail valide
            $errors['email'] = "Le mail n\'est pas valide";
        } elseif ($userRepository->findOneByEmail($user->getEmail())) { // Si le mail est déjà enregistrer dans la base de données
            $errors['emailUsed'] = "Le e-mail est déjà utilisé";
        }

        // Si le champ du mot de passe est vide
        if (empty($user->getPassword())) {
            $errors['passwordEmpty'] = "Le champ mot de passe ne doit pas être vide";
        } elseif (strlen($_POST['password']) < 12) { // Si le mot de passe a mois de 12 caractères
            $errors['passwordLen'] = "Le mot de passe doit comporter au moins 12 caractères";
        } elseif (! preg_match($regex, $_POST['password'])) { // Si le mot de passe ne respecte pas le regex
            $errors['passwordInfo'] = "Votre mot de passe doit contenir :
	                                Une lettre majuscule et une lettre minuscule,
	                                un chiffre et
	                                un caractère spécial";
        }

        return $errors;
    }

    // Fonction pour valider le formulaire de connexion
    public function logInValidate(string $email): array
    {
        // Tableau d'erreurs 
        $errors = [];

        // Si le champ du mail est vide
        if (empty($email)) {
            $errors['emailEmpty'] = "Vous devez mettre une adresse e-mail";
        }

        return $errors;
    }

    // Fonction pour vérifier le mot de passe passé dans le formulaire et le mot de passe hashé dans la bdd
    public function passwordVerify(string $password,User $user)
    {
        if ($user && password_verify($password, $user->getPassword())) {
            return true;
        } else {
            return false;
        }
    }

}
