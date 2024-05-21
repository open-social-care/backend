<?php

namespace Tests\Unit\Policies;

use App\Enums\RolesEnum;
use App\Models\Organization;
use App\Models\Role;
use App\Models\User;
use App\Policies\OrganizationPolicy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrganizationPolicyTest extends TestCase
{
    use RefreshDatabase;

    private Role $roleManager;

    private Role $roleSocialAssistant;

    public function setUp(): void
    {
        parent::setUp();

        $this->roleManager = Role::factory()->createQuietly(['name' => RolesEnum::MANAGER->value]);
        $this->roleSocialAssistant = Role::factory()->createQuietly(['name' => RolesEnum::SOCIAL_ASSISTANT->value]);
    }

    public function testViewAnyMethodReturnsTrueForAdminSystemUser()
    {
        $user = $this->createAdminSystemUser();
        $policy = new OrganizationPolicy();

        $result = $policy->viewAny($user);

        $this->assertTrue($result);
    }

    public function testViewAnyMethodReturnsFalseForNonAdminSystemUser()
    {
        $user = $this->createManagerUser();
        $policy = new OrganizationPolicy();

        $result = $policy->viewAny($user);

        $this->assertFalse($result);
    }

    public function testViewMethodReturnsTrueForAdminSystemUser()
    {
        $user = $this->createAdminSystemUser();
        $organization = Organization::factory()->createOneQuietly();
        $policy = new OrganizationPolicy();

        $result = $policy->view($user, $organization);

        $this->assertTrue($result);
    }

    public function testViewMethodReturnsTrueForManagerOrganizationUser()
    {
        $user = $this->createManagerUser();
        $organization = $user->organizations()->first();
        $policy = new OrganizationPolicy();

        $result = $policy->view($user, $organization);

        $this->assertTrue($result);
    }

    public function testViewMethodReturnsFalseForManagerUserWhenAccessOrganizationWithoutAccess()
    {
        $user = $this->createManagerUser();
        $organization = Organization::factory()->createOneQuietly();
        $policy = new OrganizationPolicy();

        $result = $policy->view($user, $organization);

        $this->assertFalse($result);
    }

    public function testViewYoursMethodReturnsTrueForManagerOrganizationUser()
    {
        $user = $this->createManagerUser();
        $policy = new OrganizationPolicy();

        $result = $policy->viewYours($user);

        $this->assertTrue($result);
    }

    public function testViewYoursMethodReturnsTrueForSocialAssistantOrganizationUser()
    {
        $user = $this->createSocialAssistantUser();
        $policy = new OrganizationPolicy();

        $result = $policy->viewYours($user);

        $this->assertTrue($result);
    }

    public function testViewYoursMethodReturnsFalseForNonManagerOrSocialAssistantOrganizationUser()
    {
        $user = $this->createAdminSystemUser();
        $policy = new OrganizationPolicy();

        $result = $policy->viewYours($user);

        $this->assertFalse($result);
    }

    public function testCreateMethodReturnsTrueForAdminSystemUser()
    {
        $user = $this->createAdminSystemUser();
        $policy = new OrganizationPolicy();

        $result = $policy->create($user);

        $this->assertTrue($result);
    }

    public function testCreateAnyMethodReturnsFalseForNonAdminSystemUser()
    {
        $user = $this->createManagerUser();
        $policy = new OrganizationPolicy();

        $result = $policy->create($user);

        $this->assertFalse($result);
    }

    public function testUpdateMethodReturnsTrueForAdminSystemUser()
    {
        $user = $this->createAdminSystemUser();
        $organization = Organization::factory()->createOneQuietly();
        $policy = new OrganizationPolicy();

        $result = $policy->update($user, $organization);

        $this->assertTrue($result);
    }

    public function testUpdateMethodReturnsTrueForManagerOrganizationUser()
    {
        $user = $this->createManagerUser();
        $organization = $user->organizations()->first();
        $policy = new OrganizationPolicy();

        $result = $policy->update($user, $organization);

        $this->assertTrue($result);
    }

    public function testUpdateMethodReturnsFalseForManagerUserWhenAccessOrganizationWithoutAccess()
    {
        $user = $this->createManagerUser();
        $organization = Organization::factory()->createOneQuietly();
        $policy = new OrganizationPolicy();

        $result = $policy->update($user, $organization);

        $this->assertFalse($result);
    }

    public function testDeleteMethodReturnsTrueForAdminSystemUser()
    {
        $user = $this->createAdminSystemUser();
        $organization = Organization::factory()->createOneQuietly();
        $policy = new OrganizationPolicy();

        $result = $policy->delete($user, $organization);

        $this->assertTrue($result);
    }

    public function testDeleteMethodReturnsFalseForNonAdminSystemUser()
    {
        $user = $this->createManagerUser();
        $organization = $user->organizations()->first();
        $policy = new OrganizationPolicy();

        $result = $policy->delete($user, $organization);

        $this->assertFalse($result);
    }

    public function testAssociateUsersMethodReturnsTrueForAdminSystemUser()
    {
        $user = $this->createAdminSystemUser();
        $organization = Organization::factory()->createOneQuietly();
        $policy = new OrganizationPolicy();

        $result = $policy->associateUsers($user, $organization);

        $this->assertTrue($result);
    }

    public function testAssociateUsersMethodReturnsTrueForManagerOrganizationUser()
    {
        $user = $this->createManagerUser();
        $organization = $user->organizations()->first();
        $policy = new OrganizationPolicy();

        $result = $policy->associateUsers($user, $organization);

        $this->assertTrue($result);
    }

    public function testAssociateUsersMethodReturnsFalseForManagerUserWhenAccessOrganizationWithoutAccess()
    {
        $user = $this->createManagerUser();
        $organization = Organization::factory()->createOneQuietly();
        $policy = new OrganizationPolicy();

        $result = $policy->associateUsers($user, $organization);

        $this->assertFalse($result);
    }

    public function testDisassociateUsersMethodReturnsTrueForAdminSystemUser()
    {
        $user = $this->createAdminSystemUser();
        $organization = Organization::factory()->createOneQuietly();
        $policy = new OrganizationPolicy();

        $result = $policy->disassociateUsers($user, $organization);

        $this->assertTrue($result);
    }

    public function testDisassociateUsersMethodReturnsTrueForManagerOrganizationUser()
    {
        $user = $this->createManagerUser();
        $organization = $user->organizations()->first();
        $policy = new OrganizationPolicy();

        $result = $policy->disassociateUsers($user, $organization);

        $this->assertTrue($result);
    }

    public function testDisassociateUsersMethodReturnsFalseForManagerUserWhenAccessOrganizationWithoutAccess()
    {
        $user = $this->createManagerUser();
        $organization = Organization::factory()->createOneQuietly();
        $policy = new OrganizationPolicy();

        $result = $policy->disassociateUsers($user, $organization);

        $this->assertFalse($result);
    }

    private function createAdminSystemUser(): User
    {
        $userAdmin = User::factory()->createQuietly();
        $role = Role::factory()->createQuietly(['name' => RolesEnum::ADMIN->value]);
        $userAdmin->roles()->attach($role);

        return $userAdmin;
    }

    private function createManagerUser(): User
    {
        $organization = Organization::factory()->createQuietly();

        $userManager = User::factory()->createQuietly();
        $userManager->roles()->attach($this->roleManager);
        $userManager->organizations()->attach($organization, ['role_id' => $this->roleManager->id]);

        return $userManager;
    }

    private function createSocialAssistantUser(): User
    {
        $organization = Organization::factory()->createQuietly();

        $userManager = User::factory()->createQuietly();
        $userManager->roles()->attach($this->roleSocialAssistant);
        $userManager->organizations()->attach($organization, ['role_id' => $this->roleSocialAssistant->id]);

        return $userManager;
    }
}
