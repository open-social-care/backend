<?php

namespace App\Traits;

trait EnumTranslation
{
    public static function trans($value): string
    {
        $className = self::classNameToSnakeCase();
        return __("enums.$className.$value");
    }

    private static function classNameToSnakeCase(): string
    {
        $className = get_called_class();
        $parts = explode("\\", $className);
        $className = end($parts);
        return strtolower(preg_replace('/(?<!^)([A-Z])/', '_$1', $className));
    }
}