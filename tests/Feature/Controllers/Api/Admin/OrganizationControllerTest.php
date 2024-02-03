<?php

namespace Tests\Feature\Controllers\Api\Admin;

use App\Enums\RolesEnum;
use App\Models\Organization;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response as HttpResponse;
use Tests\TestCase;

class OrganizationControllerTest extends TestCase
{
    use RefreshDatabase;

    protected Organization $organization;

    public function setUp(): void
    {
        parent::setUp();

        $this->organization = Organization::factory()->createQuietly();

        $userAdmin = User::factory()->createQuietly();
        $role = Role::factory()->createQuietly(['name' => RolesEnum::ADMIN->value]);
        $userAdmin->roles()->attach($role);
        $this->actingAs($userAdmin);
    }

    public function testIndexMethod()
    {
        $organizations = Organization::factory()->count(10)->createQuietly();

        $response = $this->getJson(route('admin.organizations.index'));

        $response->assertStatus(HttpResponse::HTTP_OK)
            ->assertJsonStructure(['data', 'pagination']);

        foreach ($organizations as $organization) {
            $response->assertJsonFragment(['name' => $organization->name]);
        }
    }

    public function testIndexMethodWithSearchTerm()
    {
        Organization::factory()->count(10)->createQuietly();
        $organization = Organization::factory()->createOneQuietly(['name' => 'Test search']);

        $response = $this->getJson(route('admin.organizations.index', ['q' => 'Test search']));

        $response->assertStatus(HttpResponse::HTTP_OK)
            ->assertJsonStructure(['data', 'pagination'])
            ->assertJsonFragment(['name' => $organization->name]);
    }

    public function testIndexMethodWithSearchTermWhenDontHaveContent()
    {
        Organization::factory()->count(10)->createQuietly();
        $organization = Organization::factory()->createOneQuietly(['name' => 'Test organization']);

        $response = $this->getJson(route('admin.organizations.index', ['q' => 'null organization']));

        $response->assertStatus(HttpResponse::HTTP_OK)
            ->assertJsonStructure(['data', 'pagination'])
            ->assertJsonCount(0, 'data')
            ->assertJsonMissing(['name' => $organization->name]);
    }

    public function testStoreMethod()
    {
        $data = [
            'name' => 'Teste organization',
            'phone' => '(42) 3035-4135',
            'document_type' => 'cpf',
            'document' => '014.431.840-71',
        ];

        $response = $this->postJson(route('admin.organizations.store'), $data);

        $response->assertStatus(HttpResponse::HTTP_OK)
            ->assertJson(['message' => __('messages.common.success_create')]);

        $this->assertDatabaseHas('organizations', ['name' => 'Teste organization']);
    }

    public function testStoreMethodValidation()
    {
        $response = $this->postJson(route('admin.organizations.store'), []);

        $response->assertStatus(HttpResponse::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                'message',
                'errors' => [
                    'name',
                    'phone',
                    'document_type',
                    'document',
                ],
            ]);
    }

    public function testUpdateMethod()
    {
        $organization = Organization::factory()->createOneQuietly();

        $updatedData = [
            'name' => 'New name organization',
            'phone' => '(42) 3035-4135',
            'document_type' => 'cpf',
            'document' => '014.431.840-71',
        ];

        $response = $this->putJson(route('admin.organizations.update', $organization->id), $updatedData);

        $response->assertStatus(HttpResponse::HTTP_OK)
            ->assertJson(['message' => __('messages.common.success_update')]);

        $organization = $organization->fresh();
        $this->assertEquals($updatedData['name'], $organization->name);
    }

    public function testUpdateMethodValidation()
    {
        $organization = Organization::factory()->createOneQuietly();

        $response = $this->putJson(route('admin.organizations.update', $organization->id), []);
        $response->assertStatus(HttpResponse::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                'message',
                'errors' => [
                    'name',
                ],
            ]);
    }

    public function testDestroyMethod()
    {
        $organization = Organization::factory()->create();

        $response = $this->deleteJson(route('admin.organizations.destroy', $organization->id));

        $response->assertStatus(HttpResponse::HTTP_OK)
            ->assertJson(['message' => __('messages.common.success_destroy')]);

        $this->assertSoftDeleted('organizations', ['id' => $organization->id]);
    }

    public function testDestroyMethodWithInvalidUser()
    {
        $response = $this->deleteJson(route('admin.organizations.destroy', 0));

        $response->assertStatus(HttpResponse::HTTP_NOT_FOUND)
            ->assertJsonStructure(['message']);
    }

    public function testAssociateUsersToOrganization()
    {
        $organization = Organization::factory()->createQuietly();
        $users = User::factory()->count(3)->createQuietly();

        $usersIds = $users->pluck('id')->toArray();
        $data = [
            'users' => $usersIds,
        ];

        $response = $this->postJson(route('admin.organizations.associate-users', $organization->id), $data);

        $response->assertStatus(HttpResponse::HTTP_OK)
            ->assertJson(['message' => __('messages.common.success_update')]);

        $this->assertEquals($usersIds, $organization->fresh()->users->pluck('id')->toArray());

        foreach ($usersIds as $userId) {
            $this->assertDatabaseHas('organization_users', ['user_id' => $userId]);
        }
    }

    public function testGetOrganizationUsersListByRole()
    {
        $organization = Organization::factory()->createQuietly();

        $userManager = User::factory()->createQuietly();
        $roleManager = Role::factory()->createQuietly(['name' => RolesEnum::MANAGER->value]);
        $userManager->roles()->attach($roleManager);

        $userSocialAssistant = User::factory()->createQuietly();
        $roleSocialAssistant = Role::factory()->createQuietly(['name' => RolesEnum::SOCIAL_ASSISTANT->value]);
        $userSocialAssistant->roles()->attach($roleSocialAssistant);

        $organization->users()->sync([$userManager->id, $userSocialAssistant->id]);

        $response = $this->getJson(route('admin.organizations.get-users-by-role',
            ['organization' => $organization->id, 'role' => RolesEnum::MANAGER->value]));

        $response->assertStatus(HttpResponse::HTTP_OK)
            ->assertJsonStructure(['data', 'pagination']);

        $this->assertCount(1, $response->json('data'));
        $response->assertJsonFragment(['name' => $userManager->name]);
    }
}
