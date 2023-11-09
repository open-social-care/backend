<?php

namespace Database\Factories;

use App\Models\MultipleChoiceQuestion;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class MultipleChoiceOptionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $multipleChoiceQuestionId = MultipleChoiceQuestion::factory()->createOneQuietly();

        return [
            'multiple_choice_question_id' => $multipleChoiceQuestionId->id,
            'description' => fake()->text,
        ];
    }
}
