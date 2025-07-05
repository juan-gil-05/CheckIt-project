<?php

namespace App\Repository;

class ListRepository extends Repository
{

    public function getListsByUserId(int $userId, int $categoryId = null): array
    {
        $sql = "SELECT List.*, Category.name as category_name, 
                Category.icon as category_icon 
                FROM List
                JOIN Category ON Category.id = List.category_id
                WHERE user_id = :user_id";

        if ($categoryId) {
            $sql .= " AND List.category_id = :category_id";
        }

        $query = $this->pdo->prepare($sql);
        $query->bindValue(':user_id', $userId, $this->pdo::PARAM_INT);
        if ($categoryId) {
            $query->bindValue(':category_id', $categoryId, $this->pdo::PARAM_INT);
        }
        $query->execute();
        $lists = $query->fetchAll($this->pdo::FETCH_ASSOC);

        return $lists;
    }

    public function getListById(int $id)
    {
        $query = $this->pdo->prepare('SELECT * FROM List WHERE id = :id');
        $query->bindValue(':id', $id, $this->pdo::PARAM_INT);
        $query->execute();

        return $query->fetch($this->pdo::FETCH_ASSOC);
    }

    public function saveList(string $title, int $userId, int $categoryId, int $id = null): int|bool
    {
        if ($id) {
            // UPDATE
            $query = $this->pdo->prepare("UPDATE List SET title = :title, category_id = :category_id,
                                                        user_id = :user_id
                                WHERE id = :id");
            $query->bindValue(':id', $id, $this->pdo::PARAM_INT);
        } else {
            // INSERT
            $query = $this->pdo->prepare("INSERT INTO List (title, category_id, user_id)
                                VALUES (:title, :category_id, :user_id)");
        }
        $query->bindValue(':title', $title, $this->pdo::PARAM_STR);
        $query->bindValue(':category_id', $categoryId, $this->pdo::PARAM_INT);
        $query->bindValue(':user_id', $userId, $this->pdo::PARAM_INT);

        $res = $query->execute();
        if ($res) {
            if ($id) {
                return $id;
            } else {
                return $this->pdo->lastInsertId();
            }
        } else {
            return false;
        }
    }

    public function deleteListById(int $listId)
    {
        $query = $this->pdo->prepare("DELETE FROM List WHERE id = :listId");
        $query->bindValue(":listId", $listId, $this->pdo::PARAM_INT);
        $query->execute();
    }

    public function saveListItem(string $name, int $listId, bool $status = false, int $id = null): bool
    {
        if ($id) {
            // UPDATE
            $query = $this->pdo->prepare("UPDATE Item SET name = :name, list_id = :list_id,
                                                        status = :status
                                WHERE id = :id");
            $query->bindValue(':id', $id, $this->pdo::PARAM_INT);
        } else {
            // INSERT
            $query = $this->pdo->prepare("INSERT INTO Item (name, list_id, status)
                                VALUES (:name, :list_id, :status)");
        }
        $query->bindValue(':name', $name, $this->pdo::PARAM_STR);
        $query->bindValue(':list_id', $listId, $this->pdo::PARAM_INT);
        $query->bindValue(':status', $status, $this->pdo::PARAM_BOOL);
        return $query->execute();
    }

    public function getListItems(int $id): array
    {
        $query = $this->pdo->prepare('SELECT * FROM Item WHERE list_id = :id');
        $query->bindValue(':id', $id, $this->pdo::PARAM_INT);
        $query->execute();

        return $query->fetchAll($this->pdo::FETCH_ASSOC);
    }

    public function deleteListItemById(int $id): bool
    {
        $query = $this->pdo->prepare('DELETE FROM Item WHERE id = :id');
        $query->bindValue(':id', $id, $this->pdo::PARAM_INT);

        return $query->execute();
    }

    public function updateListItemStatus(int $id, bool $status): bool
    {
        $query = $this->pdo->prepare('UPDATE Item SET status = :status WHERE id = :id ');
        $query->bindValue(':id', $id, $this->pdo::PARAM_INT);
        $query->bindValue(':status', $status, $this->pdo::PARAM_BOOL);

        return $query->execute();
    }
}
