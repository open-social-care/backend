<?php

namespace Tests\Unit\Models;

use App\Models\MultipleChoiceAnswer;
use App\Models\MultipleChoiceQuestion;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MultipleChoiceOptionTest extends TestCase
{
    use RefreshDatabase;

    public function testMultipleChoiceAnswerBelongsToMultipleChoiceQuestion()
    {
        $multipleChoiceQuestion = MultipleChoiceQuestion::factory()->createOneQuietly();
        $multipleChoiceOption = MultipleChoiceAnswer::factory()->for($multipleChoiceQuestion)->createOneQuietly();

        $this->assertEquals($multipleChoiceQuestion->id, $multipleChoiceOption->multiple_choice_question_id);
    }
}
