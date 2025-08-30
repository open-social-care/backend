<?php

namespace App\Http\Controllers\Api\Manager;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\Manager\FormTemplateMultipleChoiceQuestionResource;
use App\Models\FormTemplate;
use App\Models\MultipleChoiceQuestion;
use Illuminate\Http\JsonResponse;

class ManagerFormTemplateMultipleChoiceQuestionController extends Controller
{
    public function show(FormTemplate $formTemplate, MultipleChoiceQuestion $multipleChoiceQuestion): FormTemplateMultipleChoiceQuestionResource
    {
        $this->authorize('view', $formTemplate);

        $multipleChoiceQuestion->load('options');

        return FormTemplateMultipleChoiceQuestionResource::make($multipleChoiceQuestion);
    }

}