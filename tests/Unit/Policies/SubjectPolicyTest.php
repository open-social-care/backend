<?php

namespace Tests\Unit\Policies;

use App\Enums\RolesEnum;
use App\Models\Organization;
use App\Models\Role;
use App\Models\Subject;
use App\Models\User;
use App\Policies\SubjectPolicy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SubjectPolicyTest extends TestCase
{
    use RefreshDatabase;

    public function testViewByOrganizationMethodReturnsTrueForAdminSystemUser()
    {
        $user = $this->createAdminSystemUser();
        $organization = Organization::factory()->createOneQuietly();
        $policy = new SubjectPolicy();

        $result = $policy->viewByOrganization($user, $organization);

        $this->assertTrue($result);
    }

    public function testViewByOrganizationMethodReturnsTrueForSocialAssistantOfOrganization()
    {
        $data = $this->createSocialAssistantUser();
        $policy = new SubjectPolicy();

        $result = $policy->viewByOrganization($data['user'], $data['organization']);

        $this->assertTrue($result);
    }

    public function testViewByOrganizationMethodReturnsFalseForNonSocialAssistantOfOrganizationOrAdmin()
    {
        $user = User::factory()->createOneQuietly();
        $organization = Organization::factory()->createOneQuietly();
        $policy = new SubjectPolicy();

        $result = $policy->viewByOrganization($user, $organization);

        $this->assertFalse($result);
    }

    public function testCreateByOrganizationMethodReturnsTrueForAdminSystemUser()
    {
        $user = $this->createAdminSystemUser();
        $organization = Organization::factory()->createOneQuietly();
        $policy = new SubjectPolicy();

        $result = $policy->createByOrganization($user, $organization);

        $this->assertTrue($result);
    }

    public function testCreateByOrganizationMethodReturnsTrueForSocialAssistantOfOrganization()
    {
        $data = $this->createSocialAssistantUser();
        $policy = new SubjectPolicy();

        $result = $policy->viewByOrganization($data['user'], $data['organization']);

        $this->assertTrue($result);
    }

    public function testCreateByOrganizationMethodReturnsFalseForNonSocialAssistantOfOrganizationOrAdmin()
    {
        $user = User::factory()->createOneQuietly();
        $organization = Organization::factory()->createOneQuietly();
        $policy = new SubjectPolicy();

        $result = $policy->viewByOrganization($user, $organization);

        $this->assertFalse($result);
    }

    public function testUpdateMethodReturnsTrueForAdminSystemUser()
    {
        $user = $this->createAdminSystemUser();
        $subject = Subject::factory()->createOneQuietly();
        $policy = new SubjectPolicy();

        $result = $policy->update($user, $subject);

        $this->assertTrue($result);
    }

    public function testUpdateMethodReturnsTrueForSocialAssistantOfOrganization()
    {
        $data = $this->createSocialAssistantUser();
        $subject = Subject::factory()->for($data['organization'])->createOneQuietly();
        $policy = new SubjectPolicy();

        $result = $policy->update($data['user'], $subject);

        $this->assertTrue($result);
    }

    public function testUpdateMethodReturnsFalseForNonSocialAssistantOfOrganizationOrAdmin()
    {
        $user = User::factory()->createOneQuietly();
        $subject = Subject::factory()->createOneQuietly();
        $policy = new SubjectPolicy();

        $result = $policy->update($user, $subject);

        $this->assertFalse($result);
    }

    private function createAdminSystemUser(): User
    {
        $userAdmin = User::factory()->createQuietly();
        $role = Role::factory()->createQuietly(['name' => RolesEnum::ADMIN->value]);
        $userAdmin->roles()->attach($role);

        return $userAdmin;
    }

    private function createSocialAssistantUser(): array
    {
        $organization = Organization::factory()->createQuietly();

        $user = User::factory()->createQuietly();
        $role = Role::factory()->createQuietly(['name' => RolesEnum::SOCIAL_ASSISTANT->value]);
        $user->roles()->attach($role);
        $user->organizations()->attach($organization, ['role_id' => $role->id]);

        return [
            'organization' => $organization,
            'user' => $user,
        ];
    }
}
