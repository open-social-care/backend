<?php

namespace Tests\Unit\Actions\Admin\User;

use App\Actions\Admin\User\UserUpdateAction;
use App\DTO\Shared\UserDTO;
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

        $userDTO = new UserDTO([
            'name' => fake()->name,
            'email' => fake()->unique()->email,
            'password' => bcrypt('secret'),
        ]);

        $userEmailBeforeUpdated = $user->email;
        UserUpdateAction::execute($userDTO, $user);

        $user = $user->fresh();

        $this->assertNotEquals($userEmailBeforeUpdated, $user->email);
        $this->assertDatabaseHas('users', ['email' => $userDTO->email]);
    }
}
