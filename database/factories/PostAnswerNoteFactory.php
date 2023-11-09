<?php

namespace Database\Factories;

use App\Models\FormAnswer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class PostAnswerNoteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $formAnswer = FormAnswer::factory()->createOneQuietly();

        return [
            'form_answer_id' => $formAnswer->id,
            'note' => fake()->text,
        ];
    }
}
