<?php

namespace App\Http\Resources\Api\Shared;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AddressResource extends JsonResource
{
    /**
     * Return array of attributes
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'model_type' => $this->model_type,
            'model_id' => $this->model_id,
            'street' => $this->street,
            'number' => $this->number,
            'district' => $this->district,
            'complement' => $this->complement,
            'state_id' => $this->state_id,
            'city_id' => $this->city_id,
        ];
    }
}
