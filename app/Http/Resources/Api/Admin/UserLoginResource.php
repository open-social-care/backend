<?php

namespace App\Http\Resources\Api\Admin;

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
            'organizations_ids' => $this->organizations->pluck('id'),
            'subject_ref_by_organizations' => $this->organizations->pluck('subject_ref'),
        ];
    }
}
