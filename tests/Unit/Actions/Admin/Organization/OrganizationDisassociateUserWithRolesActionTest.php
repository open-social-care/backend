<?php

namespace Tests\Unit\Actions\Admin\Organization;

use App\Actions\Admin\Organization\OrganizationAssociateUsersWithRolesAction;
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
        $users = User::factory()->count(2)->createQuietly();
        $roles = Role::factory()->count(2)->createQuietly();

        $organization->users()->attach($users[0]->id, ['role_id' => $roles[0]->id]);
        $organization->users()->attach($users[1]->id, ['role_id' => $roles[1]->id]);

        $users[0]->roles()->attach($roles[0]);
        $users[1]->roles()->attach($roles[1]);

        $data = [
            [
                'user_id' => $users[0]->id,
                'role_id' => $roles[0]->id,
            ],
            [
                'user_id' => $users[1]->id,
                'role_id' => $roles[1]->id,
            ],
        ];

        OrganizationDisassociateUsersWithRolesAction::execute($data, $organization);

        foreach ($data as $item) {
            $this->assertDatabaseMissing('organization_users', [
                'organization_id' => $organization->id,
                'user_id' => $item['user_id'],
                'role_id' => $item['role_id'],
            ]);

            $this->assertFalse(User::find($item['user_id'])->hasRoleById($item['role_id']));
        }
    }
}
