<?php

namespace Tests\Unit\Models;

use App\Models\Address;
use App\Models\FormAnswer;
use App\Models\Subject;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SubjectTest extends TestCase
{
    use RefreshDatabase;

    public function testShortQuestionHasManyFormAnswer()
    {
        $subject = Subject::factory()->createOneQuietly();
        $formAnswer1 = FormAnswer::factory()->for($subject)->createOneQuietly();
        $formAnswer2 = FormAnswer::factory()->for($subject)->createOneQuietly();

        $this->assertTrue($subject->formAnswers->contains($formAnswer1));
        $this->assertTrue($subject->formAnswers->contains($formAnswer2));
    }

    public function testSubjectMorphManyAddresses()
    {
        $subject = Subject::factory()->createOneQuietly();

        $address1 = Address::factory()->createOneQuietly([
            'model_id' => $subject->id,
            'model_type' => Subject::class,
        ]);

        $address2 = Address::factory()->createOneQuietly([
            'model_id' => $subject->id,
            'model_type' => Subject::class,
        ]);

        $this->assertTrue($subject->addresses->contains($address1));
        $this->assertTrue($subject->addresses->contains($address2));
    }
}
