<?php

namespace Feature\Controllers\Api\Manager;

use App\Enums\RolesEnum;
use App\Models\FormTemplate;
use App\Models\Organization;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response as HttpResponse;
use Tests\TestCase;

class ManagerFormTemplateControllerTest extends TestCase
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
        $formTemplates = FormTemplate::factory()->count(10)->createQuietly();

        $response = $this->getJson(route('manager.form-templates.index'));

        $response->assertStatus(HttpResponse::HTTP_OK)
            ->assertJsonStructure(['data', 'pagination']);

        foreach ($formTemplates as $formTemplate) {
            $response->assertJsonFragment(['title' => $formTemplate->title]);
        }
    }

    public function testIndexMethodWithSearchTerm()
    {
        FormTemplate::factory()->count(10)->createQuietly();
        $formTemplate = FormTemplate::factory()->createOneQuietly(['title' => 'Test search']);

        $response = $this->getJson(route('manager.form-templates.index', ['q' => 'Test search']));

        $response->assertStatus(HttpResponse::HTTP_OK)
            ->assertJsonStructure(['data', 'pagination'])
            ->assertJsonFragment(['title' => $formTemplate->title]);
    }

    public function testIndexMethodWithSearchTermWhenDontHaveContent()
    {
        FormTemplate::factory()->count(10)->createQuietly();
        $formTemplate = FormTemplate::factory()->createOneQuietly(['title' => 'Test form template']);

        $response = $this->getJson(route('manager.form-templates.index', ['q' => 'null form template']));

        $response->assertStatus(HttpResponse::HTTP_OK)
            ->assertJsonStructure(['data', 'pagination'])
            ->assertJsonCount(0, 'data')
            ->assertJsonMissing(['title' => $formTemplate->title]);
    }

    public function testIndexMethodWhenUserCantAccessInformation()
    {
        $user = User::factory()->createQuietly();
        $this->actingAs($user);

        $response = $this->getJson(route('manager.form-templates.index'));

        $response->assertStatus(HttpResponse::HTTP_FORBIDDEN);
    }

    public function testStoreMethod()
    {
        $data = [
            'title' => 'Test forms',
            'description' => 'Default',
        ];

        $response = $this->postJson(route('manager.form-templates.store'), $data);

        $response->assertStatus(HttpResponse::HTTP_OK)
            ->assertJson(['message' => __('messages.common.success_create')]);

        $this->assertDatabaseHas('form_templates', ['title' => 'Test forms']);
    }

    public function testStoreMethodValidation()
    {
        $response = $this->postJson(route('manager.form-templates.store'), []);

        $response->assertStatus(HttpResponse::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                'message',
                'errors' => [
                    'title',
                    'description',
                ],
            ]);
    }

    public function testStoreMethodWhenUserCantAccessInformation()
    {
        $user = User::factory()->createQuietly();
        $this->actingAs($user);

        $data = [
            'title' => 'Test forms',
            'description' => 'Default',
        ];

        $response = $this->postJson(route('manager.form-templates.store'), $data);

        $response->assertStatus(HttpResponse::HTTP_FORBIDDEN);
    }

    public function testUpdateMethod()
    {
        $formTemplate = FormTemplate::factory()->createOneQuietly();

        $updatedData = [
            'title' => 'Test forms update',
            'description' => 'Default',
        ];

        $response = $this->putJson(route('manager.form-templates.update', $formTemplate->id), $updatedData);

        $response->assertStatus(HttpResponse::HTTP_OK)
            ->assertJson(['message' => __('messages.common.success_update')]);

        $formTemplate = $formTemplate->fresh();
        $this->assertEquals($updatedData['title'], $formTemplate->title);
    }

    public function testUpdateMethodValidation()
    {
        $formTemplate = FormTemplate::factory()->createOneQuietly();

        $response = $this->putJson(route('manager.form-templates.update', $formTemplate->id), []);
        $response->assertStatus(HttpResponse::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                'message',
                'errors' => [
                    'title',
                ],
            ]);
    }

    public function testUpdateMethodWhenUserCantAccessInformation()
    {
        $user = User::factory()->createQuietly();
        $this->actingAs($user);

        $formTemplate = FormTemplate::factory()->createOneQuietly();

        $updatedData = [
            'title' => 'Test forms update',
            'description' => 'Default',
        ];

        $response = $this->putJson(route('manager.form-templates.update', $formTemplate->id), $updatedData);

        $response->assertStatus(HttpResponse::HTTP_FORBIDDEN);
    }

    public function testDestroyMethod()
    {
        $formTemplate = FormTemplate::factory()->createOneQuietly();

        $response = $this->deleteJson(route('manager.form-templates.destroy', $formTemplate->id));

        $response->assertStatus(HttpResponse::HTTP_OK)
            ->assertJson(['message' => __('messages.common.success_destroy')]);

        $this->assertSoftDeleted('form_templates', ['id' => $formTemplate->id]);
    }

    public function testDestroyMethodWithInvalidUser()
    {
        $response = $this->deleteJson(route('manager.form-templates.destroy', 0));

        $response->assertStatus(HttpResponse::HTTP_NOT_FOUND)
            ->assertJsonStructure(['message']);
    }

    public function testDestroyMethodWhenUserCantAccessInformation()
    {
        $user = User::factory()->createQuietly();
        $this->actingAs($user);

        $formTemplate = FormTemplate::factory()->createOneQuietly();

        $response = $this->deleteJson(route('manager.form-templates.destroy', $formTemplate->id));

        $response->assertStatus(HttpResponse::HTTP_FORBIDDEN);
    }

    public function testShowMethod()
    {
        $formTemplate = FormTemplate::factory()->createOneQuietly();

        $response = $this->getJson(route('manager.form-templates.show', $formTemplate->id));

        $response->assertStatus(HttpResponse::HTTP_OK)
            ->assertJsonStructure(['data']);

        $response->assertJsonFragment(['title' => $formTemplate->title]);
    }

    public function testShowMethodWhenUserCantAccess()
    {
        $user = User::factory()->createQuietly();
        $this->actingAs($user);

        $formTemplate = FormTemplate::factory()->createOneQuietly();

        $response = $this->getJson(route('manager.form-templates.show', $formTemplate->id));

        $response->assertStatus(HttpResponse::HTTP_FORBIDDEN);
    }
}
