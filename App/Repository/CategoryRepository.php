<?php

namespace App\Repository;

class CategoryRepository extends Repository
{

    public function getAllCategories() : array
    {
        $query = $this->pdo->prepare("SELECT * FROM Category");
        $query->execute();
        return $query->fetchAll($this->pdo::FETCH_ASSOC);
    }


}