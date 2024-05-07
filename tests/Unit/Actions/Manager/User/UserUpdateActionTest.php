<?php

namespace Tests\Unit\Actions\Manager\User;

use App\Actions\Manager\User\UserUpdateAction;
use App\DTO\Shared\UserDTO;
use App\Enums\RolesEnum;
use App\Models\Organization;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserUpdateActionTest extends TestCase
{
    use RefreshDatabase;

    private Organization $organization;

    public function testExecuteAction()
    {
        $this->organization = Organization::factory()->createOneQuietly();
        $user = $this->createUserForOrganization();

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

    private function createUserForOrganization()
    {
        $user = User::factory()->createOneQuietly();
        $role = Role::factory()->createQuietly(['name' => RolesEnum::SOCIAL_ASSISTANT->value]);
        $user->roles()->attach($role);
        $user->organizations()->attach($this->organization, ['role_id' => $role->id]);

        return $user;
    }
}
