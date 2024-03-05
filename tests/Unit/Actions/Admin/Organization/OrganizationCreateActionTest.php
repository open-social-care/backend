<?php

namespace Tests\Unit\Actions\Admin\User;

use App\Actions\Admin\Organization\OrganizationCreateAction;
use App\Actions\Admin\User\UserCreateAction;
use App\DTO\Admin\OrganizationDTO;
use App\DTO\Admin\UserDTO;
use App\Enums\DocumentTypesEnum;
use App\Models\Organization;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrganizationCreateActionTest extends TestCase
{
    use RefreshDatabase;

    public function testExecuteAction()
    {
        $organizationDto = new OrganizationDTO([
            'name' => fake()->name,
            'phone' => fake()->phone,
            'document_type' => DocumentTypesEnum::CPF->value,
            'document' => fake()->cpf,
            'subject_ref' => 'subject',
        ]);

        OrganizationCreateAction::execute($organizationDto);

        $this->assertDatabaseHas('organizations', ['name' => $organizationDto->name]);
    }
}
