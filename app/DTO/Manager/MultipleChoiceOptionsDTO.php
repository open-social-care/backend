<?php

namespace App\DTO\Manager;

class MultipleChoiceOptionsDTO
{
    public string $description;

    /**
     * Construct class set DTO attributes
     */
    public function __construct(array $data)
    {
        $this->description = data_get($data, 'description');
    }

    /**
     * Returns array of DTO attributes
     */
    public function toArray(): array
    {
        return [
            'description' => $this->description,
        ];
    }
}
