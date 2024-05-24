<?php

namespace Tests\Unit\Policies;

use App\Enums\RolesEnum;
use App\Models\FormAnswer;
use App\Models\FormTemplate;
use App\Models\Organization;
use App\Models\Role;
use App\Models\Subject;
use App\Models\User;
use App\Policies\FormAnswerPolicy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FormAnswerPolicyTest extends TestCase
{
    use RefreshDatabase;

    private Role $roleSocialAssistant;

    public function setUp(): void
    {
        parent::setUp();

        $this->roleSocialAssistant = Role::factory()->createQuietly(['name' => RolesEnum::SOCIAL_ASSISTANT->value]);
    }

    public function testViewMethodReturnsTrueForAdminSystem()
    {
        $user = $this->createAdminSystemUser();
        $formAnswer = FormAnswer::factory()->for($user)->createOneQuietly();
        $policy = new FormAnswerPolicy();

        $result = $policy->view($user, $formAnswer);

        $this->assertTrue($result);
    }

    public function testViewMethodReturnsTrueForUserWithCommonOrganization()
    {
        $data = $this->createSocialAssistantUser();
        $user = $data['user'];

        $formTemplate = FormTemplate::factory()->hasAttached($data['organization'])->createOneQuietly();
        $formAnswer = FormAnswer::factory()->for($formTemplate)->createOneQuietly(['user_id' => $user->id]);
        $policy = new FormAnswerPolicy();

        $result = $policy->view($user, $formAnswer);

        $this->assertTrue($result);
    }

    public function testViewMethodReturnsFalseForUserWithoutCommonOrganization()
    {
        $data = $this->createSocialAssistantUser();
        $user = User::factory()->createOneQuietly();

        $formTemplate = FormTemplate::factory()->hasAttached($data['organization'])->createOneQuietly();
        $formAnswer = FormAnswer::factory()->for($formTemplate)->createOneQuietly(['user_id' => $user->id]);
        $policy = new FormAnswerPolicy();

        $result = $policy->view($user, $formAnswer);

        $this->assertFalse($result);
    }

    public function testViewBySubjectMethodReturnsTrueForAdminSystem()
    {
        $user = $this->createAdminSystemUser();
        $formAnswer = FormAnswer::factory()->for($user)->createOneQuietly();
        $policy = new FormAnswerPolicy();

        $result = $policy->viewBySubject($user, $formAnswer->subject);

        $this->assertTrue($result);
    }

    public function testViewBySubjectMethodReturnsTrueForUserWithCommonOrganization()
    {
        $data = $this->createSocialAssistantUser();
        $user = $data['user'];
        $organization = $data['organization'];
        $subject = Subject::factory()->createOneQuietly(['organization_id' => $organization->id]);
        $policy = new FormAnswerPolicy();

        $result = $policy->viewBySubject($user, $subject);

        $this->assertTrue($result);
    }

    public function testViewBySubjectMethodReturnsFalseForUserWithoutCommonOrganization()
    {
        $data = $this->createSocialAssistantUser();
        $user = User::factory()->createOneQuietly();
        $organization = $data['organization'];
        $subject = Subject::factory()->createOneQuietly(['organization_id' => $organization->id]);
        $policy = new FormAnswerPolicy();

        $result = $policy->viewBySubject($user, $subject);

        $this->assertFalse($result);
    }

    public function testCreateBySubjectMethodReturnsTrueForAdminSystem()
    {
        $user = $this->createAdminSystemUser();
        $formAnswer = FormAnswer::factory()->for($user)->createOneQuietly();
        $policy = new FormAnswerPolicy();

        $result = $policy->createBySubject($user, $formAnswer->subject);

        $this->assertTrue($result);
    }

    public function testCreateBySubjectMethodReturnsTrueForUserWithCommonOrganization()
    {
        $data = $this->createSocialAssistantUser();
        $user = $data['user'];
        $organization = $data['organization'];
        $subject = Subject::factory()->createOneQuietly(['organization_id' => $organization->id]);
        $policy = new FormAnswerPolicy();

        $result = $policy->createBySubject($user, $subject);

        $this->assertTrue($result);
    }

    public function testCreateBySubjectMethodReturnsFalseForUserWithoutCommonOrganization()
    {
        $data = $this->createSocialAssistantUser();
        $user = User::factory()->createOneQuietly();
        $organization = $data['organization'];
        $subject = Subject::factory()->createOneQuietly(['organization_id' => $organization->id]);
        $policy = new FormAnswerPolicy();

        $result = $policy->createBySubject($user, $subject);

        $this->assertFalse($result);
    }

    public function testDeleteMethodReturnsTrueForAdminSystem()
    {
        $user = $this->createAdminSystemUser();
        $formAnswer = FormAnswer::factory()->for($user)->createOneQuietly();
        $policy = new FormAnswerPolicy();

        $result = $policy->delete($user, $formAnswer);

        $this->assertTrue($result);
    }

    public function testDeleteMethodReturnsTrueForUserWithCommonOrganization()
    {
        $data = $this->createSocialAssistantUser();
        $user = $data['user'];

        $formTemplate = FormTemplate::factory()->hasAttached($data['organization'])->createOneQuietly();
        $formAnswer = FormAnswer::factory()->for($formTemplate)->createOneQuietly(['user_id' => $user->id]);
        $policy = new FormAnswerPolicy();

        $result = $policy->delete($user, $formAnswer);

        $this->assertTrue($result);
    }

    public function testDeleteMethodReturnsFalseForUserWithoutCommonOrganization()
    {
        $data = $this->createSocialAssistantUser();
        $user = User::factory()->createOneQuietly();

        $formTemplate = FormTemplate::factory()->hasAttached($data['organization'])->createOneQuietly();
        $formAnswer = FormAnswer::factory()->for($formTemplate)->createOneQuietly(['user_id' => $user->id]);
        $policy = new FormAnswerPolicy();

        $result = $policy->delete($user, $formAnswer);

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
        $userSocialAssistant = User::factory()->createQuietly();
        $userSocialAssistant->roles()->attach($this->roleSocialAssistant);
        $userSocialAssistant->organizations()->attach($organization, ['role_id' => $this->roleSocialAssistant->id]);

        return [
            'organization' => $organization,
            'user' => $userSocialAssistant,
        ];
    }
}
