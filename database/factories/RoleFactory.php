<?php

namespace Database\Factories;

use App\Enums\RolesEnum;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class RoleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $roles = array_column(RolesEnum::cases(), 'value');

        return [
            'name' => $this->faker->randomElement($roles),
        ];
    }
}
