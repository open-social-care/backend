<?php

namespace Tests\Feature\Controllers\Api\Manager;

use App\Enums\RolesEnum;
use App\Models\Organization;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response as HttpResponse;
use Tests\TestCase;

class OrganizationControllerTest extends TestCase
{
    use RefreshDatabase;

    protected Organization $organization;

    protected User $userManager;

    public function setUp(): void
    {
        parent::setUp();

        $this->organization = Organization::factory()->createQuietly();

        $this->userManager = User::factory()->createQuietly();
        $role = Role::factory()->createQuietly(['name' => RolesEnum::MANAGER->value]);
        $this->userManager->roles()->attach($role);
        $this->userManager->organizations()->attach($this->organization);
        $this->actingAs($this->userManager);
    }

    public function testGetOrganizationInfo()
    {
        $response = $this->getJson(route('manager.organizations.get-info', $this->organization->id));

        $response->assertStatus(HttpResponse::HTTP_OK)
            ->assertJsonStructure(['organization']);

        $response->assertJsonFragment(['name' => $this->organization->name]);
    }

    public function testUpdateMethod()
    {
        $updatedData = [
            'name' => 'New name organization',
            'phone' => '(42) 3035-4135',
            'document_type' => 'cpf',
            'document' => '014.431.840-71',
        ];

        $response = $this->putJson(route('manager.organizations.update', $this->organization->id), $updatedData);

        $response->assertStatus(HttpResponse::HTTP_OK)
            ->assertJson(['message' => __('messages.common.success_update')]);

        $this->organization = $this->organization->fresh();
        $this->assertEquals($updatedData['name'], $this->organization->name);
    }

    public function testGetOrganizationUsersListByRole()
    {
        $response = $this->getJson(route('manager.organizations.get-users-by-role', [
            'organization' => $this->organization->id,
            'role' => RolesEnum::MANAGER->value,
        ]));

        $response->assertStatus(HttpResponse::HTTP_OK)
            ->assertJsonStructure(['data', 'pagination']);

        $this->assertCount(1, $response->json('data'));
        $response->assertJsonFragment(['name' => $this->userManager->name]);
    }
}
