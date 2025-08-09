<?php
namespace App\DTO\Manager;

use App\Models\FormAnswer;
use App\Models\Subject;
use App\Models\MultipleChoiceQuestion;

class MultipleChoiceAnswerDTO
{
    public int $multiple_choice_question_id;
    public int $form_answer_id;
    public int $subject_id;
    public string $answer;
    public ?string $question_description;

    public function __construct(array $data, FormAnswer $formAnswer, Subject $subject)
    {
        $this->multiple_choice_question_id = data_get($data, 'multiple_choice_question_id');
        $this->form_answer_id = $formAnswer->id;
        $this->subject_id = $subject->id;
        $this->answer = data_get($data, 'answer');

        $question = MultipleChoiceQuestion::find($this->multiple_choice_question_id);
        $this->question_description = $question ? $question->description : null;
    }

    public function toArray(): array
    {
        return [
            'multiple_choice_question_id' => $this->multiple_choice_question_id,
            'form_answer_id' => $this->form_answer_id,
            'subject_id' => $this->subject_id,
            'answer' => $this->answer,
            'question_description' => $this->question_description,
        ];
    }
}
