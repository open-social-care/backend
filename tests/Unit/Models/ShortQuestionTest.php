<?php

namespace Tests\Unit\Models;

use App\Models\FormTemplate;
use App\Models\ShortAnswer;
use App\Models\ShortQuestion;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShortQuestionTest extends TestCase
{
    use RefreshDatabase;

    public function testShortQuestionBelongsToFormTemplate()
    {
        $formTemplate = FormTemplate::factory()->createOneQuietly();
        $shortQuestion = ShortQuestion::factory()->for($formTemplate)->createOneQuietly();

        $this->assertEquals($formTemplate->id, $shortQuestion->form_template_id);
    }

    public function testShortQuestionHasManyShortAnswers()
    {
        $shortQuestion = ShortQuestion::factory()->createOneQuietly();
        $shortAnswer1 = ShortAnswer::factory()->for($shortQuestion)->createOneQuietly();
        $shortAnswer2 = ShortAnswer::factory()->for($shortQuestion)->createOneQuietly();

        $this->assertTrue($shortQuestion->shortAnswers->contains($shortAnswer1));
        $this->assertTrue($shortQuestion->shortAnswers->contains($shortAnswer2));
    }
}
