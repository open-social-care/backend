<?php

namespace Tests\Unit\Actions\Admin\User;

use App\Actions\Admin\User\UserCreateAction;
use App\DTO\Admin\UserDTO;
use App\Models\Organization;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserCreateActionTest extends TestCase
{
    use RefreshDatabase;

    public function testExecuteAction()
    {
        $roles = Role::factory()->count(2)->createQuietly();
        $organizations = Organization::factory()->count(2)->createQuietly();

        $userDTO = new UserDTO([
            'name' => fake()->name,
            'email' => fake()->email,
            'password' => bcrypt('secret'),
            'roles' => $roles->pluck('id')->toArray(),
            'organizations' => $organizations->pluck('id')->toArray(),
        ]);

        UserCreateAction::execute($userDTO);

        $this->assertDatabaseHas('users', ['email' => $userDTO->email]);
        $this->assertDatabaseHas('role_users', ['role_id' => $roles->first()->id]);
        $this->assertDatabaseHas('organization_users', ['organization_id' => $organizations->first()->id]);
    }
}
