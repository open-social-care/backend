<?php

namespace Tests\Unit\Models;

use App\Models\FormAnswer;
use App\Models\FormTemplate;
use App\Models\MultipleChoiceQuestion;
use App\Models\OrganizationFormTemplate;
use App\Models\ShortQuestion;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FormTemplateTest extends TestCase
{
    use RefreshDatabase;

    public function testFormTemplateHasManyFormAnswers()
    {
        $formTemplate = FormTemplate::factory()->createOneQuietly();
        $formAnswer1 = FormAnswer::factory()->for($formTemplate)->createOneQuietly();
        $formAnswer2 = FormAnswer::factory()->for($formTemplate)->createOneQuietly();

        $this->assertTrue($formTemplate->formAnswers->contains($formAnswer1));
        $this->assertTrue($formTemplate->formAnswers->contains($formAnswer2));
    }

    public function testFormTemplateHasManyOrganizationFormTemplates()
    {
        $formTemplate = FormTemplate::factory()->createOneQuietly();
        $organizationFormTemplate1 = OrganizationFormTemplate::factory()->for($formTemplate)->createOneQuietly();
        $organizationFormTemplate2 = OrganizationFormTemplate::factory()->for($formTemplate)->createOneQuietly();

        $this->assertTrue($formTemplate->organizationFormTemplates->contains($organizationFormTemplate1));
        $this->assertTrue($formTemplate->organizationFormTemplates->contains($organizationFormTemplate2));
    }

    public function testFormTemplateHasManyShortQuestions()
    {
        $formTemplate = FormTemplate::factory()->createOneQuietly();
        $shortQuestion1 = ShortQuestion::factory()->for($formTemplate)->createOneQuietly();
        $shortQuestion2 = ShortQuestion::factory()->for($formTemplate)->createOneQuietly();

        $this->assertTrue($formTemplate->shortQuestions->contains($shortQuestion1));
        $this->assertTrue($formTemplate->shortQuestions->contains($shortQuestion2));
    }

    public function testFormTemplateHasManyMultipleChoiceQuestions()
    {
        $formTemplate = FormTemplate::factory()->createOneQuietly();
        $multipleChoiceQuestion1 = MultipleChoiceQuestion::factory()->for($formTemplate)->createOneQuietly();
        $multipleChoiceQuestion2 = MultipleChoiceQuestion::factory()->for($formTemplate)->createOneQuietly();

        $this->assertTrue($formTemplate->multipleChoiceQuestions->contains($multipleChoiceQuestion1));
        $this->assertTrue($formTemplate->multipleChoiceQuestions->contains($multipleChoiceQuestion2));
    }
}
