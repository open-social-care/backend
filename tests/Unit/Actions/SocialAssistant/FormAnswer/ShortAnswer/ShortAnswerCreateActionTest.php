<?php

namespace Tests\Unit\Actions\SocialAssistant\FormAnswer\ShortAnswer;

use App\Actions\SocialAssistant\FormAnswer\ShortAnswer\ShortAnswerCreateAction;
use App\DTO\SocialAssistant\ShortAnswerDTO;
use App\Enums\RolesEnum;
use App\Models\FormAnswer;
use App\Models\FormTemplate;
use App\Models\Organization;
use App\Models\Role;
use App\Models\ShortQuestion;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShortAnswerCreateActionTest extends TestCase
{
    use RefreshDatabase;

    protected Organization $organization;

    protected User $userSocialAssistant;

    public function testExecuteAction()
    {
        $this->createSocialAssistantUser();

        $formTemplate = FormTemplate::factory()->hasAttached($this->organization)->createOneQuietly();
        $shortQuestion = ShortQuestion::factory()->for($formTemplate)->createOneQuietly();
        $subject = Subject::factory()->for($this->organization)->for($this->userSocialAssistant)->createOneQuietly();

        $formAnswer = FormAnswer::factory()
            ->for($formTemplate)
            ->for($subject)
            ->for($this->userSocialAssistant)
            ->createQuietly();

        $data = [
            'short_question_id' => $shortQuestion->id,
            'answer' => fake()->realText(),
        ];

        $dto = new ShortAnswerDTO($data, $formAnswer, $subject);

        $shortAnswer = ShortAnswerCreateAction::execute($dto);

        $this->assertDatabaseHas('short_answers', ['id' => $shortAnswer->id]);
    }

    public function createSocialAssistantUser(): void
    {
        $this->organization = Organization::factory()->createQuietly();

        $this->userSocialAssistant = User::factory()->createQuietly();
        $roleSocialAssistant = Role::factory()->createQuietly(['name' => RolesEnum::SOCIAL_ASSISTANT->value]);
        $this->userSocialAssistant->roles()->attach($roleSocialAssistant);
        $this->userSocialAssistant->organizations()->attach($this->organization, ['role_id' => $roleSocialAssistant->id]);
        $this->actingAs($this->userSocialAssistant);
    }
}
