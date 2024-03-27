<?php

namespace App\Enums;

use App\Traits\EnumTranslation;

enum AuditEventTypesEnum: string
{
    use EnumTranslation;

    case CREATE = 'create';
    case UPDATE = 'update';
    case DELETE = 'delete';
    case VIEW = 'view';
    case LOGIN = 'login';
}
