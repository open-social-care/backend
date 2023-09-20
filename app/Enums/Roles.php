<?php

namespace App\Enums;

class Roles extends Enum
{
    const ADMIN = 'admin';
    const MANAGER = 'manager';
    const SOCIAL_ASSISTANT = 'social_assistant';

    public static function trans($value): string
    {
        return __('enums.roles.' . $value);
    }
}