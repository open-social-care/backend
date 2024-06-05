<?php

namespace App\Http\Resources\Api\SocialAssistant;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FormAnswerResource extends JsonResource
{
    /**
     * Return array of attributes
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'created_at' => $this->created_at->toISOString(),
            'user_name' => $this->user->name,
            'form_template_title' => $this->formTemplate->title,
            'short_answers' => FormAnswerShortAnswerResource::collection($this->shortAnswers)->resolve(),
        ];
    }
}
