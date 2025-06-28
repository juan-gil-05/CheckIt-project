<?php

namespace App\Controller;

use Exception;

class Controller
{

    public function route() : void
    {
        try {
            $urlController = $_GET['controller'] ?? null;
            if (isset($urlController)) {
                switch ($urlController) {
                    // ?controller=page
                    case 'page':
                        $pageController = new PageController();
                        $pageController->route();
                        break;
                    // ?controller=user
                    case 'user':
                        $pageController = new UserController();
                        $pageController->route();
                        break;
                    default:
                        throw new Exception("Le controleur n'existe pas: ".$_GET['controller']);
                        break;
                }
            } else {
                $pageController = new PageController();
                $pageController->home();
            }
        } catch (Exception $e) {
            $this->render("Errors/error", ["errorMsg" => $e->getMessage()]);
        }
    }

    protected function render(string $path, array $params = []): void
    {
        $filePath = BASE_PATH . "Templates/" . $path . ".php";
        try {

            if (!file_exists($filePath)) {
                throw new Exception("Fichier introuvable: " . $filePath);
            }
            extract($params);
            require_once $filePath;

        } catch (Exception $e) {
            $this->render("Errors/error", ["errorMsg" => $e->getMessage()]);
        }
    }
}
