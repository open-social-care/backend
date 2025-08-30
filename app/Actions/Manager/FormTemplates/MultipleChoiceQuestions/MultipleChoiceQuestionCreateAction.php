<?php

namespace App\Actions\Manager\FormTemplates\MultipleChoiceQuestions;

use App\DTO\Manager\MultipleChoiceQuestionDTO;
use App\Models\FormTemplate;
use App\Models\MultipleChoiceQuestion;
use Illuminate\Support\Facades\DB;

class MultipleChoiceQuestionCreateAction
{
    public static function execute(MultipleChoiceQuestionDTO $dto, FormTemplate $formTemplate): MultipleChoiceQuestion
    {
        return DB::transaction(function () use ($dto, $formTemplate) {

            $question = $formTemplate->multipleChoiceQuestions()->create([
                'description' => $dto->description,
                'answer_required' => $dto->answer_required,
                'data_type' => 'multiple_choice',
            ]);

            $optionsData = [];
            foreach ($dto->options as $optionText) {
                $optionsData[] = ['description' => $optionText];
            }

            $question->multipleChoiceOptions()->createMany($optionsData);

            return $question;
        });
    }
}