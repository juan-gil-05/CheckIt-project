<?php

namespace App\Tools;

class StringTools
{
    /*
        Transforme une chaine en camelCase (ou pascalCase si deuxième param à true)
    */
    public static function toCamelCase(string $value, $pascalCase = false): string
    {
        /*  
            On remplace les tiret et underscore par des espaces, 
            puis en met les premières lettres de chaque mot en majuscule avec ucword
        */
        $value = ucwords(str_replace(array('-', '_'), ' ', $value));
        // On retire les espaces
        $value = str_replace(' ', '', $value);
        // Si le param $pasacalCase est à true, on met la première lettre en minuscule
        if ($pascalCase === false) {
            return lcfirst($value);
        } else {
            return $value;
        }
    }

    /*
        Transforme une chaine en pascalCase (lowerCamelCase) en appellant toCamelCase avec le deuxième param à true
    */
    public static function toPascalCase(string $value): string
    {
        return self::toCamelCase($value, true);
    }

}
