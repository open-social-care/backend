<?php

namespace App\Http\Resources\Api\Admin;

use App\Enums\RolesEnum;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Return array of attributes
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'roles_selected' => to_select_by_enum($this->roles, RolesEnum::class),
            'organizations_selected' => to_select($this->organizations),
        ];
    }
}
