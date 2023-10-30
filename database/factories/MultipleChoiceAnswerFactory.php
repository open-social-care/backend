<?php

namespace Database\Factories;

use App\Models\FormAnswer;
use App\Models\MultipleChoiceQuestion;
use App\Models\Subject;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class MultipleChoiceAnswerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $formAnswer = FormAnswer::factory()->createOneQuietly();
        $multipleChoiceQuestion = MultipleChoiceQuestion::factory()->createOneQuietly();
        $subject = Subject::factory()->createOneQuietly();

        $jsonData = [
            'option1' => $this->faker->word,
            'option2' => $this->faker->word,
        ];

        return [
            'form_answer_id' => $formAnswer->id,
            'answer' => json_encode($jsonData),
            'multiple_choice_question_id' => $multipleChoiceQuestion->id,
            'subject_id' => $subject->id,
        ];
    }
}
