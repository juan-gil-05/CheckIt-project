<?php

namespace App\Repository;

use Exception;

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
}
