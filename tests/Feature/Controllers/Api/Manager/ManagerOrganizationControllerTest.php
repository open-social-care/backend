<?php

namespace Tests\Feature\Controllers\Api\Manager;

use App\Enums\RolesEnum;
use App\Models\Organization;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response as HttpResponse;
use Tests\TestCase;

class ManagerOrganizationControllerTest extends TestCase
{
    use RefreshDatabase;

    protected Organization $organization;

    protected User $userManager;

    public function setUp(): void
    {
        parent::setUp();

        $this->organization = Organization::factory()->createQuietly();

        $this->userManager = User::factory()->createQuietly();
        $role = Role::factory()->createQuietly(['name' => RolesEnum::MANAGER->value]);
        $this->userManager->roles()->attach($role);
        $this->userManager->organizations()->attach($this->organization, ['role_id' => $role->id]);
        $this->actingAs($this->userManager);
    }

    public function testIndexMethod()
    {
        $response = $this->getJson(route('manager.organizations.index'));

        $response->assertStatus(HttpResponse::HTTP_OK)
            ->assertJsonStructure(['data', 'pagination']);

        $response->assertJsonFragment(['name' => $this->organization->name]);
    }

    public function testIndexMethodWithSearchTerm()
    {
        $organization = Organization::factory()->createOneQuietly(['name' => 'Test search']);
        $role = Role::factory()->createQuietly(['name' => RolesEnum::SOCIAL_ASSISTANT->value]);

        $this->userManager->organizations()->attach($organization, ['role_id' => $role->id]);

        $response = $this->getJson(route('manager.organizations.index', ['q' => 'Test search']));

        $response->assertStatus(HttpResponse::HTTP_OK)
            ->assertJsonStructure(['data', 'pagination'])
            ->assertJsonFragment(['name' => $organization->name]);
    }

    public function testIndexMethodWithSearchTermWhenDontHaveContent()
    {
        $organization = Organization::factory()->createOneQuietly(['name' => 'Test search']);
        $role = Role::factory()->createQuietly(['name' => RolesEnum::SOCIAL_ASSISTANT->value]);

        $this->userManager->organizations()->attach($this->organization, ['role_id' => $role->id]);

        $response = $this->getJson(route('manager.organizations.index', ['q' => 'null organization']));

        $response->assertStatus(HttpResponse::HTTP_OK)
            ->assertJsonStructure(['data', 'pagination'])
            ->assertJsonCount(0, 'data')
            ->assertJsonMissing(['name' => $organization->name]);
    }

    public function testIndexMethodWhenUserCantAccessInformation()
    {
        $user = User::factory()->createQuietly();
        $this->actingAs($user);

        $response = $this->getJson(route('manager.organizations.index'));

        $response->assertStatus(HttpResponse::HTTP_FORBIDDEN);
    }

    public function testShowMethod()
    {
        $response = $this->getJson(route('manager.organizations.show', $this->organization->id));

        $response->assertStatus(HttpResponse::HTTP_OK)
            ->assertJsonStructure(['data']);

        $response->assertJsonFragment(['name' => $this->organization->name]);
    }

    public function testShowMethodWhenUserCantAccessOrganization()
    {
        $organization = Organization::factory()->createQuietly();
        $user = User::factory()->createQuietly();
        $roleManager = Role::factory()->createQuietly(['name' => RolesEnum::MANAGER->value]);
        $user->roles()->attach($roleManager);
        $user->organizations()->attach($organization, ['role_id' => $roleManager->id]);
        $this->actingAs($user);

        $response = $this->getJson(route('manager.organizations.show', $this->organization->id));

        $response->assertStatus(HttpResponse::HTTP_FORBIDDEN);
    }

    public function testUpdateMethod()
    {
        $updatedData = [
            'name' => 'New name organization',
            'phone' => '(42) 3035-4135',
            'document_type' => 'cpf',
            'document' => '014.431.840-71',
        ];

        $response = $this->putJson(route('manager.organizations.update', $this->organization->id), $updatedData);

        $response->assertStatus(HttpResponse::HTTP_OK)
            ->assertJson(['message' => __('messages.common.success_update')]);

        $this->organization = $this->organization->fresh();
        $this->assertEquals($updatedData['name'], $this->organization->name);
    }

    public function testUpdateMethodValidation()
    {
        $response = $this->putJson(route('manager.organizations.update', $this->organization->id), []);

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

    public function testUpdateMethodWhenUserCantAccessOrganization()
    {
        $organization = Organization::factory()->createQuietly();
        $user = User::factory()->createQuietly();
        $roleManager = Role::factory()->createQuietly(['name' => RolesEnum::MANAGER->value]);
        $user->roles()->attach($roleManager);
        $user->organizations()->attach($organization, ['role_id' => $roleManager->id]);
        $this->actingAs($user);

        $updatedData = [
            'name' => 'New name organization',
            'phone' => '(42) 3035-4135',
            'document_type' => 'cpf',
            'document' => '014.431.840-71',
        ];

        $response = $this->putJson(route('manager.organizations.update', $this->organization->id), $updatedData);

        $response->assertStatus(HttpResponse::HTTP_FORBIDDEN);
    }

    public function testGetOrganizationUsersListByRoleMethod()
    {
        $response = $this->getJson(route('manager.organizations.get-users-by-role', [
            'organization' => $this->organization->id,
            'role' => RolesEnum::MANAGER->value,
        ]));

        $response->assertStatus(HttpResponse::HTTP_OK)
            ->assertJsonStructure(['data', 'pagination']);

        $this->assertCount(1, $response->json('data'));
        $response->assertJsonFragment(['name' => $this->userManager->name]);
    }

    public function testGetOrganizationUsersListByRoleMethodWhenUserCantAccessOrganization()
    {
        $organization = Organization::factory()->createQuietly();
        $user = User::factory()->createQuietly();
        $roleManager = Role::factory()->createQuietly(['name' => RolesEnum::MANAGER->value]);
        $user->roles()->attach($roleManager);
        $user->organizations()->attach($organization, ['role_id' => $roleManager->id]);
        $this->actingAs($user);

        $response = $this->getJson(route('manager.organizations.get-users-by-role', [
            'organization' => $this->organization->id,
            'role' => RolesEnum::MANAGER->value,
        ]));

        $response->assertStatus(HttpResponse::HTTP_FORBIDDEN);
    }
}
