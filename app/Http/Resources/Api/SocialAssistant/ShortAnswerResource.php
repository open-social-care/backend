<?php

namespace App\Http\Resources\Api\SocialAssistant;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ShortAnswerResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'question_description' => $this->shortQuestion?->description,
            'answer_required' => (bool) $this->shortQuestion?->answer_required,
            'answer' => $this->answer,
            'type' => 'short_question',
        ];
    }
}