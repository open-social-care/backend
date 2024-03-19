<?php

namespace App\DTO\Admin;

class UserDTO
{
    public string $name;

    public string $email;

    public ?string $password;

    /**
     * Construct class set DTO attributes
     */
    public function __construct(array $data)
    {
        $this->name = data_get($data, 'name');
        $this->email = data_get($data, 'email');
        $this->password = data_get($data, 'password');
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
        ];
    }
}
