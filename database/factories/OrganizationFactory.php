<?php

namespace Database\Factories;

use App\Enums\DocumentTypesEnum;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class OrganizationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $documentType = array_column(DocumentTypesEnum::cases(), 'value');

        return [
            'name' => fake()->name,
            'phone' => fake()->phoneNumber,
            'document_type' => $this->faker->randomElement($documentType),
            'document' => fake()->unique()->numerify('###.###.###-##'),
        ];
    }
}
