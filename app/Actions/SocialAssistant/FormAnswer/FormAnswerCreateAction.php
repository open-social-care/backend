<?php

namespace App\Actions\SocialAssistant\FormAnswer;

use App\DTO\SocialAssistant\FormAnswerDTO;
use App\Models\FormAnswer;
use Illuminate\Support\Facades\DB;

class FormAnswerCreateAction
{
    /**
     * Execute action
     */
    public static function execute(FormAnswerDTO $formAnswerDTO): FormAnswer
    {
        DB::beginTransaction();

        $data = $formAnswerDTO->toArray();
        $formAnswer = FormAnswer::create($data);

        DB::commit();

        return $formAnswer;
    }
}
