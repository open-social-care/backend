<?php

namespace Tests\Unit\Models;

use App\Models\FormAnswer;
use App\Models\Subject;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SubjectTest extends TestCase
{
    use RefreshDatabase;

    public function testShortQuestionHasManyFormAnswer()
    {
        $subject = Subject::factory()->createOneQuietly();
        $formAnswer1 = FormAnswer::factory()->for($subject)->createOneQuietly();
        $formAnswer2 = FormAnswer::factory()->for($subject)->createOneQuietly();

        $this->assertTrue($subject->formAnswers->contains($formAnswer1));
        $this->assertTrue($subject->formAnswers->contains($formAnswer2));
    }
}
