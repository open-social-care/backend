<?php

namespace App\Http\Resources\Api\SocialAssistant;

use App\Http\Resources\Api\Shared\AddressResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SubjectResource extends JsonResource
{
    /**
     * Return array of attributes
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
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
            'addresses' => AddressResource::collection($this->addresses),
        ];
    }
}
