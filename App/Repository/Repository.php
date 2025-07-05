<?php

namespace App\Repository;

use App\Db\Mongo;
use App\Db\Mysql;
use MongoDB\Client;
use PDO;

class Repository
{

    public PDO $pdo;

    public Client $mongo;

    public function __construct()
    {
        $mysql = Mysql::getInstance();

        $this->pdo = $mysql->getPdo();

        $mongo = Mongo::getInstance();

        $this->mongo = $mongo->mongoConnect();
    }
}
