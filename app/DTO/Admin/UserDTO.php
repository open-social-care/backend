<?php

namespace App\DTO\Admin;

class UserDTO
{
    public string $name;

    public string $email;

    public string|null $password;

    public array $roles;

    public array $organizations;

    /**
     * Construct class set DTO attributes
     */
    public function __construct(array $data)
    {
        $this->name = data_get($data, 'name');
        $this->email = data_get($data, 'email');
        $this->password = data_get($data, 'password');
        $this->roles = data_get($data, 'roles', []);
        $this->organizations = data_get($data, 'organizations', []);
    }

    /**
     * Returns array of DTO attributes
     */
    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'email' => $this->email,
            'password' => $this->password,
            'roles' => $this->roles,
            'organizations' => $this->organizations,
        ];
    }
}
