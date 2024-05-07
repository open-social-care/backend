<?php

namespace App\DTO\Manager;

class MultipleChoiceQuestionDTO
{
    public string $description;

    public string $answer_required;
    public array $multipleChoiceOptions;

    /**
     * Construct class set DTO attributes
     */
    public function __construct(array $data)
    {
        $this->description = data_get($data, 'description');
        $this->answer_required = data_get($data, 'answer_required');
        $this->setMultipleChoiceOptionsDto(data_get($data, 'multiple_choice_options', []));
    }

    private function setMultipleChoiceOptionsDto(array $multipleChoiceOptionsData): void
    {
        foreach ($multipleChoiceOptionsData as $multipleChoiceOption) {
            $this->multipleChoiceOptions[] = new MultipleChoiceOptionsDTO($multipleChoiceOption);
        }
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
