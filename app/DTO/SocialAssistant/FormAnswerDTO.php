<?php

namespace App\DTO\SocialAssistant;

use App\Models\Subject;
use App\Models\User;

class FormAnswerDTO
{
    public int $user_id;

    public int $subject_id;

    public int $form_template_id;

    /**
     * Construct class set DTO attributes
     */
    public function __construct(array $data, Subject $subject, User $user)
    {
        $this->user_id = $user->id;
        $this->subject_id = $subject->id;
        $this->form_template_id = data_get($data, 'form_template_id');
    }

    /**
     * Returns array of DTO attributes
     */
    public function toArray(): array
    {
        return [
            'user_id' => $this->user_id,
            'subject_id' => $this->subject_id,
            'form_template_id' => $this->form_template_id,
        ];
    }
}
