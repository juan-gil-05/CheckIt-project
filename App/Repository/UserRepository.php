<?php

namespace App\Repository;

class UserRepository extends Repository
{

    public function callDb()
    {
        $pdo = $this->pdo;

        $query = $pdo->query('SHOW TABLES');
        $result = $query->fetchAll($pdo::FETCH_ASSOC);
        return var_dump($result);
    }



}   