<?php

namespace App\Enums;

use App\Traits\EnumTranslation;

enum SkinColorsEnum: string
{
    use EnumTranslation;

    case BLACK = 'black';
    case MEDIUM_BLACK = 'medium-black';
    case INDIGENOUS = 'indigenous';
    case WHITE = 'white';
    case YELLOW = 'yellow';
}
