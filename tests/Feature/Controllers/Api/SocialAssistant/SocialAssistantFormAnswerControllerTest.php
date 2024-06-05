<?php

namespace Feature\Controllers\Api\SocialAssistant;

use App\Enums\RolesEnum;
use App\Http\Resources\Api\SocialAssistant\FormAnswerResource;
use App\Models\FormAnswer;
use App\Models\FormTemplate;
use App\Models\Organization;
use App\Models\Role;
use App\Models\ShortAnswer;
use App\Models\ShortQuestion;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response as HttpResponse;
use Tests\TestCase;

class SocialAssistantFormAnswerControllerTest extends TestCase
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

    public function testIndexMethod()
    {
        $formTemplate = FormTemplate::factory()->hasAttached($this->organization)->createOneQuietly();

        $subject = Subject::factory()->for($this->organization)->for($this->userSocialAssistant)->createOneQuietly();
        $formAnswers = FormAnswer::factory()->count(5)
            ->for($formTemplate)
            ->for($subject)
            ->for($this->userSocialAssistant)
            ->createQuietly();

        $response = $this->getJson(route('social-assistant.form-answers.index', ['subject' => $subject->id]));

        $response->assertStatus(HttpResponse::HTTP_OK)
            ->assertJsonStructure(['data', 'pagination']);

        foreach ($formAnswers as $formAnswer) {
            $response->assertJsonFragment(['id' => $formAnswer->id]);
        }
    }

    public function testIndexMethodWhenUserCantAccessSubject()
    {
        $organization = Organization::factory()->createQuietly();
        $user = User::factory()->createQuietly();
        $roleSocialAssistant = Role::factory()->createQuietly(['name' => RolesEnum::SOCIAL_ASSISTANT->value]);
        $user->roles()->attach($roleSocialAssistant);
        $user->organizations()->attach($organization, ['role_id' => $roleSocialAssistant->id]);
        $this->actingAs($user);

        $subject = Subject::factory()->for($this->organization)->for($this->userSocialAssistant)->createOneQuietly();

        $response = $this->getJson(route('social-assistant.form-answers.index', ['subject' => $subject->id]));

        $response->assertStatus(HttpResponse::HTTP_FORBIDDEN);
    }

    public function testStoreMethod()
    {
        $formTemplate = FormTemplate::factory()->hasAttached($this->organization)->createOneQuietly();
        $shortQuestion = ShortQuestion::factory()->for($formTemplate)->createOneQuietly();
        $subject = Subject::factory()->for($this->organization)->for($this->userSocialAssistant)->createOneQuietly();

        $data = [
            'form_template_id' => $formTemplate->id,
            'short_answers' => [
                [
                    'short_question_id' => $shortQuestion->id,
                    'answer' => fake()->realText(),
                ],
            ],
        ];

        $response = $this->postJson(route('social-assistant.form-answers.store', ['subject' => $subject->id]), $data);

        $response->assertStatus(HttpResponse::HTTP_OK)
            ->assertJson(['message' => __('messages.common.success_create')]);

        $this->assertDatabaseHas('form_answers', ['form_template_id' => $formTemplate->id]);
    }

    public function testStoreMethodValidation()
    {
        $subject = Subject::factory()->for($this->organization)->for($this->userSocialAssistant)->createOneQuietly();
        $response = $this->postJson(route('social-assistant.form-answers.store', ['subject' => $subject->id]), []);

        $response->assertStatus(HttpResponse::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                'message',
                'errors' => [
                    'form_template_id',
                ],
            ]);
    }

    public function testStoreMethodWhenUserCantAccessSubject()
    {
        $organization = Organization::factory()->createQuietly();
        $user = User::factory()->createQuietly();
        $roleSocialAssistant = Role::factory()->createQuietly(['name' => RolesEnum::SOCIAL_ASSISTANT->value]);
        $user->roles()->attach($roleSocialAssistant);
        $user->organizations()->attach($organization, ['role_id' => $roleSocialAssistant->id]);
        $this->actingAs($user);

        $formTemplate = FormTemplate::factory()->hasAttached($this->organization)->createOneQuietly();
        $shortQuestion = ShortQuestion::factory()->for($formTemplate)->createOneQuietly();
        $subject = Subject::factory()->for($this->organization)->for($this->userSocialAssistant)->createOneQuietly();

        $data = [
            'form_template_id' => $formTemplate->id,
            'short_answers' => [
                [
                    'short_question_id' => $shortQuestion->id,
                    'answer' => fake()->realText(),
                ],
            ],
        ];

        $response = $this->postJson(route('social-assistant.form-answers.store', ['subject' => $subject->id]), $data);

        $response->assertStatus(HttpResponse::HTTP_FORBIDDEN);
    }

    public function testShowMethod()
    {
        $formTemplate = FormTemplate::factory()->hasAttached($this->organization)->createOneQuietly();
        $shortQuestion = ShortQuestion::factory()->for($formTemplate)->createOneQuietly();
        $subject = Subject::factory()->for($this->organization)->for($this->userSocialAssistant)->createOneQuietly();

        $formAnswer = FormAnswer::factory()
            ->for($formTemplate)
            ->for($subject)
            ->for($this->userSocialAssistant)
            ->createQuietly();

        ShortAnswer::factory()
            ->for($shortQuestion)
            ->for($formAnswer)
            ->for($subject)
            ->createOneQuietly();

        $response = $this->getJson(route('social-assistant.form-answers.show', $formAnswer->id));

        $response->assertStatus(HttpResponse::HTTP_OK);
        $expectedJson = FormAnswerResource::make($formAnswer)->jsonSerialize();
        $response->assertJsonFragment(['data' => $expectedJson]);
    }

    public function testShowMethodWhenUserCantAccess()
    {
        $organization = Organization::factory()->createQuietly();
        $user = User::factory()->createQuietly();
        $roleSocialAssistant = Role::factory()->createQuietly(['name' => RolesEnum::SOCIAL_ASSISTANT->value]);
        $user->roles()->attach($roleSocialAssistant);
        $user->organizations()->attach($organization, ['role_id' => $roleSocialAssistant->id]);
        $this->actingAs($user);

        $formTemplate = FormTemplate::factory()->hasAttached($this->organization)->createOneQuietly();
        $subject = Subject::factory()->for($this->organization)->for($this->userSocialAssistant)->createOneQuietly();
        $formAnswer = FormAnswer::factory()
            ->for($formTemplate)
            ->for($subject)
            ->for($this->userSocialAssistant)
            ->createQuietly();

        $response = $this->getJson(route('social-assistant.form-answers.show', $formAnswer->id));

        $response->assertStatus(HttpResponse::HTTP_FORBIDDEN);
    }
}
