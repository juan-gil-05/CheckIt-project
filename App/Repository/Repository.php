<?php

namespace App\Repository;

use App\Db\Mongo;
use App\Db\Mysql;
use MongoDB\Client;
use MongoDB\Database;
use PDO;

class Repository
{

    public PDO $pdo;

    public Client $mongo;

    public function __construct()
    {
        $mysql = Mysql::getInstance();

        $this->pdo = $mysql->getPdo();

        // On appelle une instance de la class Mongodb pour appeler la base de données
        $mongo = Mongo::getInstance();

        // Et on "passe" l'instance à l'objet Database pour créer la conexion a la BDD
        $this->mongo = $mongo->mongoConnect();
    }
}
