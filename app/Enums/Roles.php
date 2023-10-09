<?php

namespace App\Enums;

enum Roles implements Enum
{
    case ADMIN;
    case MANAGER;
    case SOCIAL_ASSISTANT;

    public static function getCasesNameValues(): array
    {
        return array_column(self::cases(), 'name');
    }

    public static function trans(string $value): string
    {
        return __('enums.roles.'.$value);
    }
}
