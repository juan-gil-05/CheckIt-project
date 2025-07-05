<?php

namespace App\Security;

use App\Entity\User;

class Security
{

    // Fonction pour savoir si l'utlisateur est connecté ou pas
    public static function isLogged()
    {
        return isset($_SESSION['user']);
    }

    public static function encryptUrlParameter($data): string
    {
        // Config file with environment variables
        $config = require BASE_PATH . "/config.php";

        $key = $config['ENCRYPTER_KEY'];
        $iv = random_bytes(16);
        $encrypted = openssl_encrypt($data, "AES-128-CBC", $key, 0, $iv);
        $base64Encoded = base64_encode($iv . $encrypted);
        // I change some symbols to avoid the errors
        return strtr($base64Encoded, '+/', '-_');
    }

    public static function decryptUrlParameter($encryptedParam): string
    {
        // Config file with environment variables
        $config = require BASE_PATH . "/config.php";

        $key = $config['ENCRYPTER_KEY'];
        $base64Decoded = strtr($encryptedParam, '-_', '+/');
        // fonction pour decoder le param
        $decodedParam = base64_decode($base64Decoded);
        $IV = substr($decodedParam, 0, 16);
        $encrypted = substr($decodedParam, 16);
        $decrypted = openssl_decrypt($encrypted, "AES-128-CBC", $key, 0, $IV);
        return $decrypted;
    }

    public static function passwordHasher(User $user)
    {
        if (!empty($_POST['password'])) {
            $passwordHashed = password_hash($user->getPassword(), PASSWORD_DEFAULT);
            return $user->setPassword($passwordHashed);
        } else {
            return false;
        }
    }
}
