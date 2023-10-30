<?php

namespace Database\Factories;

use App\Models\FormTemplate;
use App\Models\State;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class FormAnswerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $user = User::factory()->createOneQuietly();
        $subject = Subject::factory()->createOneQuietly();
        $formTemplate = FormTemplate::factory()->createOneQuietly();

        return [
            'user_id' => $user->id,
            'subject_id' => $subject->id,
            'form_template_id' => $formTemplate->id,
        ];
    }
}
