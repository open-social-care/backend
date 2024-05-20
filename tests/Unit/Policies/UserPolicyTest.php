<?php

namespace Tests\Unit\Policies;

use App\Enums\RolesEnum;
use App\Models\Organization;
use App\Models\Role;
use App\Models\User;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserPolicyTest extends TestCase
{
    use RefreshDatabase;

    private Role $roleManager;

    private Role $socialAssistant;

    public function setUp(): void
    {
        parent::setUp();

        $this->roleManager = Role::factory()->createQuietly(['name' => RolesEnum::MANAGER->value]);
        $this->socialAssistant = Role::factory()->createQuietly(['name' => RolesEnum::SOCIAL_ASSISTANT->value]);
    }

    public function testViewAnyMethodReturnsTrueForAdminSystemUser()
    {
        $user = $this->createAdminSystemUser();
        $policy = new UserPolicy();

        $result = $policy->viewAny($user);

        $this->assertTrue($result);
    }

    public function testViewAnyMethodReturnsFalseForNonAdminSystemUser()
    {
        $user = $this->createManagerUser();
        $policy = new UserPolicy();

        $result = $policy->viewAny($user);

        $this->assertFalse($result);
    }

    public function testViewMethodReturnsTrueForAdminSystemUser()
    {
        $user = $this->createAdminSystemUser();
        $policy = new UserPolicy();

        $result = $policy->view($user);

        $this->assertTrue($result);
    }

    public function testViewMethodReturnsFalseForNonAdminSystemUser()
    {
        $user = $this->createManagerUser();
        $policy = new UserPolicy();

        $result = $policy->view($user);

        $this->assertFalse($result);
    }

    public function testViewByOrganizationMethodReturnsTrueForAdminSystemUser()
    {
        $user = $this->createAdminSystemUser();
        $organization = Organization::factory()->createOneQuietly();
        $policy = new UserPolicy();

        $result = $policy->viewByOrganization($user, $organization);

        $this->assertTrue($result);
    }

    public function testViewByOrganizationMethodReturnsTrueForManagerOrganizationUser()
    {
        $user = $this->createManagerUser();
        $organization = $user->organizations()->first();
        $policy = new UserPolicy();

        $result = $policy->viewByOrganization($user, $organization);

        $this->assertTrue($result);
    }

    public function testViewByOrganizationMethodReturnsFalseForManagerUserWhenAccessOrganizationWithoutAccess()
    {
        $user = $this->createManagerUser();
        $organization = Organization::factory()->createOneQuietly();
        $policy = new UserPolicy();

        $result = $policy->viewByOrganization($user, $organization);

        $this->assertFalse($result);
    }

    public function testCreateMethodReturnsTrueForAdminSystemUser()
    {
        $user = $this->createAdminSystemUser();
        $policy = new UserPolicy();

        $result = $policy->create($user);

        $this->assertTrue($result);
    }

    public function testCreateAnyMethodReturnsFalseForNonAdminSystemUser()
    {
        $user = $this->createManagerUser();
        $policy = new UserPolicy();

        $result = $policy->create($user);

        $this->assertFalse($result);
    }

    public function testUpdateMethodReturnsTrueForAdminSystemUser()
    {
        $userAdmin = $this->createAdminSystemUser();
        $userManager = $this->createManagerUser();
        $policy = new UserPolicy();

        $result = $policy->update($userAdmin, $userManager);

        $this->assertTrue($result);
    }

    public function testUpdateMethodReturnsTrueForManagerOrganizationUser()
    {
        $userManager = $this->createManagerUser();
        $organization = $userManager->organizations()->first();

        $userSocialAssistant = User::factory()->createQuietly();
        $userSocialAssistant->roles()->attach($this->socialAssistant);
        $userSocialAssistant->organizations()->attach($organization, ['role_id' => $this->socialAssistant->id]);

        $policy = new UserPolicy();

        $result = $policy->update($userManager, $userSocialAssistant);

        $this->assertTrue($result);
    }

    public function testUpdateMethodReturnsFalseForManagerUserWhenAccessOrganizationWithoutAccess()
    {
        $userManager = $this->createManagerUser();

        $organization = Organization::factory()->createOneQuietly();
        $userSocialAssistant = User::factory()->createQuietly();
        $userSocialAssistant->roles()->attach($this->socialAssistant);
        $userSocialAssistant->organizations()->attach($organization, ['role_id' => $this->socialAssistant->id]);

        $policy = new UserPolicy();

        $result = $policy->update($userManager, $userSocialAssistant);

        $this->assertFalse($result);
    }

    public function testDeleteMethodReturnsTrueForAdminSystemUser()
    {
        $user = $this->createAdminSystemUser();
        $policy = new UserPolicy();

        $result = $policy->delete($user);

        $this->assertTrue($result);
    }

    public function testDeleteMethodReturnsFalseForNonAdminSystemUser()
    {
        $user = $this->createManagerUser();
        $policy = new UserPolicy();

        $result = $policy->delete($user);

        $this->assertFalse($result);
    }

    public function testDisassociateUserFromOrganizationMethodReturnsTrueForAdminSystemUser()
    {
        $userAdmin = $this->createAdminSystemUser();
        $userManager = $this->createManagerUser();
        $policy = new UserPolicy();

        $result = $policy->disassociateUserFromOrganization($userAdmin, $userManager);

        $this->assertTrue($result);
    }

    public function testDisassociateUserFromOrganizationMethodReturnsTrueForManagerOrganizationUser()
    {
        $userManager = $this->createManagerUser();
        $organization = $userManager->organizations()->first();

        $userSocialAssistant = User::factory()->createQuietly();
        $userSocialAssistant->roles()->attach($this->socialAssistant);
        $userSocialAssistant->organizations()->attach($organization, ['role_id' => $this->socialAssistant->id]);

        $policy = new UserPolicy();

        $result = $policy->disassociateUserFromOrganization($userManager, $userSocialAssistant);

        $this->assertTrue($result);
    }

    public function testDisassociateUserFromOrganizationMethodWhenAccessOrganizationWithoutAccess()
    {
        $userManager = $this->createManagerUser();

        $organization = Organization::factory()->createOneQuietly();
        $userSocialAssistant = User::factory()->createQuietly();
        $userSocialAssistant->roles()->attach($this->socialAssistant);
        $userSocialAssistant->organizations()->attach($organization, ['role_id' => $this->socialAssistant->id]);

        $policy = new UserPolicy();

        $result = $policy->disassociateUserFromOrganization($userManager, $userSocialAssistant);

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
}
