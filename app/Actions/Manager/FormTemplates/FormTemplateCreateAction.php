<?php

namespace App\Actions\Manager\FormTemplates;

use App\DTO\Manager\FormTemplateDTO;
use App\Models\FormTemplate;
use App\Models\Organization;
use Illuminate\Support\Facades\DB;

class FormTemplateCreateAction
{
    /**
     * Execute create of model
     */
    public static function execute(FormTemplateDTO $dto, Organization $organization): void
    {
        DB::beginTransaction();

        $data = $dto->toArray();
        $data['has_file_uploads'] = false;
        $formTemplate = FormTemplate::create($data);
        $formTemplate->organizations()->attach($organization);

        DB::commit();
    }
}
