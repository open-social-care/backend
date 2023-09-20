<?php

return [
    'roles' => [
        \App\Enums\Roles::ADMIN => 'Administrador',
        \App\Enums\Roles::MANAGER => 'Gestor(a)',
        \App\Enums\Roles::SOCIAL_ASSISTANT => 'Assistente social',
    ],

    'document-types' => [
        \App\Enums\DocumentTypes::CPF => 'CPF',
        \App\Enums\DocumentTypes::CNPJ => 'CNPJ',
    ],

    'skin_colors' => [
        \App\Enums\SkinColors::BLACK => 'Preto(a)',
        \App\Enums\SkinColors::MEDIUM_BLACK => 'Pardo(a)',
        \App\Enums\SkinColors::INDIGENOUS => 'Indígeno(a)',
        \App\Enums\SkinColors::WHITE => 'Branco(a)',
        \App\Enums\SkinColors::YELLOW => 'Amarelo(a)',
    ],
];
