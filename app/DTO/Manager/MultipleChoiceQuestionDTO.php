<?php

namespace App\DTO\Manager;

class MultipleChoiceQuestionDTO
{
    public string $description;
    public bool $answer_required;
    public array $options;

    /**
     * @param array $data Os dados validados do FormRequest.
     */
    public function __construct(array $data)
    {
        $this->description = data_get($data, 'description');
        $this->answer_required = (bool) data_get($data, 'answer_required', false);
        $this->options = data_get($data, 'options', []);
    }

    public function toArray(): array
    {
        return [
            'description' => $this->description,
            'answer_required' => $this->answer_required,
            'options' => $this->options,
        ];
    }
}