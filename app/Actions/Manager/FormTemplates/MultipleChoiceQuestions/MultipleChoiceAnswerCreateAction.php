<?php
namespace App\Actions\Manager\FormTemplates\MultipleChoiceQuestions;

use App\DTO\Manager\MultipleChoiceAnswerDTO;
use App\Models\MultipleChoiceAnswer;
use Illuminate\Support\Facades\DB;

class MultipleChoiceAnswerCreateAction
{
    public static function execute(MultipleChoiceAnswerDTO $dto): MultipleChoiceAnswer
    {
        DB::beginTransaction();

        $data = $dto->toArray();
        $multipleChoiceAnswer = MultipleChoiceAnswer::create($data);

        DB::commit();

        return $multipleChoiceAnswer;
    }
}
