<?php

namespace Database\Factories;

use App\Models\FormTemplate;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class ShortQuestionFactory extends Factory
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
            'data_type' => fake()->name,
            'form_template_id' => $formTemplate->id,
            'answer_required' => fake()->boolean,
        ];
    }
}
