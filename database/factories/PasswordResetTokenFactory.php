<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PasswordResetTokenFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $user = User::factory()->createOneQuietly();

        return [
            'email' => $user->email,
            'token' => mt_rand(100000, 999999),
            'created_at' => now()->addMinutes(20),
        ];
    }
}
