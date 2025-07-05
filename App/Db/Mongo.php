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
        $config = require BASE_PATH . "/config.php";

        $this->db_user_mongo = $config['MONGO_INITDB_ROOT_USERNAME'];
        $this->db_password_mongo = $config['MONGO_INITDB_ROOT_PASSWORD'];
        $this->db_host_mongo = $config['MONGO_HOST'];
        $this->db_port_mongo = $config['MONGO_PORT'];
    }

    // SINGLETON pattern
    public static function getInstance(): self
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new Mongo;
        }
        return self::$_instance;
    }

    public function mongoConnect()
    {
        $user = $this->db_user_mongo;
        $password = $this->db_password_mongo;
        $host = $this->db_host_mongo;
        $port = $this->db_port_mongo;

        try {
            // Connection string to mongoDB Atlas - App in production 
            $connectionPath = "mongodb+srv://" . $user . ":" . $password . "@" . $host . "/?retryWrites=true&w=majority";
            // Connection string to mongoDB - App in local 
            // $connectionPath = "mongodb://" . $user . ":" . $password . "@" . $host . ":" . $port;
            $mongo = new Client($connectionPath);
            return $mongo;
        } catch (Exception $e) {
            echo ('Error : ' . $e->getMessage());
            exit;
        }
    }
}
