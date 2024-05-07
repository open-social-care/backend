<?php

namespace App\DTO\Manager;

class FormTemplateDTO
{
    public string $title;

    public string $description;

    public bool $has_file_uploads;

    public array $shortQuestions;

    public array $multipleChoiceQuestions;

    /**
     * Construct class set DTO attributes
     */
    public function __construct(array $data)
    {
        $this->title = data_get($data, 'title');
        $this->description = data_get($data, 'description');
        $this->has_file_uploads = data_get($data, 'has_file_uploads');
        $this->setShortQuestionsDto(data_get($data, 'short_questions', []));
        $this->setMultipleChoiceQuestionsDto(data_get($data, 'multiple_choice_questions', []));
    }

    private function setShortQuestionsDto(array $shortQuestionsData): void
    {
        foreach ($shortQuestionsData as $shortQuestion) {
            $this->shortQuestions[] = new ShortQuestionDTO($shortQuestion);
        }
    }

    private function setMultipleChoiceQuestionsDto(array $multipleChoiceQuestionsData): void
    {
        foreach ($multipleChoiceQuestionsData as $multipleChoiceQuestion) {
            $this->multipleChoiceQuestions[] = new MultipleChoiceQuestionDTO($multipleChoiceQuestion);
        }
    }

    /**
     * Returns array of DTO attributes
     */
    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'description' => $this->description,
            'has_file_uploads' => $this->has_file_uploads,
        ];
    }
}
