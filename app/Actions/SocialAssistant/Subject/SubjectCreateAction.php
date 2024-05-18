<?php

namespace App\Actions\SocialAssistant\Subject;

use App\DTO\SocialAssistant\SubjectDTO;
use App\Models\Subject;
use Illuminate\Support\Facades\DB;

class SubjectCreateAction
{
    /**
     * Execute action
     */
    public static function execute(SubjectDTO $subjectDTO): Subject
    {
        DB::beginTransaction();

        $data = $subjectDTO->toArray();
        $subject = Subject::create($data);

        DB::commit();

        return $subject;
    }
}
