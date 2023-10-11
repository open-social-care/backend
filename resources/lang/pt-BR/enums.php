<?php

return [
    'document_types' => [
        \App\Enums\DocumentTypes::CPF->value => 'CPF',
        \App\Enums\DocumentTypes::CNPJ->value => 'CNPJ',
    ],

    'roles' => [
      \App\Enums\Roles::ADMIN->value => 'Admin',
      \App\Enums\Roles::MANAGER->value => 'Gestor(a)',
      \App\Enums\Roles::SOCIAL_ASSISTANT->value => 'Assistente Social',
    ],

    'skin_colors' => [
        \App\Enums\SkinColors::BLACK->value => 'Preto(a)',
        \App\Enums\SkinColors::MEDIUM_BLACK->value => 'Pardo(a)',
        \App\Enums\SkinColors::INDIGENOUS->value => 'Indígeno(a)',
        \App\Enums\SkinColors::WHITE->value => 'Branco(a)',
        \App\Enums\SkinColors::YELLOW->value => 'Amarelo(a)',
    ],
];