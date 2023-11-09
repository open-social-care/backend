<?php

namespace Database\Factories;

use App\Models\FormTemplate;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class MultipleChoiceQuestionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $formTemplate = FormTemplate::factory()->createOneQuietly();

        return [
            'description' => fake()->text,
            'form_template_id' => $formTemplate->id,
            'data_type' => fake()->name,
            'answer_required' => fake()->boolean,
        ];
    }
}
