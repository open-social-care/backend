<?php

namespace App\Enums;

class DocumentTypes extends Enum
{
    const CPF = 'cpf';
    const CNPJ = 'CNPJ';

    public static function trans($value): string
    {
        return __('enums.document-types.' . $value);
    }
}