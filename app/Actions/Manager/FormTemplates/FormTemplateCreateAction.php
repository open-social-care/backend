<?php

namespace App\Actions\Manager\FormTemplates;

use App\DTO\Manager\FormTemplateDTO;
use App\Models\FormTemplate;
use Illuminate\Support\Facades\DB;

class FormTemplateCreateAction
{
    /**
     * Execute create of model
     */
    public static function execute(FormTemplateDTO $dto): void
    {
        DB::beginTransaction();

        $data = $dto->toArray();
        $data['has_file_uploads'] = false;
        FormTemplate::create($data);

        DB::commit();
    }
}
