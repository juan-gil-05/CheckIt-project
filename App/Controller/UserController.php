<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Security\Security;
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
                    // ?controller=user&action=signUp
                    case 'signUp':
                        $this->signUp();
                        break;
                    // ?controller=user&action=logIn
                    case 'logIn':
                        $this->logIn();
                        break;
                    // ?controller=user&action=logOut
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
            if (isset($_POST['signUp'])) {
                $user->hydrate($_POST);
                $nickname = $user->getNickname();
                $email = $user->getEmail();
                $password = $user->getPassword();
                Security::passwordHasher($user);
                // To verify the form
                $errors = $UserValidator->singUpValidate($user);
                if (empty($errors)) {
                    $userRepository->createUser($user);
                    header('Location: ?controller=user&action=logIn');
                    exit();
                }
            }
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
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $userRepository = new UserRepository;
                $userValidator = new UserValidator;
                $user = $userRepository->findOneByEmail($_POST['email']);
                $userEmail = ($user) ? $user->getEmail() : $_POST['email'];
                $userPassword = ($user) ? $user->getPassword() : $_POST['password'];
                $errors = $userValidator->logInValidate($userEmail, $userPassword);
                if (empty($errors)) {
                    if ($user && $userValidator->passwordVerify($_POST['password'], $user)) {
                        // User session setUp
                        session_regenerate_id(true);
                        $_SESSION['user'] = [
                            "id" => $user->getId(),
                            "nickname" => $user->getNickname(),
                            "email" => $user->getEmail(),
                        ];
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
        $this->render("User/log-in", ['errors' => $errors, 'email' => $userEmail]);
    }

    /*
    Exemple d'appel depuis l'url
        ?controller=user&action=logOut
    */
    protected function logOut()
    {
        // To delete session data
        session_destroy();
        unset($_SESSION);
        header('location: index.php?controller=user&action=logIn');
    }
}
