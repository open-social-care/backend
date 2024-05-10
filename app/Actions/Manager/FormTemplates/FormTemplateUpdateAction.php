<?php

namespace App\Actions\Manager\FormTemplates;

use App\DTO\Manager\FormTemplateDTO;
use App\Models\FormTemplate;
use Illuminate\Support\Facades\DB;

class FormTemplateUpdateAction
{
    /**
     * Execute update of organization
     */
    public static function execute(FormTemplateDTO $dto, FormTemplate $formTemplate): void
    {
        DB::beginTransaction();

        $formTemplate->update($dto->toArray());

        DB::commit();
    }
}
