<?php

namespace App\DTO\Shared;

use Illuminate\Database\Eloquent\Model;

class AddressDTO
{
    public string $model_type;

    public string $model_id;

    public string $street;

    public string $number;

    public string $district;

    public ?string $complement;

    public int $state_id;

    public int $city_id;

    /**
     * Construct class set DTO attributes
     */
    public function __construct(array $data, Model $model)
    {
        $this->model_type = get_class($model);
        $this->model_id = $model->id;
        $this->street = data_get($data, 'street');
        $this->number = data_get($data, 'number');
        $this->district = data_get($data, 'district');
        $this->complement = data_get($data, 'complement');
        $this->state_id = data_get($data, 'state_id');
        $this->city_id = data_get($data, 'city_id');
    }

    /**
     * Returns array of DTO attributes
     */
    public function toArray(): array
    {
        return [
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
