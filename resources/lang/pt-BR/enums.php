<?php

return [
    'document_types' => [
        \App\Enums\DocumentTypesEnum::CPF->value => 'CPF',
        \App\Enums\DocumentTypesEnum::CNPJ->value => 'CNPJ',
    ],

    'roles' => [
        \App\Enums\RolesEnum::ADMIN->value => 'Admin',
        \App\Enums\RolesEnum::MANAGER->value => 'Gestor(a)',
        \App\Enums\RolesEnum::SOCIAL_ASSISTANT->value => 'Assistente Social',
    ],

    'skin_colors' => [
        \App\Enums\SkinColorsEnum::BLACK->value => 'Preto(a)',
        \App\Enums\SkinColorsEnum::MEDIUM_BLACK->value => 'Pardo(a)',
        \App\Enums\SkinColorsEnum::INDIGENOUS->value => 'Indígeno(a)',
        \App\Enums\SkinColorsEnum::WHITE->value => 'Branco(a)',
        \App\Enums\SkinColorsEnum::YELLOW->value => 'Amarelo(a)',
    ],

    'audit_event_types' => [
        \App\Enums\AuditEventTypesEnum::CREATE->value => 'Preto(a)',
        \App\Enums\AuditEventTypesEnum::UPDATE->value => 'Pardo(a)',
        \App\Enums\AuditEventTypesEnum::DELETE->value => 'Indígeno(a)',
        \App\Enums\AuditEventTypesEnum::VIEW->value => 'Branco(a)',
    ],
];
