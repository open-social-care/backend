<?php

namespace Feature\Controllers\Api\SocialAssistant;

use App\Enums\RolesEnum;
use App\Models\Organization;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response as HttpResponse;
use Tests\TestCase;

class SocialAssistantOrganizationControllerTest extends TestCase
{
    use RefreshDatabase;

    protected Organization $organization;

    protected User $userSocialAssistant;

    private Role $roleSocialAssistant;

    public function setUp(): void
    {
        parent::setUp();

        $this->organization = Organization::factory()->createQuietly();

        $this->userSocialAssistant = User::factory()->createQuietly();
        $this->roleSocialAssistant = Role::factory()->createQuietly(['name' => RolesEnum::SOCIAL_ASSISTANT->value]);
        $this->userSocialAssistant->roles()->attach($this->roleSocialAssistant);
        $this->userSocialAssistant->organizations()->attach($this->organization, ['role_id' => $this->roleSocialAssistant->id]);
        $this->actingAs($this->userSocialAssistant);
    }

    public function testIndexMethod()
    {
        $response = $this->getJson(route('social-assistant.organizations.index'));

        $response->assertStatus(HttpResponse::HTTP_OK)
            ->assertJsonStructure(['data', 'pagination']);

        $response->assertJsonFragment(['name' => $this->organization->name]);
    }

    public function testIndexMethodWithSearchTerm()
    {
        $organization = Organization::factory()->createOneQuietly(['name' => 'Test search']);
        $this->userSocialAssistant->organizations()->attach($organization, ['role_id' => $this->roleSocialAssistant->id]);

        $response = $this->getJson(route('social-assistant.organizations.index', ['q' => 'Test search']));

        $response->assertStatus(HttpResponse::HTTP_OK)
            ->assertJsonStructure(['data', 'pagination'])
            ->assertJsonFragment(['name' => $organization->name]);
    }

    public function testIndexMethodWithSearchTermWhenDontHaveContent()
    {
        $organization = Organization::factory()->createOneQuietly(['name' => 'Test search']);
        $this->userSocialAssistant->organizations()->attach($organization, ['role_id' => $this->roleSocialAssistant->id]);

        $response = $this->getJson(route('social-assistant.organizations.index', ['q' => 'null organization']));

        $response->assertStatus(HttpResponse::HTTP_OK)
            ->assertJsonStructure(['data', 'pagination'])
            ->assertJsonCount(0, 'data')
            ->assertJsonMissing(['name' => $organization->name]);
    }

    public function testIndexMethodWhenUserCantAccessInformation()
    {
        $user = User::factory()->createQuietly();
        $this->actingAs($user);

        $response = $this->getJson(route('social-assistant.organizations.index'));

        $response->assertStatus(HttpResponse::HTTP_FORBIDDEN);
    }

    public function testShowMethod()
    {
        $response = $this->getJson(route('social-assistant.organizations.show', $this->organization->id));

        $response->assertStatus(HttpResponse::HTTP_OK)
            ->assertJsonStructure(['data']);

        $response->assertJsonFragment(['name' => $this->organization->name]);
    }

    public function testShowMethodWhenUserCantAccessOrganization()
    {
        $organization = Organization::factory()->createQuietly();
        $user = User::factory()->createQuietly();
        $roleManager = Role::factory()->createQuietly(['name' => RolesEnum::MANAGER->value]);
        $user->roles()->attach($roleManager);
        $user->organizations()->attach($organization, ['role_id' => $roleManager->id]);
        $this->actingAs($user);

        $response = $this->getJson(route('social-assistant.organizations.show', $this->organization->id));

        $response->assertStatus(HttpResponse::HTTP_FORBIDDEN);
    }
}
