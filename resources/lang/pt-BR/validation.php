<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted' => ':attribute deve ser aceito.',
    'active_url' => ':attribute não é uma URL válida.',
    'after' => ':attribute deve ser uma data depois de :date.',
    'after_or_equal' => ':attribute deve ser uma data posterior ou igual a :date.',
    'alpha' => ':attribute deve conter somente letras.',
    'alpha_dash' => ':attribute deve conter letras, números e traços.',
    'alpha_num' => ':attribute deve conter somente letras e números.',
    'array' => ':attribute deve ser um array.',
    'before' => ':attribute deve ser uma data antes de :date.',
    'before_or_equal' => ':attribute deve ser uma data anterior ou igual a :date.',
    'between' => [
        'numeric' => ':attribute deve estar entre :min e :max.',
        'file' => ':attribute deve estar entre :min e :max kilobytes.',
        'string' => ':attribute deve estar entre :min e :max caracteres.',
        'array' => ':attribute deve ter entre :min e :max itens.',
    ],
    'boolean' => ':attribute deve ser verdadeiro ou falso.',
    'confirmed' => 'A confirmação de :attribute não confere.',
    'date' => ':attribute não é uma data válida.',
    'date_format' => ':attribute não confere com o formato :format.',
    'different' => ':attribute e :other devem ser diferentes.',
    'digits' => ':attribute deve ter :digits dígitos.',
    'digits_between' => ':attribute deve ter entre :min e :max dígitos.',
    'dimensions' => ':attribute tem dimensões de imagem inválidas.',
    'distinct' => ':attribute tem um valor duplicado.',
    'email' => ':attribute deve ser um endereço de e-mail válido.',
    'exists' => ':attribute selecionado é inválido.',
    'file' => ':attribute deve ser um arquivo.',
    'filled' => ':attribute é um campo obrigatório.',
    'image' => ':attribute deve ser uma imagem.',
    'in' => ':attribute é inválido.',
    'in_array' => ':attribute não existe em :other.',
    'integer' => ':attribute deve ser um inteiro.',
    'ip' => ':attribute deve ser um endereço IP válido.',
    'ipv4' => ':attribute deve ser um endereço IPv4 válido.',
    'ipv6' => ':attribute deve ser um endereço IPv6 válido.',
    'json' => ':attribute deve ser um JSON válido.',
    'max' => [
        'numeric' => ':attribute não deve ser maior que :max.',
        'file' => ':attribute não deve ter mais que :max kilobytes.',
        'string' => ':attribute não deve ter mais que :max caracteres.',
        'array' => ':attribute não deve ter mais que :max itens.',
    ],
    'mimes' => ':attribute deve ser um arquivo do tipo: :values.',
    'mimetypes' => ':attribute deve ser um arquivo do tipo: :values.',
    'min' => [
        'numeric' => ':attribute deve ser no mínimo :min.',
        'file' => ':attribute deve ter no mínimo :min kilobytes.',
        'string' => ':attribute deve ter no mínimo :min caracteres.',
        'array' => ':attribute deve ter no mínimo :min itens.',
    ],
    'not_in' => 'O :attribute selecionado é inválido.',
    'numeric' => ':attribute deve ser um número.',
    'present' => 'O campo :attribute deve ser presente.',
    'regex' => 'O formato de :attribute é inválido.',
    'required' => 'O campo :attribute é obrigatório.',
    'required_if' => 'O campo :attribute é obrigatório quando :other é :value.',
    'required_unless' => 'O :attribute é necessário a menos que :other esteja em :values.',
    'required_with' => 'O campo :attribute é obrigatório quando :values está presente.',
    'required_with_all' => 'O campo :attribute é obrigatório quando :values estão presentes.',
    'required_without' => 'O campo :attribute é obrigatório quando :values não está presente.',
    'required_without_all' => 'O campo :attribute é obrigatório quando nenhum destes estão presentes: :values.',
    'same' => ':attribute e :other devem ser iguais.',
    'size' => [
        'numeric' => ':attribute deve ser :size.',
        'file' => ':attribute deve ter :size kilobytes.',
        'string' => ':attribute deve ter :size caracteres.',
        'array' => ':attribute deve conter :size itens.',
    ],
    'string' => ':attribute deve ser uma string',
    'timezone' => ':attribute deve ser uma timezone válida.',
    'unique' => ':attribute já está em uso.',
    'uploaded' => ':attribute falhou ao ser enviado.',
    'url' => 'O formato de :attribute é inválido.',
    'document' => 'O formato de :attribute é inválido, formatos aceitos (CNPJ) 00.000.000/0000-00 / (CPF) 000.000.000-00',
    'required_answer' => 'A resposta é obrigatória para esta pergunta',
    'phone' => 'O :attribute é inválido',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
        'addresses.*.street' => [
            'required_with' => 'A Rua é obrigatório',
        ],
        'addresses.*.number' => [
            'required_with' => 'O Número é obrigatório',
        ],
        'addresses.*.district' => [
            'required_with' => 'O Bairro é obrigatório',
        ],
        'addresses.*.state_id' => [
            'required_with' => 'O Estado é obrigatório',
        ],
        'addresses.*.city_id' => [
            'required_with' => 'A Cidade é obrigatório',
        ],
        'short_answers.*.short_question_id' => [
            'required' => 'O id da pergunta do template é obrigatório',
        ],
        'short_answers.*.answer' => [
            'required' => 'A pergunta é obrigatória',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap attribute place-holders
    | with something more reader friendly such as E-Mail Address instead
    | of "email". This simply helps us make messages a little cleaner.
    |
    */

    'attributes' => [
        'name' => 'Nome',
        'title' => 'Título',
        'email' => 'Email',
        'password' => 'Senha',
        'username' => 'Usuário',
        'description' => 'Descrição',
        'is_active' => 'Ativo',
        'checklist_template_id' => 'Campanha',
        'begins_at' => 'Início em',
        'ends_at' => 'Término em',
        'is_company_admin' => 'Administrador ?',
        'company_id' => 'Empresa',
        'key' => 'Código da filial',
        'parent_id' => 'Grupo de lojas',
        'address' => [
            'cep' => 'CEP',
            'number' => 'Número',
            'district' => 'Bairro',
            'complement' => 'Complemento',
            'street' => 'Rua',
            'state_id' => 'Estado',
            'city_id' => 'Cidade',
        ],
        'roles' => 'Perfis',
        'organizations' => 'Organizações',
        'phone' => 'Telefone',
        'document_type' => 'Tipo de documento',
        'document' => 'Documento',
        'subject_ref' => 'Referência de sujeito/usuários',
        'users' => 'Usuários',
        'role_name' => 'Perfil de usuário',
        'data.*.role_name' => 'Perfil de usuário',
        'user_id' => 'Usuário',
        'data.*.user_id' => 'Usuário',
        'addresses' => 'Endereços',
        'addresses.*.street' => 'Cidade',
        'addresses.*.number' => 'Número',
        'addresses.*.district' => 'Bairro',
        'addresses.*.complement' => 'Complemento',
        'addresses.*.state_id' => 'Estado',
        'addresses.*.city_id' => 'Cidade',
        'birth_date' => 'Data de nascimento',
        'nationality' => 'Nacionalidade',
        'father_name' => 'Nome do pai',
        'mother_name' => 'Nome da mãe',
        'skin_color' => 'Cor de pele',
        'relative_relation_type' => 'Tipo de parentesco',
        'relative_name' => 'Nome do parente',
        'relative_phone' => 'Telefone do parente',
        'short_answers' => 'Pergunta',
    ],
];
