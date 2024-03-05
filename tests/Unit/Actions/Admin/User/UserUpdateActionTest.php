<?php

namespace Tests\Unit\Actions\Admin\User;

use App\Actions\Admin\User\UserUpdateAction;
use App\DTO\Admin\UserDTO;
use App\Models\Organization;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserUpdateActionTest extends TestCase
{
    use RefreshDatabase;

    public function testExecuteAction()
    {
        $user = User::factory()->createOneQuietly();

        $roles = Role::factory()->count(2)->createQuietly();
        $organizations = Organization::factory()->count(2)->createQuietly();

        $userDTO = new UserDTO([
            'name' => fake()->name,
            'email' => fake()->unique()->email,
            'password' => bcrypt('secret'),
            'roles' => $roles->pluck('id')->toArray(),
            'organizations' => $organizations->pluck('id')->toArray(),
        ]);

        $userEmailBeforeUpdated = $user->email;
        UserUpdateAction::execute($userDTO, $user);

        $user = $user->fresh();

        $this->assertNotEquals($userEmailBeforeUpdated, $user->email);
        $this->assertDatabaseHas('users', ['email' => $userDTO->email]);
        $this->assertDatabaseHas('role_users', ['role_id' => $roles->first()->id]);
        $this->assertDatabaseHas('organization_users', ['organization_id' => $organizations->first()->id]);
    }
}
