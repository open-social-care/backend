<?php

namespace Tests\Unit\Models;

use App\Models\Role;
use App\Models\RoleUser;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleUserTest extends TestCase
{
    use RefreshDatabase;

    public function testRoleUserBelongsToRole()
    {
        $role = Role::factory()->createOneQuietly();
        $roleUser = RoleUser::factory()->for($role)->createOneQuietly();

        $this->assertEquals($role->id, $roleUser->role_id);
    }

    public function testRoleUserBelongsToUser()
    {
        $user = User::factory()->createOneQuietly();
        $roleUser = RoleUser::factory()->for($user)->createOneQuietly();

        $this->assertEquals($user->id, $roleUser->user_id);
    }
}
