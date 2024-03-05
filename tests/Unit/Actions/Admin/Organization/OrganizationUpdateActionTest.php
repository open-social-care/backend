<?php

namespace Tests\Unit\Actions\Admin\User;

use App\Actions\Admin\Organization\OrganizationCreateAction;
use App\Actions\Admin\Organization\OrganizationUpdateAction;
use App\Actions\Admin\User\UserUpdateAction;
use App\DTO\Admin\OrganizationDTO;
use App\DTO\Admin\UserDTO;
use App\Enums\DocumentTypesEnum;
use App\Models\Organization;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrganizationUpdateActionTest extends TestCase
{
    use RefreshDatabase;

    public function testExecuteAction()
    {
        $organization = Organization::factory()->createOneQuietly();

        $organizationDto = new OrganizationDTO([
            'name' => fake()->name,
            'phone' => fake()->phone,
            'document_type' => DocumentTypesEnum::CPF->value,
            'document' => fake()->cpf,
            'subject_ref' => 'subject',
        ]);

        $organizationNameBeforeUpdated = $organization->name;
        OrganizationUpdateAction::execute($organizationDto, $organization);

        $organization = $organization->fresh();

        $this->assertNotEquals($organization->name, $organizationNameBeforeUpdated);
        $this->assertDatabaseHas('organizations', ['name' => $organizationDto->name]);
    }
}
