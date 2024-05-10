<?php

namespace Tests\Unit\Policies;

use App\Enums\RolesEnum;
use App\Models\FormTemplate;
use App\Models\Organization;
use App\Models\Role;
use App\Models\User;
use App\Policies\FormTemplatePolicy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FormTemplatePolicyTest extends TestCase
{
    use RefreshDatabase;

    public function testViewAnyMethodReturnsTrueForAdminSystemUser()
    {
        $user = $this->createAdminSystemUser();
        $policy = new FormTemplatePolicy();

        $result = $policy->viewAny($user);

        $this->assertTrue($result);
    }

    public function testViewAnyMethodReturnsFalseForNonAdminSystemUser()
    {
        $user = $this->createManagerUser();
        $policy = new FormTemplatePolicy();

        $result = $policy->viewAny($user);

        $this->assertFalse($result);
    }

    public function testViewMethodReturnsTrueForAdminSystemUser()
    {
        $user = $this->createAdminSystemUser();
        $policy = new FormTemplatePolicy();

        $result = $policy->view($user);

        $this->assertTrue($result);
    }

    public function testViewMethodReturnsTrueForManagerUser()
    {
        $user = $this->createManagerUser();
        $policy = new FormTemplatePolicy();

        $result = $policy->view($user);

        $this->assertTrue($result);
    }

    public function testViewMethodReturnsFalseForNonAdminSystemOrManagerUser()
    {
        $user = User::factory()->createQuietly();
        $policy = new FormTemplatePolicy();

        $result = $policy->view($user);

        $this->assertFalse($result);
    }

    public function testCreateMethodReturnsTrueForAdminSystemUser()
    {
        $user = $this->createAdminSystemUser();
        $policy = new FormTemplatePolicy();

        $result = $policy->create($user);

        $this->assertTrue($result);
    }

    public function testCreateMethodReturnsTrueForManagerUser()
    {
        $user = $this->createManagerUser();
        $policy = new FormTemplatePolicy();

        $result = $policy->create($user);

        $this->assertTrue($result);
    }

    public function testCreateAnyMethodReturnsFalseForNonAdminSystemOrManagerUsers()
    {
        $user = User::factory()->createQuietly();
        $policy = new FormTemplatePolicy();

        $result = $policy->create($user);

        $this->assertFalse($result);
    }

    public function testUpdateMethodReturnsTrueForAdminSystemUser()
    {
        $user = $this->createAdminSystemUser();
        $formTemplate = FormTemplate::factory()->createOneQuietly();
        $policy = new FormTemplatePolicy();

        $result = $policy->update($user, $formTemplate);

        $this->assertTrue($result);
    }

    public function testUpdateMethodReturnsTrueForManagerUser()
    {
        $user = $this->createManagerUser();
        $formTemplate = FormTemplate::factory()->createOneQuietly();
        $policy = new FormTemplatePolicy();

        $result = $policy->update($user, $formTemplate);

        $this->assertTrue($result);
    }

    public function testUpdateMethodReturnsFalseForNonAdminSystemOrManagerUsers()
    {
        $user = User::factory()->createQuietly();
        $formTemplate = FormTemplate::factory()->createOneQuietly();
        $policy = new FormTemplatePolicy();

        $result = $policy->update($user, $formTemplate);

        $this->assertFalse($result);
    }

    public function testDeleteMethodReturnsTrueForAdminSystemUser()
    {
        $user = $this->createAdminSystemUser();
        $formTemplate = FormTemplate::factory()->createOneQuietly();
        $policy = new FormTemplatePolicy();

        $result = $policy->delete($user, $formTemplate);

        $this->assertTrue($result);
    }

    public function testDeleteMethodReturnsTrueForManagerUser()
    {
        $user = $this->createManagerUser();
        $formTemplate = FormTemplate::factory()->createOneQuietly();
        $policy = new FormTemplatePolicy();

        $result = $policy->delete($user, $formTemplate);

        $this->assertTrue($result);
    }

    public function testDeleteMethodReturnsFalseForNonAdminSystemOrManagerUsers()
    {
        $user = User::factory()->createQuietly();
        $formTemplate = FormTemplate::factory()->createOneQuietly();
        $policy = new FormTemplatePolicy();

        $result = $policy->delete($user, $formTemplate);

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
        $role = Role::factory()->createQuietly(['name' => RolesEnum::MANAGER->value]);
        $userManager->roles()->attach($role);
        $userManager->organizations()->attach($organization, ['role_id' => $role->id]);

        return $userManager;
    }
}
