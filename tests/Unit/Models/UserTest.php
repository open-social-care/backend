<?php

namespace Tests\Unit\Models;

use App\Models\FormAnswer;
use App\Models\OrganizationUser;
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
}
