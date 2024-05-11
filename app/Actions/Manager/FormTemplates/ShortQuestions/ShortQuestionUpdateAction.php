<?php

namespace App\Actions\Manager\FormTemplates\ShortQuestions;

use App\DTO\Manager\ShortQuestionDTO;
use App\Models\ShortQuestion;
use Illuminate\Support\Facades\DB;

class ShortQuestionUpdateAction
{
    /**
     * Execute update of organization
     */
    public static function execute(ShortQuestionDTO $dto, ShortQuestion $shortQuestion): void
    {
        DB::beginTransaction();

        $shortQuestion->update($dto->toArray());

        DB::commit();
    }
}
