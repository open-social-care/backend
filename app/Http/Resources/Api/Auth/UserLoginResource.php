<?php

namespace App\Http\Resources\Api\Auth;

use App\Http\Resources\Api\Shared\OrganizationResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserLoginResource extends JsonResource
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
            'roles_ids' => $this->roles->pluck('id'),
            'organizations' => OrganizationResource::collection($this->organizations)
        ];
    }
}
