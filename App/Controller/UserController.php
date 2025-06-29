<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Security\UserValidator;
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
                    case 'logIn':
                        $this->logIn();
                        break;
                    case 'logOut':
                        $this->logOut();
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
                    header('Location: ?controller=user&action=logIn');
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

    /*
    Exemple d'appel depuis l'url
        ?controller=user&action=logIn
    */
    protected function logIn()
    {
        $errors = [];
        $userEmail = "";

        try {
            // Si le formulaire est envoyé, on cherche l'utilisateur par son mail
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $userRepository = new UserRepository;
                $userValidator = new UserValidator;
                // on cherche l'utilisateur par son mail
                $user = $userRepository->findOneByEmail($_POST['email']);
                // On récupère le mail de l'utilisateur
                $userEmail = ($user) ? $user->getEmail() : $_POST['email'];
                $userPassword = ($user) ? $user->getPassword() : $_POST['password'];
                // Validation des erreurs dans le formulaire
                $errors = $userValidator->logInValidate($userEmail, $userPassword);
                // S'il n'y a pas des erreurs ...
                if (empty($errors)) {
                    // Et si le mot de passe est correct ...
                    if ($user && $userValidator->passwordVerify($_POST['password'], $user)) {
                        // Pour générer l'id de la session
                        session_regenerate_id(true);
                        // On crée une nouvelle session avec les données de l'utilisateur connecté
                        $_SESSION['user'] = [
                            "id" => $user->getId(),
                            "nickname" => $user->getNickname(),
                            "email" => $user->getEmail(),
                        ];
                        // On redirige vers la page d'accueil
                        header('location: ?controller=page&action=home');
                        exit();
                    } else {
                        $errors['invalidUser'] = "Email ou mot de passe invalide";
                    }
                }
            }
        } catch (Exception $e) {
            $this->render("Errors/error", [
                'errorMsg' => $e->getMessage()
            ]);
        }
        // On affiche la page de connexion, et on passe des params
        $this->render("User/log-in", ['errors' => $errors, 'email' => $userEmail]);
    }

    /*
    Exemple d'appel depuis l'url
        ?controller=user&action=logOut
    */
    protected function logOut()
    {
        //Supprime les données de session du serveur
        session_destroy();
        //Supprime les données du tableau $_SESSION
        unset($_SESSION);
        // On envoie l'utilisateur vers la page de connexion
        header('location: index.php?controller=user&action=logIn');
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
