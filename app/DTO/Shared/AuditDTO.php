<?php

namespace App\DTO\Shared;

class AuditDTO
{
    public string $model_type;
    public int $model_id;
    public int $user_id;
    public ?string $event_context;
    public string $event_type;
    public ?array $data;
    public string $ip_address;

    /**
     * Construct class set DTO attributes
     */
    public function __construct(array $data)
    {
        $this->model_type = data_get($data, 'model_type');
        $this->model_id = data_get($data, 'model_id');
        $this->user_id = data_get($data, 'user_id');
        $this->event_context = data_get($data, 'event_context');
        $this->event_type = data_get($data, 'event_type');
        $this->data = data_get($data, 'data');
        $this->ip_address = data_get($data, 'ip_address');
    }

    /**
     * Returns array of DTO attributes
     */
    public function toArray(): array
    {
        return [
            'model_type' => $this->model_type,
            'model_id' => $this->model_id,
            'user_id' => $this->user_id,
            'event_context' => $this->event_context,
            'event_type' => $this->event_type,
            'data' => $this->data,
            'ip_address' => $this->ip_address,
        ];
    }
}
