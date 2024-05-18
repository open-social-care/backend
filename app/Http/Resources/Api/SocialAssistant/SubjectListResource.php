<?php

namespace App\Http\Resources\Api\SocialAssistant;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SubjectListResource extends JsonResource
{
    /**
     * Return array of attributes
     */
    public function toArray(Request $request): array
    {
        $lastFormAnswer = $this->formAnswers()->get()->last();

        return [
            'id' => $this->id,
            'name' => $this->name,
            'birth_date' => $this->birth_date,
            'last_form_answer_date' => optional($lastFormAnswer)->created_at,
        ];
    }
}
