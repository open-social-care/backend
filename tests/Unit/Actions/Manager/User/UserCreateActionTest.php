<?php

namespace Tests\Unit\Actions\Manager\User;

use App\Actions\Manager\User\UserCreateAction;
use App\DTO\Shared\UserDTO;
use App\Enums\RolesEnum;
use App\Models\Organization;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserCreateActionTest extends TestCase
{
    use RefreshDatabase;

    public function testExecuteAction()
    {
        Role::factory()->createQuietly(['name' => RolesEnum::SOCIAL_ASSISTANT->value]);
        $organization = Organization::factory()->createOneQuietly();

        $userDTO = new UserDTO([
            'name' => fake()->name,
            'email' => fake()->email,
            'password' => bcrypt('secret'),
        ]);

        $user = UserCreateAction::execute($userDTO, $organization);

        $this->assertDatabaseHas('users', ['email' => $userDTO->email]);
        $this->assertDatabaseHas('organization_users', ['user_id' => $user->id]);
    }
}
