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
        $users = User::factory()->count(2)->createQuietly();
        $roles = Role::factory()->count(2)->createQuietly();

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

        OrganizationAssociateUsersWithRolesAction::execute($data, $organization);

        foreach ($data as $item) {
            $this->assertDatabaseHas('organization_users', [
                'organization_id' => $organization->id,
                'user_id' => $item['user_id'],
                'role_id' => $item['role_id'],
            ]);

            $this->assertTrue(User::find($item['user_id'])->hasRoleById($item['role_id']));
        }
    }
}
