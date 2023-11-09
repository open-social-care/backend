<?php

namespace Database\Factories;

use App\Models\FormAnswer;
use App\Models\ShortQuestion;
use App\Models\Subject;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class ShortAnswerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $shortQuestion = ShortQuestion::factory()->createOneQuietly();
        $formAnswer = FormAnswer::factory()->createOneQuietly();
        $subject = Subject::factory()->createOneQuietly();

        return [
            'short_question_id' => $shortQuestion->id,
            'form_answer_id' => $formAnswer,
            'subject_id' => $subject->id,
            'answer' => fake()->text,
        ];
    }
}
