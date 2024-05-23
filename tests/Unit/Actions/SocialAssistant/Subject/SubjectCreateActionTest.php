<?php

namespace Tests\Unit\Actions\SocialAssistant\Subject;

use App\Actions\SocialAssistant\Subject\SubjectCreateAction;
use App\DTO\SocialAssistant\SubjectDTO;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SubjectCreateActionTest extends TestCase
{
    use RefreshDatabase;

    public function testExecuteAction()
    {
        $data = [
            'name' => fake()->name,
            'birth_date' => fake()->date(),
        ];

        $organization = Organization::factory()->createOneQuietly();
        $user = User::factory()->createOneQuietly();

        $dto = new SubjectDTO($data, $organization, $user);

        SubjectCreateAction::execute($dto);

        $this->assertDatabaseHas('subjects', ['name' => $dto->name]);
    }
}
