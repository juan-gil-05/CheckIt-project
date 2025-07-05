<?php

namespace App\Entity;

use App\Tools\StringTools;

class Entity
{

    public static function createAndHydrate(array $data): static
    {
        $entity = new static();
        $entity->hydrate($data);
        return $entity;
    }
    
    // Function to hydrate the object with the data 
    public function hydrate(array $data)
    {
        if (count($data) > 0) {
            foreach ($data as $key => $value) {
                $methodName = 'set' . StringTools::toPascalCase($key);
                if (method_exists($this, $methodName)) {
                    $this->{$methodName}($value);
                }
            }
        }
    }
}
