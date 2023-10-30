<?php

namespace App\Enums;

use App\Traits\EnumTranslation;

enum DocumentTypesEnum: string
{
    use EnumTranslation;

    case CPF = 'cpf';
    case CNPJ = 'cnpj';
}
