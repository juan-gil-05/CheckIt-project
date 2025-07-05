<?php

namespace App\Tools;

class StringTools
{
    /* To Transform a string into camelCase (or pascalCase)*/
    public static function toCamelCase(string $value, $pascalCase = false): string
    {
        $value = ucwords(str_replace(array('-', '_'), ' ', $value));
        $value = str_replace(' ', '', $value);
        if ($pascalCase === false) {
            return lcfirst($value);
        } else {
            return $value;
        }
    }

    public static function toPascalCase(string $value): string
    {
        return self::toCamelCase($value, true);
    }
}
