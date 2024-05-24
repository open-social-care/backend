<?php

namespace App\Actions\SocialAssistant\FormAnswer\ShortAnswer;

use App\DTO\SocialAssistant\ShortAnswerDTO;
use App\Models\ShortAnswer;
use Illuminate\Support\Facades\DB;

class ShortAnswerCreateAction
{
    /**
     * Execute action
     */
    public static function execute(ShortAnswerDTO $shortAnswerDTO): ShortAnswer
    {
        DB::beginTransaction();

        $data = $shortAnswerDTO->toArray();
        $shortAnswer = ShortAnswer::create($data);

        DB::commit();

        return $shortAnswer;
    }
}
