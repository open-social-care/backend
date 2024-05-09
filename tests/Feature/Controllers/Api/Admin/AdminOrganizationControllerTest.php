<?php

namespace Tests\Feature\Controllers\Api\Admin;

use App\Enums\RolesEnum;
use App\Models\Organization;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response as HttpResponse;
use Tests\TestCase;

class AdminOrganizationControllerTest extends TestCase
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

    public function testIndexMethodWhenUserCantAccessInformation()
    {
        $user = User::factory()->createQuietly();
        $this->actingAs($user);

        $response = $this->getJson(route('admin.organizations.index'));

        $response->assertStatus(HttpResponse::HTTP_FORBIDDEN);
    }

    public function testStoreMethod()
    {
        $data = [
            'name' => 'Teste organization',
            'phone' => '(42) 3035-4135',
            'document_type' => 'cpf',
            'document' => '529.982.247-25',
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

    public function testStoreMethodWhenUserCantAccessInformation()
    {
        $user = User::factory()->createQuietly();
        $this->actingAs($user);

        $data = [
            'name' => 'Teste organization',
            'phone' => '(42) 3035-4135',
            'document_type' => 'cpf',
            'document' => '529.982.247-25',
        ];

        $response = $this->postJson(route('admin.organizations.store'), $data);

        $response->assertStatus(HttpResponse::HTTP_FORBIDDEN);
    }

    public function testUpdateMethod()
    {
        $organization = Organization::factory()->createOneQuietly();

        $updatedData = [
            'name' => 'New name organization',
            'phone' => '(42) 3035-4135',
            'document_type' => 'cpf',
            'document' => '529.982.247-25',
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

    public function testUpdateMethodWhenUserCantAccessInformation()
    {
        $user = User::factory()->createQuietly();
        $this->actingAs($user);

        $organization = Organization::factory()->createOneQuietly();

        $updatedData = [
            'name' => 'New name organization',
            'phone' => '(42) 3035-4135',
            'document_type' => 'cpf',
            'document' => '529.982.247-25',
        ];

        $response = $this->putJson(route('admin.organizations.update', $organization->id), $updatedData);

        $response->assertStatus(HttpResponse::HTTP_FORBIDDEN);
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

    public function testDestroyMethodWhenUserCantAccessInformation()
    {
        $user = User::factory()->createQuietly();
        $this->actingAs($user);

        $organization = Organization::factory()->create();

        $response = $this->deleteJson(route('admin.organizations.destroy', $organization->id));

        $response->assertStatus(HttpResponse::HTTP_FORBIDDEN);
    }

    public function testAssociateUsersToOrganizationMethod()
    {
        $organization = Organization::factory()->createQuietly();
        $users = User::factory()->count(2)->createQuietly();
        $roles = Role::factory()->count(2)->createQuietly();

        $data = [
            'data' => [
                [
                    'user_id' => $users[0]->id,
                    'role_id' => $roles[0]->id,
                ],
                [
                    'user_id' => $users[1]->id,
                    'role_id' => $roles[1]->id,
                ],
            ],
        ];

        $response = $this->postJson(route('admin.organizations.associate-users', $organization->id), $data);

        $response->assertStatus(HttpResponse::HTTP_OK)
            ->assertJson(['message' => __('messages.common.success_update')]);

        $this->assertEquals(count($data['data']), $organization->fresh()->users->count());
        $this->assertDatabaseHas('organization_users', ['user_id' => $users[0]->id, 'role_id' => $roles[0]->id]);
        $this->assertDatabaseHas('organization_users', ['user_id' => $users[1]->id, 'role_id' => $roles[1]->id]);
    }

    public function testAssociateUsersToOrganizationMethodWhenUserCantAccessInformation()
    {
        $user = User::factory()->createQuietly();
        $this->actingAs($user);

        $organization = Organization::factory()->createQuietly();
        $users = User::factory()->count(2)->createQuietly();
        $roles = Role::factory()->count(2)->createQuietly();

        $data = [
            'data' => [
                [
                    'user_id' => $users[0]->id,
                    'role_id' => $roles[0]->id,
                ],
                [
                    'user_id' => $users[1]->id,
                    'role_id' => $roles[1]->id,
                ],
            ],
        ];

        $response = $this->postJson(route('admin.organizations.associate-users', $organization->id), $data);

        $response->assertStatus(HttpResponse::HTTP_FORBIDDEN);
    }

    public function testDissociateUsersToOrganizationMethod()
    {
        $organization = Organization::factory()->createQuietly();
        $users = User::factory()->count(2)->createQuietly();
        $roles = Role::factory()->count(2)->createQuietly();

        $organization->users()->attach($users[0]->id, ['role_id' => $roles[0]->id]);
        $organization->users()->attach($users[1]->id, ['role_id' => $roles[1]->id]);

        $users[0]->roles()->attach($roles[0]);
        $users[1]->roles()->attach($roles[1]);

        $data = [
            'data' => [
                [
                    'user_id' => $users[0]->id,
                    'role_id' => $roles[0]->id,
                ],
                [
                    'user_id' => $users[1]->id,
                    'role_id' => $roles[1]->id,
                ],
            ],
        ];

        $response = $this->postJson(route('admin.organizations.disassociate-users', $organization->id), $data);

        $response->assertStatus(HttpResponse::HTTP_OK)
            ->assertJson(['message' => __('messages.common.success_update')]);

        $this->assertDatabaseMissing('organization_users', ['user_id' => $users[0]->id, 'role_id' => $roles[0]->id]);
        $this->assertDatabaseMissing('organization_users', ['user_id' => $users[1]->id, 'role_id' => $roles[1]->id]);
    }

    public function testDissociateUsersToOrganizationMethodWhenUserCantAccessInformation()
    {
        $user = User::factory()->createQuietly();
        $this->actingAs($user);

        $organization = Organization::factory()->createQuietly();
        $users = User::factory()->count(2)->createQuietly();
        $roles = Role::factory()->count(2)->createQuietly();

        $organization->users()->attach($users[0]->id, ['role_id' => $roles[0]->id]);
        $organization->users()->attach($users[1]->id, ['role_id' => $roles[1]->id]);

        $users[0]->roles()->attach($roles[0]);
        $users[1]->roles()->attach($roles[1]);

        $data = [
            'data' => [
                [
                    'user_id' => $users[0]->id,
                    'role_id' => $roles[0]->id,
                ],
                [
                    'user_id' => $users[1]->id,
                    'role_id' => $roles[1]->id,
                ],
            ],
        ];

        $response = $this->postJson(route('admin.organizations.disassociate-users', $organization->id), $data);

        $response->assertStatus(HttpResponse::HTTP_FORBIDDEN);
    }

    public function testGetOrganizationUsersListByRoleMethod()
    {
        $organization = Organization::factory()->createQuietly();

        $userManager = User::factory()->createQuietly();
        $roleManager = Role::factory()->createQuietly(['name' => RolesEnum::MANAGER->value]);
        $userManager->roles()->attach($roleManager);
        $organization->users()->attach($userManager->id, ['role_id' => $roleManager->id]);

        $userSocialAssistant = User::factory()->createQuietly();
        $roleSocialAssistant = Role::factory()->createQuietly(['name' => RolesEnum::SOCIAL_ASSISTANT->value]);
        $userSocialAssistant->roles()->attach($roleSocialAssistant);
        $organization->users()->attach($userSocialAssistant->id, ['role_id' => $roleSocialAssistant->id]);

        $response = $this->getJson(route('admin.organizations.get-users-by-role',
            ['organization' => $organization->id, 'role' => RolesEnum::MANAGER->value]));

        $response->assertStatus(HttpResponse::HTTP_OK)
            ->assertJsonStructure(['data', 'pagination']);

        $this->assertCount(1, $response->json('data'));
        $response->assertJsonFragment(['name' => $userManager->name]);
    }

    public function testGetOrganizationUsersListByRoleMethodWhenUserCantAccessInformation()
    {
        $user = User::factory()->createQuietly();
        $this->actingAs($user);

        $organization = Organization::factory()->createQuietly();

        $response = $this->getJson(route('admin.organizations.get-users-by-role',
            ['organization' => $organization->id, 'role' => RolesEnum::MANAGER->value]));

        $response->assertStatus(HttpResponse::HTTP_FORBIDDEN);
    }

    public function testShowMethod()
    {
        $response = $this->getJson(route('admin.organizations.show', $this->organization->id));

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

        $response = $this->getJson(route('admin.organizations.show', $this->organization->id));

        $response->assertStatus(HttpResponse::HTTP_FORBIDDEN);
    }

    public function testGetNonMembersByRoleMethod()
    {
        $roleManager = Role::factory()->createQuietly(['name' => RolesEnum::MANAGER->value]);

        $organization1 = Organization::factory()->createQuietly();
        $userManagerWithOrganization1 = User::factory()->createQuietly();
        $userManagerWithOrganization1->roles()->attach($roleManager);
        $organization1->users()->attach($userManagerWithOrganization1->id, ['role_id' => $roleManager->id]);

        $roleSocialAssistant = Role::factory()->createQuietly(['name' => RolesEnum::SOCIAL_ASSISTANT->value]);
        $userSocialAssistantWithOrganization1 = User::factory()->createQuietly();
        $userSocialAssistantWithOrganization1->roles()->attach($roleSocialAssistant);
        $organization1->users()->attach($userSocialAssistantWithOrganization1->id, ['role_id' => $roleSocialAssistant->id]);

        $organization2 = Organization::factory()->createQuietly();
        $userManagerWithOrganization2 = User::factory()->createQuietly();
        $userManagerWithOrganization2->roles()->attach($roleManager);
        $organization2->users()->attach($userManagerWithOrganization2->id, ['role_id' => $roleManager->id]);

        $userWithoutOrganization = User::factory()->createQuietly();

        $response = $this->getJson(route('admin.organizations.get-non-members-by-role',
            ['organization' => $organization1->id, 'role' => $roleManager->name]));

        $response->assertStatus(HttpResponse::HTTP_OK)
            ->assertJsonStructure(['data', 'pagination']);

        $response->assertJsonFragment(['name' => $userSocialAssistantWithOrganization1->name]);
        $response->assertJsonFragment(['name' => $userWithoutOrganization->name]);
        $response->assertJsonFragment(['name' => $userManagerWithOrganization2->name]);

        $response->assertJsonMissing(['name' => $userManagerWithOrganization1->name]);
    }

    public function testGetNonMembersByRoleMethodWhenUserCantAccessInformation()
    {
        $user = User::factory()->createQuietly();
        $this->actingAs($user);

        $organization = Organization::factory()->createQuietly();

        $response = $this->getJson(route('admin.organizations.get-non-members-by-role',
            ['organization' => $organization->id, 'role' => 'manager']));

        $response->assertStatus(HttpResponse::HTTP_FORBIDDEN);
    }
}
