<?php

namespace App\DTO\SocialAssistant;

use App\Models\FormAnswer;
use App\Models\Subject;

class ShortAnswerDTO
{
    public int $short_question_id;

    public int $form_answer_id;

    public int $subject_id;

    public ?string $answer;

    /**
     * Construct class set DTO attributes
     */
    public function __construct(array $data, FormAnswer $formAnswer, Subject $subject)
    {
        $this->short_question_id = data_get($data, 'short_question_id');
        $this->form_answer_id = $formAnswer->id;
        $this->subject_id = $subject->id;
        $this->answer = data_get($data, 'answer');
    }

    /**
     * Returns array of DTO attributes
     */
    public function toArray(): array
    {
        return [
            'short_question_id' => $this->short_question_id,
            'form_answer_id' => $this->form_answer_id,
            'subject_id' => $this->subject_id,
            'answer' => $this->answer,
        ];
    }
}
