<?php

namespace Tests\Unit\Actions\Admin\Organization;

use App\Actions\Admin\Organization\OrganizationAssociateUsersWithRolesAction;
use App\Models\Organization;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrganizationAssociateUserWithRolesActionTest extends TestCase
{
    use RefreshDatabase;

    public function testExecuteAction()
    {
        $organization = Organization::factory()->createQuietly();
        $user = User::factory()->createOneQuietly();
        $role = Role::factory()->createOneQuietly();

        OrganizationAssociateUsersWithRolesAction::execute($user, $role, $organization);

        $this->assertDatabaseHas('organization_users', [
            'organization_id' => $organization->id,
            'user_id' => $user->id,
            'role_id' => $role->id,
        ]);

        $this->assertTrue($user->hasRoleById($role->id));
    }
}
