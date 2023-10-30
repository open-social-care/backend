<?php

namespace Tests\Unit\Models;

use App\Models\FileUpload;
use App\Models\FormAnswer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FileUploadTest extends TestCase
{
    use RefreshDatabase;

    public function testFileUploadBelongsToFormAnswer()
    {
        $formAnswer = FormAnswer::factory()->createOneQuietly();
        $fileUpload = FileUpload::factory()->for($formAnswer)->createOneQuietly();

        $this->assertEquals($formAnswer->id, $fileUpload->form_answer_id);
    }
}
