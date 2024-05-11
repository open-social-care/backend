<?php

namespace App\Actions\Manager\FormTemplates\ShortQuestions;

use App\DTO\Manager\FormTemplateDTO;
use App\DTO\Manager\ShortQuestionDTO;
use App\Models\FormTemplate;
use App\Models\ShortQuestion;
use Illuminate\Support\Facades\DB;

class ShortQuestionCreateAction
{
    /**
     * Execute create of model
     */
    public static function execute(ShortQuestionDTO $dto, FormTemplate $formTemplate): void
    {
        DB::beginTransaction();

        $data = $dto->toArray();
        $data['form_template_id'] = $formTemplate->id;
        $data['data_type'] = 'short-question';
        ShortQuestion::create($data);

        DB::commit();
    }
}
