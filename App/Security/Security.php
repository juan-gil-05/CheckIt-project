<?php

namespace App\Security;

class Security
{
    // Fonction pour savoir si l'utlisateur est connecté ou pas
    public static function isLogged()
    {
        return isset($_SESSION['user']);
    }

}
