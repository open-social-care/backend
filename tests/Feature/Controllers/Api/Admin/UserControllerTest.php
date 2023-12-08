<?php

namespace Feature\Controllers\Api\Admin;

use App\Enums\RolesEnum;
use App\Http\Resources\Api\Admin\UserResource;
use App\Models\Organization;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response as HttpResponse;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->createQuietly();
        $role = Role::factory()->createQuietly(['name' => RolesEnum::ADMIN->value]);
        $this->user->roles()->attach($role);
        $this->actingAs($this->user);
    }

    public function testIndexMethod()
    {
        $users = User::factory()->count(10)->createQuietly();

        $response = $this->getJson(route('admin.users.index'));

        $response->assertStatus(HttpResponse::HTTP_OK)
            ->assertJsonStructure(['data', 'pagination']);

        foreach ($users as $user) {
            $response->assertJsonFragment(['name' => $user->name]);
        }
    }

    public function testIndexMethodWithSearchTerm()
    {
        User::factory()->count(10)->createQuietly();
        $user = User::factory()->createOneQuietly(['name' => 'Test search']);

        $response = $this->getJson(route('admin.users.index', ['q' => 'Test search']));

        $response->assertStatus(HttpResponse::HTTP_OK)
            ->assertJsonStructure(['data', 'pagination'])
            ->assertJsonFragment(['name' => $user->name]);
    }

    public function testStoreMethod()
    {
        $roles = Role::factory()->count(2)->createQuietly();
        $organizations = Organization::factory()->count(2)->createQuietly();

        $userData = [
            'name' => 'Nome do Usuário',
            'email' => 'usuario@example.com',
            'password' => 'senha123',
            'password_confirmation' => "senha123",
        	'roles' => $roles->pluck('id')->toArray(),
	        'organizations' => $organizations->pluck('id')->toArray()
        ];

        $response = $this->postJson(route('admin.users.store'), $userData);

        $response->assertStatus(HttpResponse::HTTP_OK)
            ->assertJson(['message' => __('messages.common.success_create')]);

        $this->assertDatabaseHas('users', ['email' => 'usuario@example.com']);
        $this->assertDatabaseHas('role_users', ['role_id' => $roles->first()->id]);
        $this->assertDatabaseHas('organization_users', ['organization_id' => $organizations->first()->id]);
    }

    public function testStoreMethodValidation()
    {
        $response = $this->postJson(route('admin.users.store'), []);

        $response->assertStatus(HttpResponse::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                'message',
                'errors' => [
                    'name',
                    'email',
                    'password',
                    'roles',
                    'organizations',
                ],
            ]);
    }

    public function testUpdateMethod()
    {
        $user = User::factory()->createOneQuietly();

        $roles = Role::factory()->count(2)->createQuietly();
        $organizations = Organization::factory()->count(2)->createQuietly();

        $updatedUserData = [
            'name' => 'New name test',
            'roles' => $roles->pluck('id')->toArray(),
            'organizations' => $organizations->pluck('id')->toArray()
        ];

        $updatedUserData = array_merge($user->toArray(), $updatedUserData);
        $response = $this->putJson(route('admin.users.update', $user->id), $updatedUserData);

        $response->assertStatus(HttpResponse::HTTP_OK)
            ->assertJson(['message' => __('messages.common.success_update')]);

        $user = $user->fresh();
        $this->assertEquals($updatedUserData['name'], $user->name);
    }

    public function testUpdateMethodValidation()
    {
        $user = User::factory()->createOneQuietly();

        $response = $this->putJson(route('admin.users.update', $user->id), []);
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
        $user = User::factory()->create();

        $response = $this->deleteJson(route('admin.users.destroy', $user->id));

        $response->assertStatus(HttpResponse::HTTP_OK)
            ->assertJson(['message' => __('messages.common.success_destroy')]);

        $this->assertSoftDeleted('users', ['id' => $user->id]);
    }

    public function testDestroyMethodWithInvalidUser()
    {
        $response = $this->deleteJson(route('admin.users.destroy', 0));

        $response->assertStatus(HttpResponse::HTTP_NOT_FOUND)
            ->assertJsonStructure(['message']);
    }

    public function testFormInfosMethod()
    {
        Role::factory()->count(5)->createQuietly();
        Organization::factory()->count(5)->createQuietly();

        $response = $this->getJson(route('admin.users.form-infos'));

        $response->assertStatus(HttpResponse::HTTP_OK)
            ->assertJsonStructure([
                'organizationsToSelect',
                'rolesToSelect',
            ]);

        $response->assertJsonFragment(['organizationsToSelect' => to_select(Organization::all())]);
        $response->assertJsonFragment(['rolesToSelect' => to_select_by_enum(Role::all(), RolesEnum::class)]);
    }

    public function testGetUserMethod()
    {
        $user = User::factory()->createOneQuietly();

        $response = $this->getJson(route('admin.users.get-user', $user->id));

        $response->assertStatus(HttpResponse::HTTP_OK)
            ->assertJsonStructure(['user']);

        $expectedUserData = UserResource::make($user)->jsonSerialize();
        $response->assertJsonFragment(['user' => $expectedUserData]);
    }
}
