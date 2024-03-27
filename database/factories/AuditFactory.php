<?php

namespace Database\Factories;

use App\Enums\AuditEventTypesEnum;
use App\Models\City;
use App\Models\State;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class AuditFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $user = User::factory()->createOneQuietly();
        $events = array_column(AuditEventTypesEnum::cases(), 'value');

        return [
            'model_type' => fake()->name(),
            'model_id' => fake()->unique()->numberBetween(1, 1000),
            'user_id' => $user->id,
            'event_type' => $this->faker->randomElement($events),
            'ip_address' => fake()->ipv6(),
        ];
    }
}
