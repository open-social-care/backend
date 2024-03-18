<?php

namespace Tests\Unit\Actions\Admin\Organization;

use App\Actions\Admin\Organization\OrganizationDisassociateUsersWithRolesAction;
use App\Models\Organization;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrganizationDisassociateUserWithRolesActionTest extends TestCase
{
    use RefreshDatabase;

    public function testExecuteAction()
    {
        $organization = Organization::factory()->createQuietly();
        $user = User::factory()->createOneQuietly();
        $role = Role::factory()->createOneQuietly();

        $organization->users()->attach($user->id, ['role_id' => $role->id]);
        $user->roles()->attach($role);

        OrganizationDisassociateUsersWithRolesAction::execute($user, $role, $organization);

        $this->assertDatabaseMissing('organization_users', [
            'organization_id' => $organization->id,
            'user_id' => $user->id,
            'role_id' => $role->id,
        ]);

        $this->assertFalse($user->hasRoleById($role->id));
    }
}
