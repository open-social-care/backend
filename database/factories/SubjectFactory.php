<?php

namespace Database\Factories;

use App\Enums\SkinColorsEnum;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class SubjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $skinColors = array_column(SkinColorsEnum::cases(), 'value');

        return [
            'name' => fake()->name,
            'relative_name' => fake()->name,
            'relative_relation' => fake()->name,
            'birth_date' => fake()->dateTimeThisDecade,
            'contact_phone' => fake()->phoneNumber,
            'cpf' => fake()->unique()->numerify('###.###.###-##'),
            'rg' => fake()->unique()->numerify('##.###.###-#'),
            'skin_color' => $this->faker->randomElement($skinColors),
        ];
    }
}
