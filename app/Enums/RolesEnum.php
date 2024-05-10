<?php

namespace App\Enums;

use App\Traits\EnumTranslation;

enum RolesEnum: string
{
    use EnumTranslation;

    case ADMIN = 'admin';
    case MANAGER = 'manager';
    case SOCIAL_ASSISTANT = 'social-assistant';
}
