<?php

namespace App\Controller;

use App\Repository\UserRepository;
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
    protected function signUp(): void
    {
        $repo = new UserRepository;

        // $result = $repo->callDb();
        // var_dump($result);

        $this->render("User/sign-up");
    }
}
