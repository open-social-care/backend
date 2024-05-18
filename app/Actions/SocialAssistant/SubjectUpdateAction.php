<?php

namespace App\Actions\SocialAssistant;

use App\DTO\SocialAssistant\SubjectDTO;
use App\Models\Subject;
use Illuminate\Support\Facades\DB;

class SubjectUpdateAction
{
    /**
     * Execute update of user with sync roles and organizations
     */
    public static function execute(SubjectDTO $subjectDTO, Subject $subject): void
    {
        DB::beginTransaction();

        $data = $subjectDTO->toArray();
        $subject->update($data);

        DB::commit();
    }
}
