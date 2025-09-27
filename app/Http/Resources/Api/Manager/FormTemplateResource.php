<?php

namespace App\Http\Resources\Api\Manager;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FormTemplateResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $shortQuestions = $this->whenLoaded('shortQuestions');
        $multipleChoiceQuestions = $this->whenLoaded('multipleChoiceQuestions');

        $shortQuestionsArray = $shortQuestions->map(function ($question) {
            return (new FormTemplateShortQuestionResource($question))->resolve();
        });

        $multipleChoiceQuestionsArray = $multipleChoiceQuestions->map(function ($question) {
            return (new FormTemplateMultipleChoiceQuestionResource($question))->resolve();
        });

        $allQuestions = $shortQuestionsArray->toBase()->merge($multipleChoiceQuestionsArray);

        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'questions' => $allQuestions,
        ];
    }
}