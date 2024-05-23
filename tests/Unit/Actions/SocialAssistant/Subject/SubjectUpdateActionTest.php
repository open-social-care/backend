<?php

namespace Tests\Unit\Actions\SocialAssistant\Subject;

use App\Actions\SocialAssistant\Subject\SubjectUpdateAction;
use App\DTO\SocialAssistant\SubjectDTO;
use App\Models\Organization;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SubjectUpdateActionTest extends TestCase
{
    use RefreshDatabase;

    public function testExecuteAction()
    {
        $organization = Organization::factory()->createOneQuietly();
        $user = User::factory()->createOneQuietly();
        $subject = Subject::factory()->createOneQuietly();

        $data = $subject->toArray();
        $data['name'] = fake()->name();

        $dto = new SubjectDTO($data, $organization, $user);

        $subjectNameBeforeUpdated = $subject->name;
        SubjectUpdateAction::execute($dto, $subject);

        $subject = $subject->fresh();

        $this->assertNotEquals($subjectNameBeforeUpdated, $subject->name);
        $this->assertDatabaseHas('subjects', ['name' => $subject->name]);
    }
}
