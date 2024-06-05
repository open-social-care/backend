<?php

namespace Tests\Unit\Actions\SocialAssistant\FormAnswer;

use App\Actions\SocialAssistant\FormAnswer\FormAnswerCreateAction;
use App\DTO\SocialAssistant\FormAnswerDTO;
use App\Enums\RolesEnum;
use App\Models\FormTemplate;
use App\Models\Organization;
use App\Models\Role;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FormAnswerCreateActionTest extends TestCase
{
    use RefreshDatabase;

    protected Organization $organization;

    protected User $userSocialAssistant;

    public function setUp(): void
    {
        parent::setUp();

        $this->organization = Organization::factory()->createQuietly();

        $this->userSocialAssistant = User::factory()->createQuietly();
        $roleSocialAssistant = Role::factory()->createQuietly(['name' => RolesEnum::SOCIAL_ASSISTANT->value]);
        $this->userSocialAssistant->roles()->attach($roleSocialAssistant);
        $this->userSocialAssistant->organizations()->attach($this->organization, ['role_id' => $roleSocialAssistant->id]);
        $this->actingAs($this->userSocialAssistant);
    }

    public function testExecuteAction()
    {
        $formTemplate = FormTemplate::factory()->hasAttached($this->organization)->createOneQuietly();
        $subject = Subject::factory()->for($this->organization)->for($this->userSocialAssistant)->createOneQuietly();

        $data = [
            'form_template_id' => $formTemplate->id,
        ];

        $dto = new FormAnswerDTO($data, $subject, $this->userSocialAssistant);

        $formTemplate = FormAnswerCreateAction::execute($dto);

        $this->assertDatabaseHas('form_answers', ['id' => $formTemplate->id]);
    }
}
