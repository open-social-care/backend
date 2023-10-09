<?php

namespace App\Enums;

enum SkinColors implements Enum
{
    case BLACK;
    case MEDIUM_BLACK;
    case INDIGENOUS;
    case WHITE;
    case YELLOW;

    public static function getCasesNameValues(): array
    {
        return array_column(self::cases(), 'name');
    }

    public static function trans($value): string
    {
        return __('enums.skin_colors.'.$value);
    }
}
