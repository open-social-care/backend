<?php

namespace Tests\Unit\Models;

use App\Models\FormTemplate;
use App\Models\MultipleChoiceAnswer;
use App\Models\MultipleChoiceOption;
use App\Models\MultipleChoiceQuestion;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MultipleChoiceQuestionTest extends TestCase
{
    use RefreshDatabase;

    public function testMultipleChoiceQuestionBelongsToFormTemplate()
    {
        $formTemplate = FormTemplate::factory()->createOneQuietly();
        $multipleChoiceQuestion = MultipleChoiceQuestion::factory()->for($formTemplate)->createOneQuietly();

        $this->assertEquals($formTemplate->id, $multipleChoiceQuestion->form_template_id);
    }

    public function testMultipleChoiceQuestionHasManyMultipleChoiceOptions()
    {
        $multipleChoiceQuestion = MultipleChoiceQuestion::factory()->createOneQuietly();
        $multipleChoiceOption1 = MultipleChoiceOption::factory()->for($multipleChoiceQuestion)->createOneQuietly();
        $multipleChoiceOption2 = MultipleChoiceOption::factory()->for($multipleChoiceQuestion)->createOneQuietly();

        $this->assertTrue($multipleChoiceQuestion->multipleChoiceOptions->contains($multipleChoiceOption1));
        $this->assertTrue($multipleChoiceQuestion->multipleChoiceOptions->contains($multipleChoiceOption2));
    }

    public function testMultipleChoiceQuestionHasManyMultipleChoiceAnswers()
    {
        $multipleChoiceQuestion = MultipleChoiceQuestion::factory()->createOneQuietly();
        $multipleChoiceAnswer1 = MultipleChoiceAnswer::factory()->for($multipleChoiceQuestion)->createOneQuietly();
        $multipleChoiceAnswer2 = MultipleChoiceAnswer::factory()->for($multipleChoiceQuestion)->createOneQuietly();

        $this->assertTrue($multipleChoiceQuestion->multipleChoiceAnswers->contains($multipleChoiceAnswer1));
        $this->assertTrue($multipleChoiceQuestion->multipleChoiceAnswers->contains($multipleChoiceAnswer2));
    }
}
