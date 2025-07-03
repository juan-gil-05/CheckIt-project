<?php

namespace App\Db;

use Exception;
use MongoDB\Client;

class Mongo
{
    private $db_user_mongo;
    private $db_password_mongo;
    private $db_port_mongo;
    private $db_host_mongo;
    private static $_instance = null;

    public function __construct()
    {
        // Appel du fichier avec les paramètres de la BDD
        $config = require BASE_PATH . "/config.php";

        $this->db_user_mongo = $config['MONGO_INITDB_ROOT_USERNAME'];
        $this->db_password_mongo = $config['MONGO_INITDB_ROOT_PASSWORD'];
        $this->db_host_mongo = $config['MONGO_HOST'];
        $this->db_port_mongo = $config['MONGO_PORT'];
    }


    // SINGLETON pour instancier la class Mongodb une seule fois
    public static function getInstance(): self
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new Mongo;
        }
        return self::$_instance;
    }

    // Fonction pour se connecter à mongodb 
    public function mongoConnect()
    {
        $user = $this->db_user_mongo;
        $password = $this->db_password_mongo;
        $host = $this->db_host_mongo;
        $port = $this->db_port_mongo;

        try {

            // $client = new Client("mongodb://checkit_mongo:tx8fGVgD9VMlNOu@mongo:27017");

            $connectionPath = "mongodb://" . $user . ":" . $password . "@" . $host . ":" . $port;
            // Instance de la classe Client
            $mongo = new Client($connectionPath);
            // On retourne l'instance de la base de données
            return $mongo;
        } catch (Exception $e) {
            echo ('Error : ' . $e->getMessage());
            exit;
        }
    }
}
