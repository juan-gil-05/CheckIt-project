<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Tools\UserValidator;
use Exception;

class UserController extends Controller
{
    public function route(): void
    {
        $urlAction = $_GET['action'] ?? null;
        try {

            if (isset($urlAction)) {
                switch ($urlAction) {
                    // ?controller=User&action=signUp
                    case 'signUp':
                        $this->signUp();
                        break;
                    default:
                        throw new Exception("Le controleur n'existe pas: " . $_GET['action']);
                        break;
                }
            } else {
                throw new Exception("Aucune action détectée");
            }
        } catch (Exception $e) {
            $this->render("Errors/error", ["errorMsg" => $e->getMessage()]);
        }
    }


    /*
    Exemple d'appel depuis l'url
        ?controller=user&action=signUp
    */
    protected function signUp()
    {
        $errors = [];
        $nickname = "";
        $email = "";
        $password = "";
        try {
            $user = new User();
            $UserValidator = new UserValidator();
            $userRepository = new UserRepository();
            // Si le formulaire est envoyé, on hydrate l'objet User avec les données passées
            if (isset($_POST['signUp'])) {
                // var_dump($_POST);
                $user->hydrate($_POST);
                $nickname = $user->getNickname();
                $email = $user->getEmail();
                $password = $user->getPassword();
                // Pour hasher le mot de passe
                $this->passwordHasher($user);
                // Pour valider s'il n'y a pas des erreurs dans le formulaire
                $errors = $UserValidator->singUpValidate($user);
                // S'il n'y a pas des erreurs, on crée l'utilisateur dans la basse des données
                if (empty($errors)) {
                    // Si l'utilisateur est passager 
                    $userRepository->createUser($user);
                    // On envoie l'utilisateur vers la page de connexion
                    header('Location: ?controller=page&action=home');
                    exit();
                }
            }
            // On affiche la page de création du compte, et on passe des params
            $this->render(
                "User/sign-up",
                [
                    'errors' => $errors,
                    'nickname' => $nickname,
                    'password' => $password,
                    'email' => $email,
                ]
            );
        } catch (Exception $e) {
            $this->render("Errors/error", [
                'errorMsg' => $e->getMessage()
            ]);
        }
    }

    // Fonction pour hasher le mot de passe
    public function passwordHasher(User $user)
    {
        if (!empty($_POST['password'])) {
            $passwordHashed = password_hash($user->getPassword(), PASSWORD_DEFAULT);
            return $user->setPassword($passwordHashed);
        } else {
            return false;
        }
    }
}
