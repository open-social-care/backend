<?php

namespace Tests\Unit\Models;

use App\Models\Role;
use App\Models\RoleUser;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleTest extends TestCase
{
    use RefreshDatabase;

    public function testRoleHasManyMultipleRoleUsers()
    {
        $role = Role::factory()->createOneQuietly();
        $roleUser1 = RoleUser::factory()->for($role)->createOneQuietly();
        $roleUser2 = RoleUser::factory()->for($role)->createOneQuietly();

        $this->assertTrue($role->roleUsers->contains($roleUser1));
        $this->assertTrue($role->roleUsers->contains($roleUser2));
    }
}
