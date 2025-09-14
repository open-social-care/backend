<?php

namespace App\Http\Resources\Api\SocialAssistant;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MultipleChoiceAnswerResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'question_description' => $this->multipleChoiceQuestion?->description,
            'answer_required' => (bool) $this->multipleChoiceQuestion?->answer_required,
            'answer' => $this->answer,
            'type' => 'multiple_choice',
        ];
    }
}