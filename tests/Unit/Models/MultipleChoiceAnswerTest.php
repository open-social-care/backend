<?php

namespace Tests\Unit\Models;

use App\Models\FormAnswer;
use App\Models\MultipleChoiceAnswer;
use App\Models\MultipleChoiceQuestion;
use App\Models\Subject;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MultipleChoiceAnswerTest extends TestCase
{
    use RefreshDatabase;

    public function testMultipleChoiceAnswerBelongsToFormAnswer()
    {
        $formAnswer = FormAnswer::factory()->createOneQuietly();
        $multipleChoiceAnswer = MultipleChoiceAnswer::factory()->for($formAnswer)->createOneQuietly();

        $this->assertEquals($formAnswer->id, $multipleChoiceAnswer->form_answer_id);
    }

    public function testMultipleChoiceAnswerBelongsToMultipleChoiceQuestion()
    {
        $multipleChoiceQuestion = MultipleChoiceQuestion::factory()->createOneQuietly();
        $multipleChoiceAnswer = MultipleChoiceAnswer::factory()->for($multipleChoiceQuestion)->createOneQuietly();

        $this->assertEquals($multipleChoiceQuestion->id, $multipleChoiceAnswer->multiple_choice_question_id);
    }

    public function testMultipleChoiceAnswerBelongsToSubject()
    {
        $subject = Subject::factory()->createOneQuietly();
        $multipleChoiceAnswer = MultipleChoiceAnswer::factory()->for($subject)->createOneQuietly();

        $this->assertEquals($subject->id, $multipleChoiceAnswer->subject_id);
    }
}
