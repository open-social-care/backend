<?php

namespace Feature\Controllers\Api\Admin;

use App\Enums\RolesEnum;
use App\Http\Resources\Api\Admin\UserResource;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response as HttpResponse;
use Tests\TestCase;

class AdminUserControllerTest extends TestCase
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

    public function testIndexMethodWithSearchTermWhenDontHaveContent()
    {
        User::factory()->count(10)->createQuietly();
        $user = User::factory()->createOneQuietly(['name' => 'Test user']);

        $response = $this->getJson(route('admin.users.index', ['q' => 'users']));

        $response->assertStatus(HttpResponse::HTTP_OK)
            ->assertJsonStructure(['data', 'pagination'])
            ->assertJsonCount(0, 'data')
            ->assertJsonMissing(['name' => $user->name]);
    }

    public function testIndexMethodWhenUserCantAccessInformation()
    {
        $user = User::factory()->createQuietly();
        $this->actingAs($user);

        $response = $this->getJson(route('admin.users.index'));

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

        $response = $this->postJson(route('admin.users.store'), $userData);

        $response->assertStatus(HttpResponse::HTTP_OK)
            ->assertJson(['message' => __('messages.common.success_create')]);

        $this->assertDatabaseHas('users', ['email' => 'usuario@example.com']);
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
                ],
            ]);
    }

    public function testStoreMethodWhenUserCantAccessInformation()
    {
        $user = User::factory()->createQuietly();
        $this->actingAs($user);

        $userData = [
            'name' => 'Nome do Usuário',
            'email' => 'usuario@example.com',
            'password' => 'senha123',
            'password_confirmation' => 'senha123',
        ];

        $response = $this->postJson(route('admin.users.store'), $userData);

        $response->assertStatus(HttpResponse::HTTP_FORBIDDEN);
    }

    public function testUpdateMethod()
    {
        $user = User::factory()->createOneQuietly();

        $updatedUserData = [
            'name' => 'New name test',
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

    public function testUpdateMethodWhenUserCantAccessInformation()
    {
        $user = User::factory()->createQuietly();
        $this->actingAs($user);

        $userToUpdate = User::factory()->createOneQuietly();

        $updatedUserData = [
            'name' => 'New name test',
        ];

        $updatedUserData = array_merge($userToUpdate->toArray(), $updatedUserData);
        $response = $this->putJson(route('admin.users.update', $userToUpdate->id), $updatedUserData);

        $response->assertStatus(HttpResponse::HTTP_FORBIDDEN);
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

    public function testDestroyMethodWhenUserCantAccessInformation()
    {
        $user = User::factory()->createQuietly();
        $this->actingAs($user);

        $userToDestroy = User::factory()->create();

        $response = $this->deleteJson(route('admin.users.destroy', $userToDestroy->id));

        $response->assertStatus(HttpResponse::HTTP_FORBIDDEN);
    }

    public function testGetUserMethod()
    {
        $user = User::factory()->createOneQuietly();

        $response = $this->getJson(route('admin.users.get-user', $user->id));

        $response->assertStatus(HttpResponse::HTTP_OK)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'name',
                    'email',
                ],
            ]);

        $expectedUserData = UserResource::make($user)->jsonSerialize();
        $response->assertJsonFragment(['data' => $expectedUserData]);
    }

    public function testGetUserMethodWhenUserCantAccessInformation()
    {
        $user = User::factory()->createQuietly();
        $this->actingAs($user);

        $userToGet = User::factory()->createOneQuietly();

        $response = $this->getJson(route('admin.users.get-user', $userToGet->id));

        $response->assertStatus(HttpResponse::HTTP_FORBIDDEN);
    }
}
