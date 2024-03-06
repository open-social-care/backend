<?php

namespace Tests\Unit\Actions\Admin\Organization;

use App\Actions\Admin\Organization\OrganizationCreateAction;
use App\DTO\Admin\OrganizationDTO;
use App\Enums\DocumentTypesEnum;
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
