<?php

namespace App\Repository;

use Exception;
use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;
use MongoDB\Collection;

class ItemRepository extends Repository
{
    public function conect()
    {
        try {
            $client = $this->mongo;

            $databases = $client->listDatabases();
            echo "<h2>✅ Connexion réussie à MongoDB</h2>";
            echo "<ul>";
            foreach ($databases as $db) {
                echo "<li>" . $db->getName() . "</li>";
            }
            echo "</ul>";
        } catch (Exception $e) {
            echo "<h2>❌ Erreur de connexion :</h2>";
            echo $e->getMessage();
        }
    }

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

        $reminder = $mongo->selectCollection('checkItMongoDB', 'reminders')->deleteOne(['_id' => new ObjectId($remindId)]);

    }
}
