<?php

namespace Feature\Controllers\Api\Manager;

use App\Enums\RolesEnum;
use App\Models\FormTemplate;
use App\Models\Organization;
use App\Models\Role;
use App\Models\ShortQuestion;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response as HttpResponse;
use Tests\TestCase;

class ManagerFormTemplateShortQuestionControllerTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $userManager = User::factory()->createQuietly();
        $role = Role::factory()->createQuietly(['name' => RolesEnum::MANAGER->value]);
        $organization = Organization::factory()->createQuietly();
        $userManager->roles()->attach($role);
        $userManager->organizations()->attach($organization, ['role_id' => $role->id]);
        $this->actingAs($userManager);
    }

    public function testIndexMethod()
    {
        $formTemplate = FormTemplate::factory()->createOneQuietly();
        $shortQuestions = ShortQuestion::factory()->count(5)->for($formTemplate)->createQuietly();

        $response = $this->getJson(route('manager.form-templates.short-questions.index',
            [
                'form_template' => $formTemplate->id,
            ]));

        $response->assertStatus(HttpResponse::HTTP_OK)
            ->assertJsonStructure(['data']);

        foreach ($shortQuestions as $shortQuestion) {
            $response->assertJsonFragment(['id' => $shortQuestion->id]);
        }
    }

    public function testIndexMethodWhenUserCantAccessInformation()
    {
        $user = User::factory()->createQuietly();
        $this->actingAs($user);

        $formTemplate = FormTemplate::factory()->createOneQuietly();
        $response = $this->getJson(route('manager.form-templates.short-questions.index',
            [
                'form_template' => $formTemplate->id,
            ]));

        $response->assertStatus(HttpResponse::HTTP_FORBIDDEN);
    }

    public function testStoreMethod()
    {
        $formTemplate = FormTemplate::factory()->createOneQuietly();

        $data = [
            'description' => 'Age?',
            'answer_required' => true,
        ];

        $response = $this->postJson(route('manager.form-templates.short-questions.store',
            [
                'form_template' => $formTemplate->id,
            ]), $data);

        $response->assertStatus(HttpResponse::HTTP_OK)
            ->assertJson(['message' => __('messages.common.success_create')]);

        $this->assertDatabaseHas('short_questions', ['description' => 'Age?']);
    }

    public function testStoreMethodValidation()
    {
        $formTemplate = FormTemplate::factory()->createOneQuietly();

        $response = $this->postJson(route('manager.form-templates.short-questions.store',
            [
                'form_template' => $formTemplate->id,
            ]), []);

        $response->assertStatus(HttpResponse::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                'message',
                'errors' => [
                    'description',
                    'answer_required',
                ],
            ]);
    }

    public function testStoreMethodWhenUserCantAccessInformation()
    {
        $user = User::factory()->createQuietly();
        $this->actingAs($user);

        $formTemplate = FormTemplate::factory()->createOneQuietly();

        $data = [
            'description' => 'Age?',
            'answer_required' => true,
        ];

        $response = $this->postJson(route('manager.form-templates.short-questions.store',
            [
                'form_template' => $formTemplate->id,
            ]), $data);

        $response->assertStatus(HttpResponse::HTTP_FORBIDDEN);
    }

    public function testUpdateMethod()
    {
        $formTemplate = FormTemplate::factory()->createOneQuietly();
        $shortQuestion = ShortQuestion::factory()->for($formTemplate)->createOneQuietly();

        $updatedData = [
            'description' => 'How old are you?',
            'answer_required' => true,
        ];

        $response = $this->putJson(route('manager.form-templates.short-questions.update',
            [
                'form_template' => $formTemplate->id,
                'short_question' => $shortQuestion->id,
            ]), $updatedData);

        $response->assertStatus(HttpResponse::HTTP_OK)
            ->assertJson(['message' => __('messages.common.success_update')]);

        $shortQuestion = $shortQuestion->fresh();
        $this->assertEquals($updatedData['description'], $shortQuestion->description);
    }

    public function testUpdateMethodValidation()
    {
        $formTemplate = FormTemplate::factory()->createOneQuietly();
        $shortQuestion = ShortQuestion::factory()->for($formTemplate)->createOneQuietly();

        $response = $this->putJson(route('manager.form-templates.short-questions.update',
            [
                'form_template' => $formTemplate->id,
                'short_question' => $shortQuestion->id,
            ]), []);

        $response->assertStatus(HttpResponse::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                'message',
                'errors' => [
                    'description',
                ],
            ]);
    }

    public function testUpdateMethodWhenUserCantAccessInformation()
    {
        $user = User::factory()->createQuietly();
        $this->actingAs($user);

        $formTemplate = FormTemplate::factory()->createOneQuietly();
        $shortQuestion = ShortQuestion::factory()->for($formTemplate)->createOneQuietly();

        $updatedData = [
            'description' => 'How old are you?',
            'answer_required' => true,
        ];

        $response = $this->putJson(route('manager.form-templates.short-questions.update',
            [
                'form_template' => $formTemplate->id,
                'short_question' => $shortQuestion->id,
            ]), $updatedData);

        $response->assertStatus(HttpResponse::HTTP_FORBIDDEN);
    }

    public function testDestroyMethod()
    {
        $formTemplate = FormTemplate::factory()->createOneQuietly();
        $shortQuestion = ShortQuestion::factory()->for($formTemplate)->createOneQuietly();

        $response = $this->deleteJson(route('manager.form-templates.short-questions.destroy',
            [
                'form_template' => $formTemplate->id,
                'short_question' => $shortQuestion->id,
            ]));

        $response->assertStatus(HttpResponse::HTTP_OK)
            ->assertJson(['message' => __('messages.common.success_destroy')]);

        $this->assertSoftDeleted('short_questions', ['id' => $shortQuestion->id]);
    }

    public function testDestroyMethodWithInvalidUser()
    {
        $response = $this->deleteJson(route('manager.form-templates.short-questions.destroy',
            [
                'form_template' => 0,
                'short_question' => 0,
            ]));

        $response->assertStatus(HttpResponse::HTTP_NOT_FOUND)
            ->assertJsonStructure(['message']);
    }

    public function testDestroyMethodWhenUserCantAccessInformation()
    {
        $user = User::factory()->createQuietly();
        $this->actingAs($user);

        $formTemplate = FormTemplate::factory()->createOneQuietly();
        $shortQuestion = ShortQuestion::factory()->for($formTemplate)->createOneQuietly();

        $response = $this->deleteJson(route('manager.form-templates.short-questions.destroy',
            [
                'form_template' => $formTemplate->id,
                'short_question' => $shortQuestion->id,
            ]));

        $response->assertStatus(HttpResponse::HTTP_FORBIDDEN);
    }

    public function testShowMethod()
    {
        $formTemplate = FormTemplate::factory()->createOneQuietly();
        $shortQuestion = ShortQuestion::factory()->for($formTemplate)->createOneQuietly();

        $response = $this->getJson(route('manager.form-templates.short-questions.show',
            [
                'form_template' => $formTemplate->id,
                'short_question' => $shortQuestion->id,
            ]));

        $response->assertStatus(HttpResponse::HTTP_OK)
            ->assertJsonStructure(['data']);

        $response->assertJsonFragment(['description' => $shortQuestion->description]);
    }

    public function testShowMethodWhenUserCantAccess()
    {
        $user = User::factory()->createQuietly();
        $this->actingAs($user);

        $formTemplate = FormTemplate::factory()->createOneQuietly();
        $shortQuestion = ShortQuestion::factory()->for($formTemplate)->createOneQuietly();

        $response = $this->getJson(route('manager.form-templates.short-questions.show',
            [
                'form_template' => $formTemplate->id,
                'short_question' => $shortQuestion->id,
            ]));

        $response->assertStatus(HttpResponse::HTTP_FORBIDDEN);
    }
}
