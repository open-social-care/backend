<?php

namespace App\Enums;

class SkinColors extends Enum
{
    const BLACK = 'black';
    const MEDIUM_BLACK = 'medium_black';
    const INDIGENOUS = 'indigenous';
    const WHITE = 'white';
    const YELLOW = 'yellow';

    public static function trans($value): string
    {
        return __('enums.skin_colors.' . $value);
    }
}