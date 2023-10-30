<?php

namespace Tests\Unit\Models;

use App\Models\Organization;
use App\Models\OrganizationUser;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrganizationUserTest extends TestCase
{
    use RefreshDatabase;

    public function testOrganizationUserBelongsToOrganization()
    {
        $organization = Organization::factory()->createOneQuietly();
        $organizationUser = OrganizationUser::factory()->for($organization)->createOneQuietly();

        $this->assertEquals($organization->id, $organizationUser->organization_id);
    }

    public function testOrganizationUserBelongsToUser()
    {
        $user = User::factory()->createOneQuietly();
        $organizationUser = OrganizationUser::factory()->for($user)->createOneQuietly();

        $this->assertEquals($user->id, $organizationUser->user_id);
    }
}
