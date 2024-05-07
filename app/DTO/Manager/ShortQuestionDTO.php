<?php

namespace App\DTO\Manager;

class ShortQuestionDTO
{
    public string $description;

    public string $answer_required;

    /**
     * Construct class set DTO attributes
     */
    public function __construct(array $data)
    {
        $this->description = data_get($data, 'description');
        $this->answer_required = data_get($data, 'answer_required');
    }

    /**
     * Returns array of DTO attributes
     */
    public function toArray(): array
    {
        return [
            'description' => $this->description,
            'answer_required' => $this->answer_required,
        ];
    }
}
