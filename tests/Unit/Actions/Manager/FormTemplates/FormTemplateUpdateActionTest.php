<?php

namespace Tests\Unit\Actions\Manager\FormTemplates;

use App\Actions\Manager\FormTemplates\FormTemplateUpdateAction;
use App\DTO\Manager\FormTemplateDTO;
use App\Models\FormTemplate;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FormTemplateUpdateActionTest extends TestCase
{
    use RefreshDatabase;

    public function testExecuteAction()
    {
        $formTemplate = FormTemplate::factory()->createOneQuietly();

        $formTemplateDto = new FormTemplateDTO([
            'title' => fake()->name,
            'description' => $formTemplate->description,
        ]);

        $formTemplateTitleBeforeUpdated = $formTemplate->title;
        FormTemplateUpdateAction::execute($formTemplateDto, $formTemplate);

        $formTemplate = $formTemplate->fresh();

        $this->assertNotEquals($formTemplate->title, $formTemplateTitleBeforeUpdated);
        $this->assertDatabaseHas('form_templates', ['title' => $formTemplateDto->title]);
    }
}
