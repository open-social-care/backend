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
        $userDTO = new UserDTO([
            'name' => fake()->name,
            'email' => fake()->email,
            'password' => bcrypt('secret'),
        ]);

        UserCreateAction::execute($userDTO);

        $this->assertDatabaseHas('users', ['email' => $userDTO->email]);
    }
}
