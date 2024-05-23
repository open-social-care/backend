<?php

namespace App\DTO\SocialAssistant;

use App\Models\Organization;
use App\Models\User;

class SubjectDTO
{
    public string $name;

    public string $birth_date;

    public ?string $nationality;

    public ?string $phone;

    public ?string $father_name;

    public ?string $mother_name;

    public ?string $cpf;

    public ?string $rg;

    public ?string $skin_color;

    public ?string $relative_relation_type;

    public ?string $relative_name;

    public ?string $relative_phone;

    public int $organization_id;

    public int $user_id;

    /**
     * Construct class set DTO attributes
     */
    public function __construct(array $data, Organization $organization, User $user)
    {
        $this->name = data_get($data, 'name');
        $this->birth_date = data_get($data, 'birth_date');
        $this->nationality = data_get($data, 'nationality');
        $this->phone = data_get($data, 'phone');
        $this->father_name = data_get($data, 'father_name');
        $this->mother_name = data_get($data, 'mother_name');
        $this->cpf = data_get($data, 'cpf');
        $this->rg = data_get($data, 'rg');
        $this->skin_color = data_get($data, 'skin_color');
        $this->relative_relation_type = data_get($data, 'relative_relation_type');
        $this->relative_name = data_get($data, 'relative_name');
        $this->relative_phone = data_get($data, 'relative_phone');
        $this->organization_id = $organization->id;
        $this->user_id = $user->id;
    }

    /**
     * Returns array of DTO attributes
     */
    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'birth_date' => $this->birth_date,
            'nationality' => $this->nationality,
            'phone' => $this->phone,
            'father_name' => $this->father_name,
            'mother_name' => $this->mother_name,
            'cpf' => $this->cpf,
            'rg' => $this->rg,
            'skin_color' => $this->skin_color,
            'relative_relation_type' => $this->relative_relation_type,
            'relative_name' => $this->relative_name,
            'relative_phone' => $this->relative_phone,
            'organization_id' => $this->organization_id,
            'user_id' => $this->user_id,
        ];
    }
}
