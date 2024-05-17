<?php

namespace App\DTO\Manager;

class FormTemplateDTO
{
    public string $title;

    public string $description;

    /**
     * Construct class set DTO attributes
     */
    public function __construct(array $data)
    {
        $this->title = data_get($data, 'title');
        $this->description = data_get($data, 'description');
    }

    /**
     * Returns array of DTO attributes
     */
    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'description' => $this->description,
        ];
    }
}
