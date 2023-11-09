<?php

namespace Tests\Unit\Models;

use App\Models\FormAnswer;
use App\Models\PostAnswerNote;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostAnswerNoteTest extends TestCase
{
    use RefreshDatabase;

    public function testPostAnswerNoteBelongsToFormAnswer()
    {
        $formAnswer = FormAnswer::factory()->createOneQuietly();
        $postAnswerNote = PostAnswerNote::factory()->for($formAnswer)->createOneQuietly();

        $this->assertEquals($formAnswer->id, $postAnswerNote->form_answer_id);
    }
}
