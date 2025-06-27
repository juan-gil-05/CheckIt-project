<?php

namespace App\Controller;

use Exception;

class PageController extends Controller
{
    public function route(): void
    {

        $urlAction = $_GET['action'] ?? null;
        try {

            if (isset($urlAction)) {
                switch ($urlAction) {
                    // ?controller=page&action=home
                    case 'home':
                        $this->home();
                        break;
                    default:
                        throw new Exception("Le controleur n'existe pas: " . $_GET['action']);
                        break;
                }
            } else {
                throw new Exception("Aucune action détectée");
            }
        } catch (Exception $e) {
            $this->render("Errors/error", ["error" => $e->getMessage()]);
        }
    }


    /*
    Exemple d'appel depuis l'url
        ?controller=page&action=home
    */
    protected function home(): void
    {
        $this->render("Pages/home");
    }
}
