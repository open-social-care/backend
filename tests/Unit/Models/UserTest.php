<?php

namespace Tests\Unit\Models;

use App\Models\FormAnswer;
use App\Models\Organization;
use App\Models\OrganizationUser;
use App\Models\Role;
use App\Models\RoleUser;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function testUserHasManyOrganizationUsers()
    {
        $user = User::factory()->createOneQuietly();
        $organizationUser1 = OrganizationUser::factory()->for($user)->createOneQuietly();
        $organizationUser2 = OrganizationUser::factory()->for($user)->createOneQuietly();

        $this->assertTrue($user->organizationUsers->contains($organizationUser1));
        $this->assertTrue($user->organizationUsers->contains($organizationUser2));
    }

    public function testUserHasManyRoleUser()
    {
        $user = User::factory()->createOneQuietly();
        $roleUser1 = RoleUser::factory()->for($user)->createOneQuietly();
        $roleUser2 = RoleUser::factory()->for($user)->createOneQuietly();

        $this->assertTrue($user->roleUsers->contains($roleUser1));
        $this->assertTrue($user->roleUsers->contains($roleUser2));
    }

    public function testUserHasManyFormAnswers()
    {
        $user = User::factory()->createOneQuietly();
        $formAnswer1 = FormAnswer::factory()->for($user)->createOneQuietly();
        $formAnswer2 = FormAnswer::factory()->for($user)->createOneQuietly();

        $this->assertTrue($user->formAnswers->contains($formAnswer1));
        $this->assertTrue($user->formAnswers->contains($formAnswer2));
    }

    public function testUserBelongsToManyOrganizations()
    {
        $user = User::factory()->create();
        $organizations = Organization::factory(3)->create();
        $user->organizations()->attach($organizations);

        $this->assertEquals(3, $user->organizations()->count());

        $pivotTable = 'organization_users';
        $this->assertDatabaseHas($pivotTable, [
            'user_id' => $user->id,
            'organization_id' => $organizations[0]->id,
        ]);
    }

    public function testUserBelongsToManyRoles()
    {
        $user = User::factory()->create();
        $roles = Role::factory(3)->create();
        $user->roles()->attach($roles);

        $this->assertEquals(3, $user->roles()->count());

        $pivotTable = 'role_users';
        $this->assertDatabaseHas($pivotTable, [
            'user_id' => $user->id,
            'role_id' => $roles[0]->id,
        ]);
    }
}
