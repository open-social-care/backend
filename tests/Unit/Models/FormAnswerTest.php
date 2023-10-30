<?php

namespace Tests\Unit\Models;

use App\Models\FileUpload;
use App\Models\FormAnswer;
use App\Models\FormTemplate;
use App\Models\MultipleChoiceAnswer;
use App\Models\PostAnswerNote;
use App\Models\ShortAnswer;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FormAnswerTest extends TestCase
{
    use RefreshDatabase;

    public function testFormAnswerBelongsToUser()
    {
        $user = User::factory()->createOneQuietly();
        $formAnswer = FormAnswer::factory()->for($user)->createOneQuietly();

        $this->assertEquals($user->id, $formAnswer->user_id);
    }

    public function testFormAnswerBelongsToSubject()
    {
        $subject = Subject::factory()->createOneQuietly();
        $formAnswer = FormAnswer::factory()->for($subject)->createOneQuietly();

        $this->assertEquals($subject->id, $formAnswer->subject_id);
    }

    public function testFormAnswerBelongsToFormTemplate()
    {
        $formTemplate = FormTemplate::factory()->createOneQuietly();
        $formAnswer = FormAnswer::factory()->for($formTemplate)->createOneQuietly();

        $this->assertEquals($formTemplate->id, $formAnswer->form_template_id);
    }

    public function testFormAnswerHasManyShortAnswers()
    {
        $formAnswer = FormAnswer::factory()->createOneQuietly();
        $shortAnswer1 = ShortAnswer::factory()->for($formAnswer)->createOneQuietly();
        $shortAnswer2 = ShortAnswer::factory()->for($formAnswer)->createOneQuietly();

        $this->assertTrue($formAnswer->shortAnswers->contains($shortAnswer1));
        $this->assertTrue($formAnswer->shortAnswers->contains($shortAnswer2));
    }

    public function testFormAnswerHasManyMultipleChoiceAnswers()
    {
        $formAnswer = FormAnswer::factory()->createOneQuietly();
        $multipleChoiceAnswer1 = MultipleChoiceAnswer::factory()->for($formAnswer)->createOneQuietly();
        $multipleChoiceAnswer2 = MultipleChoiceAnswer::factory()->for($formAnswer)->createOneQuietly();

        $this->assertTrue($formAnswer->multipleChoiceAnswers->contains($multipleChoiceAnswer1));
        $this->assertTrue($formAnswer->multipleChoiceAnswers->contains($multipleChoiceAnswer2));
    }

    public function testFormAnswerHasManyFileUploads()
    {
        $formAnswer = FormAnswer::factory()->createOneQuietly();
        $fileUpload1 = FileUpload::factory()->for($formAnswer)->createOneQuietly();
        $fileUpload2 = FileUpload::factory()->for($formAnswer)->createOneQuietly();

        $this->assertTrue($formAnswer->fileUploads->contains($fileUpload1));
        $this->assertTrue($formAnswer->fileUploads->contains($fileUpload2));
    }

    public function testFormAnswerHasManyPostAnswerNotes()
    {
        $formAnswer = FormAnswer::factory()->createOneQuietly();
        $postAnswerNote1 = PostAnswerNote::factory()->for($formAnswer)->createOneQuietly();
        $postAnswerNote2 = PostAnswerNote::factory()->for($formAnswer)->createOneQuietly();

        $this->assertTrue($formAnswer->postAnswerNotes->contains($postAnswerNote1));
        $this->assertTrue($formAnswer->postAnswerNotes->contains($postAnswerNote2));
    }
}
