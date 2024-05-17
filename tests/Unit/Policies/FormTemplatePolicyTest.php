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
        $data = $this->createManagerUser();
        $policy = new FormTemplatePolicy();

        $result = $policy->viewAny($data['user']);

        $this->assertFalse($result);
    }

    public function testViewForOrganizationMethodReturnsTrueForManagerOfOrganization()
    {
        $data = $this->createManagerUser();
        $policy = new FormTemplatePolicy();

        $result = $policy->viewForOrganization($data['user'], $data['organization']);

        $this->assertTrue($result);
    }

    public function testViewForOrganizationMethodReturnsFalseForNonManagerOfOrganization()
    {
        $data = $this->createManagerUser();
        $nonUser = User::factory()->createOneQuietly();
        $policy = new FormTemplatePolicy();

        $result = $policy->viewForOrganization($nonUser, $data['organization']);

        $this->assertFalse($result);
    }

    public function testViewMethodReturnsTrueForAdminSystemUser()
    {
        $user = $this->createAdminSystemUser();
        $formTemplate = FormTemplate::factory()->createOneQuietly();
        $policy = new FormTemplatePolicy();

        $result = $policy->view($user, $formTemplate);

        $this->assertTrue($result);
    }

    public function testViewMethodReturnsTrueForManagerUserThatHasCommonOrganization()
    {
        $data = $this->createManagerUser();
        $formTemplate = FormTemplate::factory()->hasAttached($data['organization'])->createOneQuietly();
        $policy = new FormTemplatePolicy();

        $result = $policy->view($data['user'], $formTemplate);

        $this->assertTrue($result);
    }

    public function testViewMethodReturnsFalseForNonAdminSystemOrManagerUser()
    {
        $user = User::factory()->createQuietly();
        $formTemplate = FormTemplate::factory()->createOneQuietly();
        $policy = new FormTemplatePolicy();

        $result = $policy->view($user, $formTemplate);

        $this->assertFalse($result);
    }

    public function testCreateQuestionsForFormTemplateMethodReturnsTrueForManagerThatHasCommonOrganization()
    {
        $data = $this->createManagerUser();
        $formTemplate = FormTemplate::factory()->hasAttached($data['organization'])->createOneQuietly();
        $policy = new FormTemplatePolicy();

        $result = $policy->createQuestionsForFormTemplate($data['user'], $formTemplate);

        $this->assertTrue($result);
    }

    public function testCreateQuestionsForFormTemplateMethodReturnsFalseForNonManagerOfOrganization()
    {
        $data = $this->createManagerUser();
        $formTemplate = FormTemplate::factory()->hasAttached($data['organization'])->createOneQuietly();
        $nonUser = User::factory()->createOneQuietly();
        $policy = new FormTemplatePolicy();

        $result = $policy->createQuestionsForFormTemplate($nonUser, $formTemplate);

        $this->assertFalse($result);
    }

    public function testCreateQuestionsForFormTemplateMethodReturnsTrueForAdminSystemUser()
    {
        $user = $this->createAdminSystemUser();
        $formTemplate = FormTemplate::factory()->createOneQuietly();
        $policy = new FormTemplatePolicy();

        $result = $policy->view($user, $formTemplate);

        $this->assertTrue($result);
    }

    public function testCreateForOrganizationMethodReturnsTrueForAdminSystemUser()
    {
        $user = $this->createAdminSystemUser();
        $organization = Organization::factory()->createOneQuietly();
        $policy = new FormTemplatePolicy();

        $result = $policy->createForOrganization($user, $organization);

        $this->assertTrue($result);
    }

    public function testCreateForOrganizationMethodReturnsTrueForManagerUser()
    {
        $data = $this->createManagerUser();
        $policy = new FormTemplatePolicy();

        $result = $policy->createForOrganization($data['user'], $data['organization']);

        $this->assertTrue($result);
    }

    public function testCreateForOrganizationMethodReturnsFalseForNonAdminSystemOrManagerUsers()
    {
        $user = User::factory()->createQuietly();
        $organization = Organization::factory()->createOneQuietly();
        $policy = new FormTemplatePolicy();

        $result = $policy->createForOrganization($user, $organization);

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
        $data = $this->createManagerUser();
        $formTemplate = FormTemplate::factory()->hasAttached($data['organization'])->createOneQuietly();
        $policy = new FormTemplatePolicy();

        $result = $policy->update($data['user'], $formTemplate);

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
        $data = $this->createManagerUser();
        $formTemplate = FormTemplate::factory()->hasAttached($data['organization'])->createOneQuietly();
        $policy = new FormTemplatePolicy();

        $result = $policy->delete($data['user'], $formTemplate);

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

    private function createManagerUser(): array
    {
        $organization = Organization::factory()->createQuietly();

        $userManager = User::factory()->createQuietly();
        $role = Role::factory()->createQuietly(['name' => RolesEnum::MANAGER->value]);
        $userManager->roles()->attach($role);
        $userManager->organizations()->attach($organization, ['role_id' => $role->id]);

        return [
            'organization' => $organization,
            'user' => $userManager,
        ];
    }
}
