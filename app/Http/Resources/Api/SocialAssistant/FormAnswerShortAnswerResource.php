<?php

namespace App\Http\Resources\Api\SocialAssistant;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FormAnswerShortAnswerResource extends JsonResource
{
    /**
     * Return array of attributes
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'short_question_id' => $this->short_question_id,
            'answer' => $this->answer,
            'short_question' => FormTemplateShortQuestionResource::make($this->shortQuestion)->resolve(),
        ];
    }
}
