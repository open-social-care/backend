<?php

namespace Tests\Unit\Models;

use App\Models\FormAnswer;
use App\Models\ShortAnswer;
use App\Models\ShortQuestion;
use App\Models\Subject;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShortAnswerTest extends TestCase
{
    use RefreshDatabase;

    public function testShortAnswerBelongsToShortQuestion()
    {
        $shortQuestion = ShortQuestion::factory()->createOneQuietly();
        $shortAnswer = ShortAnswer::factory()->for($shortQuestion)->createOneQuietly();

        $this->assertEquals($shortQuestion->id, $shortAnswer->short_question_id);
    }

    public function testShortAnswerBelongsToFormAnswer()
    {
        $formAnswer = FormAnswer::factory()->createOneQuietly();
        $shortAnswer = ShortAnswer::factory()->for($formAnswer)->createOneQuietly();

        $this->assertEquals($formAnswer->id, $shortAnswer->form_answer_id);
    }

    public function testShortAnswerBelongsToSubject()
    {
        $subject = Subject::factory()->createOneQuietly();
        $shortAnswer = ShortAnswer::factory()->for($subject)->createOneQuietly();

        $this->assertEquals($subject->id, $shortAnswer->subject_id);
    }
}
