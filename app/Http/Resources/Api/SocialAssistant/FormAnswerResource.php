<?php

namespace App\Http\Resources\Api\SocialAssistant;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FormAnswerResource extends JsonResource
{
    /**
     * Return array of attributes
     */
    function toArray(Request $request): array
    {
        $shortAnswers = ShortAnswerResource::collection($this->whenLoaded('shortAnswers'));
        $multipleChoiceAnswers = MultipleChoiceAnswerResource::collection($this->whenLoaded('multipleChoiceAnswers'));

        $allAnswers = $shortAnswers->toBase()->merge($multipleChoiceAnswers);

        return [
            'id' => $this->id,
            'created_at' => $this->created_at,
            'user_name' => $this->user?->name,
            'form_template_title' => $this->formTemplate?->title,
            'question_answers' => $allAnswers,
        ];
    }
}
