<?php

namespace App\Traits;

trait EnumTranslation
{
    /**
     * Return translated value of enum
     */
    public static function trans($value): string
    {
        $className = self::classNameToSnakeCase();

        return __("enums.$className.$value");
    }

    /**
     * Return the name of the enum class to filter by key in enums lang
     */
    private static function classNameToSnakeCase(): string
    {
        $className = get_called_class();
        $parts = explode('\\', $className);
        $className = end($parts);
        $className = strtolower(preg_replace('/(?<!^)([A-Z])/', '_$1', $className));

        return str_replace('_enum', '', $className);
    }
}
