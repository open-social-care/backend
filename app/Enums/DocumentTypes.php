<?php

namespace App\Enums;

enum DocumentTypes implements Enum
{
    case CPF;
    case CNPJ;

    public static function getCasesNameValues(): array
    {
        return array_column(self::cases(), 'name');
    }

    public static function trans($value): string
    {
        return __('enums.document_types.'.$value);
    }
}
