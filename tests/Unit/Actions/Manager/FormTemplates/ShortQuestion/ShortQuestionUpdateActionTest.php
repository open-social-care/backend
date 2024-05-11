<?php

namespace Tests\Unit\Actions\Manager\FormTemplates;

use App\Actions\Manager\FormTemplates\FormTemplateUpdateAction;
use App\Actions\Manager\FormTemplates\ShortQuestions\ShortQuestionUpdateAction;
use App\DTO\Manager\FormTemplateDTO;
use App\DTO\Manager\ShortQuestionDTO;
use App\Models\FormTemplate;
use App\Models\ShortQuestion;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShortQuestionUpdateActionTest extends TestCase
{
    use RefreshDatabase;

    public function testExecuteAction()
    {
        $formTemplate = FormTemplate::factory()->createOneQuietly();
        $shortQuestion = ShortQuestion::factory()->for($formTemplate)->createOneQuietly();

        $shortQuestionDto = new ShortQuestionDTO([
            'description' => fake()->name,
            'answer_required' => true,
        ]);

        $descriptionBeforeUpdated = $shortQuestion->description;
        ShortQuestionUpdateAction::execute($shortQuestionDto, $shortQuestion);

        $shortQuestion = $shortQuestion->fresh();

        $this->assertNotEquals($shortQuestion->description, $descriptionBeforeUpdated);
        $this->assertDatabaseHas('short_questions', ['description' => $shortQuestionDto->description]);
    }
}
