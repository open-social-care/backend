<?php

namespace Feature\Controllers\Api\SocialAssistant;

use App\Enums\RolesEnum;
use App\Models\FormTemplate;
use App\Models\Organization;
use App\Models\Role;
use App\Models\ShortQuestion;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response as HttpResponse;
use Tests\TestCase;

class SocialAssistantFormTemplateControllerTest extends TestCase
{
    use RefreshDatabase;

    protected Organization $organization;

    protected User $userSocialAssistant;

    private Role $roleSocialAssistant;

    private Role $roleManager;

    public function setUp(): void
    {
        parent::setUp();

        $this->organization = Organization::factory()->createQuietly();

        $this->userSocialAssistant = User::factory()->createQuietly();
        $this->roleSocialAssistant = Role::factory()->createQuietly(['name' => RolesEnum::SOCIAL_ASSISTANT->value]);
        $this->userSocialAssistant->roles()->attach($this->roleSocialAssistant);
        $this->userSocialAssistant->organizations()->attach($this->organization, ['role_id' => $this->roleSocialAssistant->id]);
        $this->actingAs($this->userSocialAssistant);

        $this->roleManager = Role::factory()->createQuietly(['name' => RolesEnum::MANAGER->value]);
    }

    public function testGetToSelectMethod()
    {
        $formTemplates = FormTemplate::factory()->hasAttached($this->organization)->count(10)->createQuietly();
        foreach ($formTemplates as $formTemplate) {
         ShortQuestion::factory()->createOne(['form_template_id' => $formTemplate->id]);
        }

        $response = $this->getJson(route('social-assistant.form-templates.get-to-select', $this->organization));

        $response->assertStatus(HttpResponse::HTTP_OK)
            ->assertJsonStructure(['data']);

        foreach ($formTemplates as $formTemplate) {
            $response->assertJsonFragment(['title' => $formTemplate->title]);
        }
    }

    public function testGetToSelectMethodWhenUserCantAccessInformation()
    {
        $user = User::factory()->createQuietly();
        $this->actingAs($user);

        $response = $this->getJson(route('social-assistant.form-templates.get-to-select', $this->organization));

        $response->assertStatus(HttpResponse::HTTP_FORBIDDEN);
    }

    public function testShowMethod()
    {
        $formTemplate = FormTemplate::factory()->hasAttached($this->organization)->createOneQuietly();

        $response = $this->getJson(route('social-assistant.form-templates.show', $formTemplate->id));

        $response->assertStatus(HttpResponse::HTTP_OK)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'title',
                    'description',
                    'short_questions' => [
                        '*' => [
                            'id',
                            'description',
                            'answer_required',
                        ],
                    ],
                ],
            ]);

        $response->assertJsonFragment(['title' => $formTemplate->title]);
    }

    public function testShowMethodWhenUserCantAccess()
    {
        $user = User::factory()->createQuietly();
        $this->actingAs($user);

        $formTemplate = FormTemplate::factory()->hasAttached($this->organization)->createOneQuietly();

        $response = $this->getJson(route('social-assistant.form-templates.show', $formTemplate->id));

        $response->assertStatus(HttpResponse::HTTP_FORBIDDEN);
    }
}
