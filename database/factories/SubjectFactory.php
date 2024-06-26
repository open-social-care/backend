<?php

namespace Database\Factories;

use App\Models\Organization;
use App\Models\User;
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
        $organization = Organization::factory()->createOneQuietly();
        $user = User::factory()->createOneQuietly();

        return [
            'name' => fake()->name,
            'birth_date' => fake()->date(),
            'organization_id' => $organization->id,
            'user_id' => $user->id,
        ];
    }
}
