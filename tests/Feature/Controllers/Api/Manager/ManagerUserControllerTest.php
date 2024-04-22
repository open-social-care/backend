<?php

namespace Tests\Feature\Controllers\Api\Manager;

use App\Enums\RolesEnum;
use App\Http\Resources\Api\Shared\UserListWithRolesResource;
use App\Models\Organization;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response as HttpResponse;
use Tests\TestCase;

class ManagerUserControllerTest extends TestCase
{
    use RefreshDatabase;

    protected Organization $organization;

    protected User $user;

    public function setUp(): void
    {
        parent::setUp();

        $this->organization = Organization::factory()->createQuietly();

        $this->userManager = User::factory()->createQuietly();
        $roleManager = Role::factory()->createQuietly(['name' => RolesEnum::MANAGER->value]);
        $this->userManager->roles()->attach($roleManager);
        $this->userManager->organizations()->attach($this->organization, ['role_id' => $roleManager->id]);
        $this->actingAs($this->userManager);

        Role::factory()->createQuietly(['name' => RolesEnum::SOCIAL_ASSISTANT->value]);
    }

    public function testIndexMethod()
    {
        $users = User::factory()->count(5)->createQuietly();

        foreach ($users as $user) {
            $role = Role::factory()->createQuietly(['name' => RolesEnum::SOCIAL_ASSISTANT->value]);
            $user->roles()->attach($role);
            $user->organizations()->attach($this->organization, ['role_id' => $role->id]);
        }

        $response = $this->getJson(route('manager.users.index', ['organization' => $this->organization->id]));

        $response->assertStatus(HttpResponse::HTTP_OK)
            ->assertJsonStructure(['data', 'pagination']);

        foreach ($users as $user) {
            $response->assertJsonFragment(['name' => $user->name]);
        }
    }

    public function testIndexMethodWithSearchTerm()
    {
        $this->createUsersForOrganization();
        $userName = 'Test search organization';
        $user = $this->createUserForOrganization($userName);

        $response = $this->getJson(route('manager.users.index', [
            'organization' => $this->organization->id,
            'q' => $userName,
        ]));

        $response->assertStatus(HttpResponse::HTTP_OK)
            ->assertJsonStructure(['data', 'pagination'])
            ->assertJsonFragment(['name' => $user->name]);
    }

    public function testIndexMethodWithSearchTermWhenDontHaveContent()
    {
        $this->createUsersForOrganization();
        $userName = 'Test search organization';
        $user = $this->createUserForOrganization($userName);

        $response = $this->getJson(route('manager.users.index', [
            'organization' => $this->organization->id,
            'q' => 'users',
        ]));

        $response->assertStatus(HttpResponse::HTTP_OK)
            ->assertJsonStructure(['data', 'pagination'])
            ->assertJsonCount(0, 'data')
            ->assertJsonMissing(['name' => $user->name]);
    }

    public function testIndexMethodWhenUserCantAccessOrganization()
    {
        $organization = Organization::factory()->createQuietly();
        $user = User::factory()->createQuietly();
        $roleManager = Role::factory()->createQuietly(['name' => RolesEnum::MANAGER->value]);
        $user->roles()->attach($roleManager);
        $user->organizations()->attach($organization, ['role_id' => $roleManager->id]);
        $this->actingAs($user);

        $response = $this->getJson(route('manager.users.index', ['organization' => $this->organization->id]));

        $response->assertStatus(HttpResponse::HTTP_FORBIDDEN);
    }

    public function testStoreMethod()
    {
        $userData = [
            'name' => 'Nome do Usuário',
            'email' => 'usuario@example.com',
            'password' => 'senha123',
            'password_confirmation' => 'senha123',
        ];

        $response = $this->postJson(route('manager.users.store', ['organization' => $this->organization->id]), $userData);

        $response->assertStatus(HttpResponse::HTTP_OK)
            ->assertJson(['message' => __('messages.common.success_create')]);

        $this->assertDatabaseHas('users', ['email' => 'usuario@example.com']);
    }

    public function testStoreMethodValidation()
    {
        $response = $this->postJson(route('manager.users.store', ['organization' => $this->organization->id]), []);

        $response->assertStatus(HttpResponse::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                'message',
                'errors' => [
                    'name',
                    'email',
                    'password',
                ],
            ]);
    }

    public function testStoreMethodWhenUserCantAccessOrganization()
    {
        $organization = Organization::factory()->createQuietly();
        $user = User::factory()->createQuietly();
        $roleManager = Role::factory()->createQuietly(['name' => RolesEnum::MANAGER->value]);
        $user->roles()->attach($roleManager);
        $user->organizations()->attach($organization, ['role_id' => $roleManager->id]);
        $this->actingAs($user);

        $userData = [
            'name' => 'Nome do Usuário',
            'email' => 'usuario@example.com',
            'password' => 'senha123',
            'password_confirmation' => 'senha123',
        ];

        $response = $this->postJson(route('manager.users.store', ['organization' => $this->organization->id]), $userData);

        $response->assertStatus(HttpResponse::HTTP_FORBIDDEN);
    }

    public function testUpdateMethod()
    {
        $user = $this->createUserForOrganization();

        $updatedUserData = [
            'name' => 'New name test',
        ];

        $updatedUserData = array_merge($user->toArray(), $updatedUserData);
        $response = $this->putJson(route('manager.users.update', ['user' => $user->id]), $updatedUserData);

        $response->assertStatus(HttpResponse::HTTP_OK)
            ->assertJson(['message' => __('messages.common.success_update')]);

        $user = $user->fresh();
        $this->assertEquals($updatedUserData['name'], $user->name);
    }

    public function testUpdateMethodValidation()
    {
        $user = $this->createUserForOrganization();

        $response = $this->putJson(route('manager.users.update', $user->id), []);
        $response->assertStatus(HttpResponse::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                'message',
                'errors' => [
                    'name',
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

        $userToUpdate = $this->createUserForOrganization();

        $updatedUserData = [
            'name' => 'New name test',
        ];

        $updatedUserData = array_merge($user->toArray(), $updatedUserData);
        $response = $this->putJson(route('manager.users.update', ['user' => $userToUpdate->id]), $updatedUserData);

        $response->assertStatus(HttpResponse::HTTP_FORBIDDEN);
    }

    public function testDisassociateUserFromOrganizationMethod()
    {
        $user = $this->createUserForOrganization();

        $response = $this->deleteJson(route('manager.users.disassociate-user-from-organization', [
            'user' => $user->id,
            'organization' => $this->organization->id,
        ]));

        $response->assertStatus(HttpResponse::HTTP_OK)
            ->assertJson(['message' => __('messages.common.success_destroy')]);

        $this->assertDatabaseMissing('organization_users', [
            'organization_id' => $this->organization->id,
            'user_id' => $user->id,
        ]);
    }

    public function testDisassociateUserFromOrganizationMethodWithInvalidUser()
    {
        $response = $this->deleteJson(route('manager.users.disassociate-user-from-organization', [
            'user' => 0,
            'organization' => 0,
        ]));

        $response->assertStatus(HttpResponse::HTTP_NOT_FOUND)
            ->assertJsonStructure(['message']);
    }

    public function testDisassociateUserFromOrganizationMethodWhenUserCantAccessOrganization()
    {
        $organization = Organization::factory()->createQuietly();
        $user = User::factory()->createQuietly();
        $roleManager = Role::factory()->createQuietly(['name' => RolesEnum::MANAGER->value]);
        $user->roles()->attach($roleManager);
        $user->organizations()->attach($organization, ['role_id' => $roleManager->id]);
        $this->actingAs($user);

        $userToDisassociate = $this->createUserForOrganization();

        $response = $this->deleteJson(route('manager.users.disassociate-user-from-organization', [
            'user' => $userToDisassociate->id,
            'organization' => $this->organization->id,
        ]));

        $response->assertStatus(HttpResponse::HTTP_FORBIDDEN);
    }

    public function testGetUserMethod()
    {
        $user = $this->createUserForOrganization();

        $response = $this->getJson(route('manager.users.get-user', $user->id));

        $response->assertStatus(HttpResponse::HTTP_OK)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'name',
                    'email',
                ],
            ]);

        $expectedUserData = UserListWithRolesResource::make($user)->jsonSerialize();
        $response->assertJsonFragment(['data' => $expectedUserData]);
    }

    public function testGetUserMethodWhenUserCantAccessOrganization()
    {
        $organization = Organization::factory()->createQuietly();
        $user = User::factory()->createQuietly();
        $roleManager = Role::factory()->createQuietly(['name' => RolesEnum::MANAGER->value]);
        $user->roles()->attach($roleManager);
        $user->organizations()->attach($organization, ['role_id' => $roleManager->id]);
        $this->actingAs($user);

        $userToGet = $this->createUserForOrganization();

        $response = $this->getJson(route('manager.users.get-user', $userToGet->id));

        $response->assertStatus(HttpResponse::HTTP_FORBIDDEN);
    }

    private function createUsersForOrganization()
    {
        $users = User::factory()->count(5)->createQuietly();

        foreach ($users as $user) {
            $role = Role::factory()->createQuietly(['name' => RolesEnum::SOCIAL_ASSISTANT->value]);
            $user->roles()->attach($role);
            $user->organizations()->attach($this->organization, ['role_id' => $role->id]);
        }

        return $users;
    }

    private function createUserForOrganization(string $name = null)
    {
        $name = $name ?? fake()->name;
        $user = User::factory()->createQuietly(['name' => $name]);
        $role = Role::factory()->createQuietly(['name' => RolesEnum::SOCIAL_ASSISTANT->value]);
        $user->roles()->attach($role);
        $user->organizations()->attach($this->organization, ['role_id' => $role->id]);

        return $user;
    }
}
