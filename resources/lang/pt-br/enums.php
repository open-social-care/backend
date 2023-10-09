<?php

return [
    'document_types' => [
        \App\Enums\DocumentTypes::CPF->name => 'CPF',
        \App\Enums\DocumentTypes::CNPJ->name => 'CNPJ',
    ],

    'roles' => [
      \App\Enums\Roles::ADMIN->name => 'Admin',
      \App\Enums\Roles::MANAGER->name => 'Gestor(a)',
      \App\Enums\Roles::SOCIAL_ASSISTANT->name => 'Assistente Social',
    ],

    'skin_colors' => [
        \App\Enums\SkinColors::BLACK->name => 'Preto(a)',
        \App\Enums\SkinColors::MEDIUM_BLACK->name => 'Pardo(a)',
        \App\Enums\SkinColors::INDIGENOUS->name => 'Indígeno(a)',
        \App\Enums\SkinColors::WHITE->name => 'Branco(a)',
        \App\Enums\SkinColors::YELLOW->name => 'Amarelo(a)',
    ],
];