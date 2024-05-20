<?php

namespace Feature\Controllers\Api\SocialAssistant;

use App\Enums\RolesEnum;
use App\Http\Resources\Api\SocialAssistant\SubjectResource;
use App\Models\Organization;
use App\Models\Role;
use App\Models\State;
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
        $subjects = Subject::factory()->for($this->organization)->count(5)->createQuietly();

        $response = $this->getJson(route('social-assistant.subjects.index', ['organization' => $this->organization->id]));

        $response->assertStatus(HttpResponse::HTTP_OK)
            ->assertJsonStructure(['data', 'pagination']);

        foreach ($subjects as $subject) {
            $response->assertJsonFragment(['name' => $subject->name]);
        }
    }

    public function testIndexMethodWithSearchTerm()
    {
        Subject::factory()->for($this->organization)->count(5)->createQuietly();
        $subjectSearch = Subject::factory()->for($this->organization)->createOneQuietly(['name' => 'search subject']);

        $response = $this->getJson(route('social-assistant.subjects.index', [
            'organization' => $this->organization->id,
            'q' => $subjectSearch->name,
        ]));

        $response->assertStatus(HttpResponse::HTTP_OK)
            ->assertJsonStructure(['data', 'pagination'])
            ->assertJsonFragment(['name' => $subjectSearch->name]);
    }

    public function testIndexMethodWithSearchTermWhenDontHaveContent()
    {
        Subject::factory()->for($this->organization)->count(5)->createQuietly();
        $subjectNotSearch = Subject::factory()->for($this->organization)->createOneQuietly(['name' => 'search subject']);

        $response = $this->getJson(route('social-assistant.subjects.index', [
            'organization' => $this->organization->id,
            'q' => 'subjects',
        ]));

        $response->assertStatus(HttpResponse::HTTP_OK)
            ->assertJsonStructure(['data', 'pagination'])
            ->assertJsonCount(0, 'data')
            ->assertJsonMissing(['name' => $subjectNotSearch->name]);
    }

    public function testIndexMethodWhenUserCantAccessOrganization()
    {
        $organization = Organization::factory()->createQuietly();
        $user = User::factory()->createQuietly();
        $roleSocialAssistant = Role::factory()->createQuietly(['name' => RolesEnum::SOCIAL_ASSISTANT->value]);
        $user->roles()->attach($roleSocialAssistant);
        $user->organizations()->attach($organization, ['role_id' => $roleSocialAssistant->id]);
        $this->actingAs($user);

        $response = $this->getJson(route('social-assistant.subjects.index', ['organization' => $this->organization->id]));

        $response->assertStatus(HttpResponse::HTTP_FORBIDDEN);
    }

    public function testStoreMethod()
    {
        $data = [
            'name' => 'subject test name',
            'birth_date' => fake()->date(),
            'nationality' => fake()->name(),
            'phone' => fake()->phoneNumber(),
            'father_name' => fake()->name(),
            'mother_name' => fake()->name(),
        ];

        $response = $this->postJson(route('social-assistant.subjects.store', ['organization' => $this->organization->id]), $data);

        $response->assertStatus(HttpResponse::HTTP_OK)
            ->assertJson(['message' => __('messages.common.success_create')]);

        $this->assertDatabaseHas('subjects', ['name' => 'subject test name']);
    }

    public function testStoreMethodValidation()
    {
        $response = $this->postJson(route('social-assistant.subjects.store', ['organization' => $this->organization->id]), []);

        $response->assertStatus(HttpResponse::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                'message',
                'errors' => [
                    'name',
                ],
            ]);
    }

    public function testStoreMethodWhenUserCantAccessOrganization()
    {
        $organization = Organization::factory()->createQuietly();
        $user = User::factory()->createQuietly();
        $roleSocialAssistant = Role::factory()->createQuietly(['name' => RolesEnum::SOCIAL_ASSISTANT->value]);
        $user->roles()->attach($roleSocialAssistant);
        $user->organizations()->attach($organization, ['role_id' => $roleSocialAssistant->id]);
        $this->actingAs($user);

        $data = [
            'name' => 'subject',
        ];

        $response = $this->postJson(route('social-assistant.subjects.store', ['organization' => $this->organization->id]), $data);

        $response->assertStatus(HttpResponse::HTTP_FORBIDDEN);
    }

    public function testUpdateMethod()
    {
        $subject = Subject::factory()->for($this->organization)->createOneQuietly();

        $updatedData = [
            'name' => 'New name test',
        ];

        $updatedData = array_merge($subject->toArray(), $updatedData);
        $response = $this->putJson(route('social-assistant.subjects.update', ['subject' => $subject->id]), $updatedData);

        $response->assertStatus(HttpResponse::HTTP_OK)
            ->assertJson(['message' => __('messages.common.success_update')]);

        $subject = $subject->fresh();
        $this->assertEquals($updatedData['name'], $subject->name);
    }

    public function testUpdateMethodValidation()
    {
        $subject = Subject::factory()->for($this->organization)->createOneQuietly();

        $response = $this->putJson(route('social-assistant.subjects.update', $subject->id), []);
        $response->assertStatus(HttpResponse::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                'message',
                'errors' => [
                    'name',
                ],
            ]);
    }

    public function testUpdateMethodWhenUserCantAccessOrganization()
    {
        $organization = Organization::factory()->createQuietly();
        $user = User::factory()->createQuietly();
        $roleSocialAssistant = Role::factory()->createQuietly(['name' => RolesEnum::SOCIAL_ASSISTANT->value]);
        $user->roles()->attach($roleSocialAssistant);
        $user->organizations()->attach($organization, ['role_id' => $roleSocialAssistant->id]);
        $this->actingAs($user);

        $subject = Subject::factory()->for($this->organization)->createOneQuietly();

        $updatedData = [
            'name' => 'New name test',
        ];

        $updatedData = array_merge($subject->toArray(), $updatedData);
        $response = $this->putJson(route('social-assistant.subjects.update', ['subject' => $subject->id]), $updatedData);

        $response->assertStatus(HttpResponse::HTTP_FORBIDDEN);
    }

    public function testShowMethod()
    {
        $subject = Subject::factory()->for($this->organization)->createOneQuietly();

        $response = $this->getJson(route('social-assistant.subjects.show', $subject->id));

        $response->assertStatus(HttpResponse::HTTP_OK)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'name',
                    'birth_date',
                ],
            ]);

        $expectedData = SubjectResource::make($subject)->jsonSerialize();
        $response->assertJsonFragment(['data' => $expectedData]);
    }

    public function testShowMethodWhenUserCantAccessOrganization()
    {
        $organization = Organization::factory()->createQuietly();
        $user = User::factory()->createQuietly();
        $roleSocialAssistant = Role::factory()->createQuietly(['name' => RolesEnum::SOCIAL_ASSISTANT->value]);
        $user->roles()->attach($roleSocialAssistant);
        $user->organizations()->attach($organization, ['role_id' => $roleSocialAssistant->id]);
        $this->actingAs($user);

        $subject = Subject::factory()->for($this->organization)->createOneQuietly();

        $response = $this->getJson(route('social-assistant.subjects.show', $subject->id));

        $response->assertStatus(HttpResponse::HTTP_FORBIDDEN);
    }

    public function testGetFormInfosMethod()
    {
        $response = $this->getJson(route('social-assistant.subjects.get-form-infos'));

        $response->assertStatus(HttpResponse::HTTP_OK)
            ->assertJsonStructure([
                'data' => [
                    'skinColors',
                ],
            ]);
    }
}
