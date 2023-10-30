<?php

namespace Database\Factories;

use App\Models\City;
use App\Models\State;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class AddressFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $state = State::factory()->createOneQuietly();
        $city = City::factory()->createOneQuietly();

        return [
            'model_type' => fake()->name(),
            'model_id' => fake()->unique()->numberBetween(1, 1000),
            'street' => fake()->name(),
            'number' => fake()->numerify(),
            'district' => fake()->name(),
            'complement' => fake()->name(),
            'state_id' => $state->id,
            'city_id' => $city->id,
            'is_secondary_address' => fake()->boolean,
        ];
    }
}
