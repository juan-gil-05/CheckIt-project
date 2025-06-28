<?php

namespace App\Repository;

use App\Db\Mysql;
use PDO;

class Repository
{

    public PDO $pdo;


    public function __construct()
    {
        $mysql = Mysql::getInstance();

        $this->pdo = $mysql->getPdo();
    }
}
