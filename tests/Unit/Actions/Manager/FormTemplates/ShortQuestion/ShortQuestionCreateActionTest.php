<?php

namespace Tests\Unit\Actions\Manager\FormTemplates\ShortQuestion;

use App\Actions\Manager\FormTemplates\FormTemplateCreateAction;
use App\Actions\Manager\FormTemplates\ShortQuestions\ShortQuestionCreateAction;
use App\DTO\Manager\FormTemplateDTO;
use App\DTO\Manager\ShortQuestionDTO;
use App\Models\FormTemplate;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShortQuestionCreateActionTest extends TestCase
{
    use RefreshDatabase;

    public function testExecuteAction()
    {
        $formTemplate = FormTemplate::factory()->createOneQuietly();

        $shortQuestionDto = new ShortQuestionDTO([
            'description' => fake()->name,
            'answer_required' => fake()->boolean(),
        ]);

        ShortQuestionCreateAction::execute($shortQuestionDto, $formTemplate);

        $this->assertDatabaseHas('short_questions', ['description' => $shortQuestionDto->description]);
    }
}
