<?php

namespace App\Repository;

use Exception;
use MongoDB\BSON\ObjectId;

class ItemRepository extends Repository
{
    public function saveReminder(int $userId, int $itemId, int $listId, $remindAt, string $message)
    {
        $mongo = $this->mongo;

        $reminder = [
            'user_id' => $userId,
            'item_id' => $itemId,
            'list_id' => $listId,
            // strotime to transform to timestamp UNIX and * 1000 because mongo uses milliseconds instead of seconds
            'remind_at' => $remindAt,
            'notified' => false,
            'message' => $message
        ];
        $mongo->selectCollection('checkItMongoDB', 'reminders')->insertOne($reminder);
    }

    public function findReminderByUserId(int $userId, int $listId)
    {
        $mongo = $this->mongo;

        $reminder = $mongo->selectCollection('checkItMongoDB', 'reminders')->find(
            [
                "user_id" => $userId,
                "list_id" => $listId,
            ]
        )->toArray();

        return $reminder;
    }

    public function deleteReminderById(string $remindId)
    {

        $mongo = $this->mongo;

        return $mongo->selectCollection('checkItMongoDB', 'reminders')->deleteOne(['_id' => new ObjectId($remindId)]);

    }
}
