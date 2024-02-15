<?php

return [
    'common' => [
        'success_view' => 'Registro carregado com sucesso.',
        'success_create' => 'Registro criado com sucesso.',
        'success_update' => 'Registro atualizado com sucesso.',
        'success_destroy' => 'Registro apagado com sucesso.',
        'error_validation_request' => 'Verifique os erros nos campos!',
    ],

    'auth' => [
        'login_success' => 'Usurário logado com sucesso.',
        'login_invalid' => 'Credenciais de login inválidas.',
        'logout_success' => 'Usurário deslogado com sucesso.',

        'password' => [
            'password_reset_success' => 'Senha redefinida com sucesso.',
        ],

        'access_denied' => 'Acesso não autorizado.',
    ],

    'email' => [
        'hello' => 'Olá,',
        'att' => 'Atensiosamente,',
        'team_name' => 'Equipe Open Social Care.',

        'reset_password' => [
            'title' => 'Open Social Care: Recuperação de senha',
            'message' => 'Você solicitou a recuperação da sua senha de acesso a plataforma Open Social Care.',
            'token_message' => 'Seu código de recuperação é:',
            'observation_message' => 'Observação: O código de recuperação tem um limite de 15 minutos para ser utilizado, após isso, é necessário solicitar um novo.',
        ],
    ],
];
