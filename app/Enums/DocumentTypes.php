<?php

namespace App\Enums;

use App\Traits\EnumTranslation;

enum DocumentTypes: string
{
    use EnumTranslation;

    case CPF = 'cpf';
    case CNPJ = 'cnpj';
}
