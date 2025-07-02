<?php

namespace App\Repository;

class TagRepository extends Repository
{

    public function saveItemTag(int $itemId, int $tagId)
    {
        $query = $this->pdo->prepare("INSERT INTO Item_Tag (item_id, tag_id) VALUES (:itemId, :tagId)");
        $query->bindValue(":itemId", $itemId, $this->pdo::PARAM_INT);
        $query->bindValue(":tagId", $tagId, $this->pdo::PARAM_INT);
        return $query->execute();
    }

    public function getAllTags(): array
    {
        $query = $this->pdo->prepare("SELECT * FROM Tag");
        $query->execute();
        return $query->fetchAll($this->pdo::FETCH_ASSOC);
    }

    public function getItemTagByItemId(int $itemId): array
    {
        $query = $this->pdo->prepare("SELECT IT.*, Tag.name as tag_name 
                                        FROM Item_Tag as IT 
                                        INNER JOIN Tag ON IT.tag_id = Tag.id 
                                        WHERE IT.item_id = :itemId");
        $query->bindValue(":itemId", $itemId, $this->pdo::PARAM_INT);
        $query->execute();
        return $query->fetchAll($this->pdo::FETCH_ASSOC);
    }

    public function deleteItemTag(int $itemId, int $categoryId): bool
    {
        $query = $this->pdo->prepare('DELETE FROM Item_Tag WHERE item_id = :itemId AND tag_id = :categoryId');
        $query->bindValue(':itemId', $itemId, $this->pdo::PARAM_INT);
        $query->bindValue(':categoryId', $categoryId, $this->pdo::PARAM_INT);

        return $query->execute();
    }
}
