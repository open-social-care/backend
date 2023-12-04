<?php

namespace App\Http\Resources\Api\Admin;

use App\Enums\RolesEnum;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;

class UserListResource extends JsonResource
{
    /**
     * Return array of attributes
     *
     * @param Request $request
     * @return array
     */
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->name,
            'email' => $this->email,
            'roles' => $this->getRoles(),

            'links' => [

            ]
        ];
    }

    /**
     * Return array of translated roles name
     *
     * @return array
     */
    private function getRoles(): array
    {
        $this->load('roles');
        $roles = $this->roles->pluck('name');

        $data = [];
        foreach ($roles as $role) {
            $data[] = RolesEnum::trans($role);
        }

        return $data;
    }
}
