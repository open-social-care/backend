<?php

namespace Feature\Controllers\Api\SocialAssistant;

use App\Enums\RolesEnum;
use App\Http\Resources\Api\Shared\UserListWithRolesResource;
use App\Models\Organization;
use App\Models\Role;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response as HttpResponse;
use Tests\TestCase;

class SocialAssistantSubjectControllerTest extends TestCase
{
    use RefreshDatabase;

    protected Organization $organization;

    protected User $userSocialAssistant;

    public function setUp(): void
    {
        parent::setUp();

        $this->organization = Organization::factory()->createQuietly();

        $this->userSocialAssistant = User::factory()->createQuietly();
        $roleSocialAssistant = Role::factory()->createQuietly(['name' => RolesEnum::SOCIAL_ASSISTANT->value]);
        $this->userSocialAssistant->roles()->attach($roleSocialAssistant);
        $this->userSocialAssistant->organizations()->attach($this->organization, ['role_id' => $roleSocialAssistant->id]);
        $this->actingAs($this->userSocialAssistant);
    }

    public function testIndexMethod()
    {
        $subjects = Subject::factory()->count(5)->createQuietly(['organization_id' => $this->organization->id]);

        $response = $this->getJson(route('social-assistant.subjects.index', ['organization' => $this->organization->id]));

        $response->assertStatus(HttpResponse::HTTP_OK)
            ->assertJsonStructure(['data', 'pagination']);

        foreach ($subjects as $subject) {
            $response->assertJsonFragment(['name' => $subject->name]);
        }
    }
}
