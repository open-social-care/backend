<?php

namespace Tests\Unit\Actions\Manager\FormTemplates;

use App\Actions\Manager\FormTemplates\FormTemplateCreateAction;
use App\DTO\Manager\FormTemplateDTO;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FormTemplateCreateActionTest extends TestCase
{
    use RefreshDatabase;

    public function testExecuteAction()
    {
        $formTemplateDto = new FormTemplateDTO([
            'title' => fake()->name,
            'description' => fake()->name,
        ]);

        FormTemplateCreateAction::execute($formTemplateDto);

        $this->assertDatabaseHas('form_templates', ['title' => $formTemplateDto->title]);
    }
}
