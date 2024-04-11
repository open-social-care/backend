<?php

namespace Tests\Unit\Actions\Manager\User;

use App\Actions\Manager\User\UserDestroyAction;
use App\Enums\RolesEnum;
use App\Models\Organization;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserDestroyActionTest extends TestCase
{
    use RefreshDatabase;

    protected Organization $organization;

    protected Role $roleManager;

    protected Role $roleSocialAssistant;

    public function setUp(): void
    {
        parent::setUp();

        $this->organization = Organization::factory()->createQuietly();
        $this->roleManager = Role::factory()->createQuietly(['name' => RolesEnum::MANAGER->value]);
        $this->roleSocialAssistant = Role::factory()->createQuietly(['name' => RolesEnum::SOCIAL_ASSISTANT->value]);
    }

    public function testExecuteActionWhenUserHasOnlyOneOrganization()
    {
        $user = $this->createUserWithOneOrganization();
        UserDestroyAction::execute($user, $this->organization);

        $this->assertSoftDeleted('users', ['id' => $user->id]);

        $this->assertDatabaseMissing('organization_users', [
            'user_id' => $user->id,
            'organization_id' => $this->organization->id,
        ]);

        $this->assertDatabaseMissing('role_users', [
            'user_id' => $user->id,
            'role_id' => $this->roleManager->id,
        ]);
    }

    public function testExecuteActionWhenUserHasMoreThanOneOrganizationAndSameRole()
    {
        $user = $this->createUserWithTwoOrganizationAndSameRole();
        UserDestroyAction::execute($user, $this->organization);

        $this->assertDatabaseHas('users', ['id' => $user->id]);

        $this->assertDatabaseMissing('organization_users', [
            'user_id' => $user->id,
            'organization_id' => $this->organization->id,
        ]);
    }

    public function testExecuteActionWhenUserHasMoreThanOneOrganizationAndDifferentRoles()
    {
        $user = $this->createUserWithTwoOrganizationAndDifferentRoles();
        UserDestroyAction::execute($user, $this->organization);

        $this->assertDatabaseHas('users', ['id' => $user->id]);

        $this->assertDatabaseMissing('organization_users', [
            'user_id' => $user->id,
            'organization_id' => $this->organization->id,
        ]);

        $this->assertDatabaseMissing('role_users', [
            'user_id' => $user->id,
            'role_id' => $this->roleManager->id,
        ]);
    }

    private function createUserWithOneOrganization()
    {
        $user = User::factory()->createOneQuietly();
        $user->roles()->attach($this->roleManager);
        $user->organizations()->attach($this->organization, ['role_id' => $this->roleManager->id]);

        return $user;
    }

    private function createUserWithTwoOrganizationAndSameRole()
    {
        $user = User::factory()->createOneQuietly();
        $user->roles()->attach($this->roleManager);

        $organization2 = Organization::factory()->createQuietly();
        $user->organizations()->attach($organization2, ['role_id' => $this->roleManager->id]);
        $user->organizations()->attach($this->organization, ['role_id' => $this->roleManager->id]);

        return $user;
    }

    private function createUserWithTwoOrganizationAndDifferentRoles()
    {
        $user = User::factory()->createOneQuietly();

        $user->roles()->attach($this->roleManager);
        $user->organizations()->attach($this->organization, ['role_id' => $this->roleManager->id]);

        $organizationSocialAssistant = Organization::factory()->createQuietly();
        $user->roles()->attach($this->roleSocialAssistant);
        $user->organizations()->attach($organizationSocialAssistant, ['role_id' => $this->roleSocialAssistant->id]);

        return $user;
    }
}
