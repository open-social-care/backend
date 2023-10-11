<?php

namespace App\Enums;

use App\Traits\EnumTranslation;

enum Roles: string
{
    use EnumTranslation;

    case ADMIN = 'admin';
    case MANAGER = 'manager';
    case SOCIAL_ASSISTANT = 'social_assistant';
}
