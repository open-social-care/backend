<?php

namespace App\Http\Resources\Api\Admin;

use App\Http\Resources\Api\Shared\PaginationResource;
use Illuminate\Http\Resources\Json\JsonResource;

class UserListResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'name' => $this->name,
            'email' => $this->email,

            'links' => [

            ]
        ];
    }
}
